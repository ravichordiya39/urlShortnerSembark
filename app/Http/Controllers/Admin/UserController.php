<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Company;
use App\Models\CompanyInvitedUsers;


class UserController extends Controller
{

    public function index()
    {
        if (!auth()->user()->can('user_list')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $users = User::with('roles')->latest()->get();
        $companies = Company::where('status','Y')->get();
        return view('admin.users.list', compact('users','companies'));
    }



    public function userListAjax(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->input('search.value');

         $loggedUser = auth()->user();

        // Check logged-in user role
        $isSupAdmin = $loggedUser->roles()
        ->whereIn('title', ['Superadmin'])
        ->where('status', 'Y')
        ->whereNull('deleted_at')
        ->exists();

        $isAdmin = $loggedUser->roles()
        ->whereIn('title', [ 'Admin'])
        ->where('status', 'Y')
        ->whereNull('deleted_at')
        ->exists();
    
        $query = User::with(['roles' => function ($q) {
            $q->where('status', 'Y')->whereNull('deleted_at');
        }])->whereNull('deleted_at');
        
    
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', $searchValue . '%')
                  ->orWhere('email', 'like', $searchValue . '%');
            });
        }
    
        $total = $query->count();
        
        $results = $query->orderBy('id', 'desc')
            ->offset($start)
            ->take($length)
            ->get()
            ->toArray();
    
        $data = [];
        $no = 1;

        foreach ($results as $role) {
            $permissionsBadges = '';
            $roleName = '';
            if (!empty($role['roles'])) {
                foreach ($role['roles'] as $perm) {
                    $permissionsBadges .= '<span class="badge badge-info mr-1">' . $perm['title'] . '</span>';
                    $roleName .= $perm['title'];

                }
            }
            $action = '';
            $action .= '<div class="row"><a href="'.route('admin.users.edit', $role["id"]).'" type="button" class="btn bg-gradient-info btn-sm">Edit</a><button type="button" class="btn bg-gradient-danger btn-sm deleteUser" data-id='.$role["id"].'>Delete</button>';

            if($isSupAdmin && $roleName == 'Admin'){
                 $action .= '<button type="button" class="btn bg-gradient-primary btn-sm inviteUser openUserModal" data-id='.$role["id"].' data-name='.$role["name"].' data-email='.$role["email"].'>Invite User</button>';
            }else if($isAdmin && ($roleName == 'Admin' || $roleName == 'Member')){
                 $action .= '<button type="button" class="btn bg-gradient-primary btn-sm inviteUser openUserModal" data-id='.$role["id"].' data-name='.$role["name"].' data-email='.$role["email"].'>Invite User</button>';
            }
                $action .= '</div>';

            $role['action'] = $action;
            
            $role['id'] = $role['id'];
            $role['name'] = $role['name'];
         
            $role['email'] = $role['email'];
            $role['status'] = $role['status'];
        
            $role['roles'] = $permissionsBadges;
           
            $role['created_at_dt'] = !empty($role["created_at"])
            ? (new \DateTime($role["created_at"], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                ->format('Y-m-d h:i:s A')
            : "";
            $role['updated_at_dt'] = !empty($role["updated_at"])
            ? (new \DateTime($role["updated_at"], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                ->format('Y-m-d h:i:s A')
            : "";
           
            $data[] = $role;
            $no++;
        }
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }


    public function changeStatusUser(Request $request) {

        try {
            $request->validate([
                'id' => 'required|exists:users,id',
                'status' => 'required|in:Y,N'
            ]);
            $id = $request->id;
            $status = $request->status;
            
            $roleUpdate = User::where('id',$id)->update(['status' => $status]);
            if($roleUpdate){
                return response()->json([
                    'message' => 'User status updated successfully',
                    'data' => '',
                    'status' => 'success',
                    'error' => 0
                ]);
            }else{
                return response()->json([
                    'message' => 'Something went wrong !',
                    'data' => '',
                    'status' => 'fail',
                    'error' => 1
                ]); 
            }
            
        } catch (\Throwable $e) {
            return response()->json([
                    'message' => 'Something went wrong !',
                    'data' => $e->getMessage(),
                    'status' => 'fail',
                    'error' => 1
            ]); 
        }
        
       
    }
    public function deleteUser(Request $request)
    {
        try {
            $id = $request->id;
            $delete = User::where('id', $id)->delete();
    
            if ($delete) {
                return response()->json([
                    'message' => 'User deleted successfully.',
                    'status' => 'success',
                    'error' => 0
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to delete User.',
                    'status' => 'fail',
                    'error' => 1
                ]);
            }
    
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'data' => $e->getMessage(),
                'status' => 'fail',
                'error' => 1
            ]);
        }
    }


    public function create() 
    {
        if (!auth()->user()->can('user_add')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $roles = Role::where('status', 'Y')->where('id','!=', '1')->whereNull('deleted_at')->get();
        $companies = Company::where('status', 'Y')->whereNull('deleted_at')->get();
        return view('admin.users.add', compact('roles','companies'));
    }
    

    public function store(Request $request)
    {
        $request->merge([
            'name' => trim($request->name)
        ]);
        
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?!\s*$).+/'
            ],
            //'email' => 'required|email|unique:users,email',
            'email' => [
                            'required',
                            'email:rfc,dns',
                            Rule::unique('users')
                                ->where(function ($query) use ($request) {
                                    return $query->where('company_id', $request->company_id);
                                })
                        ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^\S*$/', // No spaces allowed
            ],
            'roles' => 'array',
            'roles.*' => 'exists:tbl_roles,id'
        ], [
            'password.confirmed' => 'Password and Confirm Password do not match.',
            'name.regex' => 'The name cannot be empty or contain only spaces.'
        ]);
        
        
    
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'password' => \Hash::make($request->password),
        ]);

        // Attach roles (many-to-many)
        $user->roles()->sync($request->input('roles', []));
    
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    


    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        if (!auth()->user()->can('user_edit')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $user = \App\Models\User::findOrFail($id);
        $roles = Role::where('status', 'Y')->where('id','!=', '1')->whereNull('deleted_at')->get();
        $companies = Company::where('status', 'Y')->whereNull('deleted_at')->get();
        $userRoleIds = $user->roles()->pluck('id')->toArray();
    
        return view('admin.users.edit', compact('user', 'roles', 'userRoleIds','companies'));
    }
    
    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            //'email' => 'required|email|unique:users,email,' . $id,
            'email' => [
                            'required',
                            'email:rfc,dns',
                            Rule::unique('users')
                                ->where(function ($query) use ($request) {
                                    return $query->where('company_id', $request->company_id);
                                })
                                ->ignore($id)
                        ],
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:tbl_roles,id',
        ]);
    
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = \Hash::make($request->password);
        }
    
        $user->update($data);
    
        // Update roles
        $user->roles()->sync($request->input('roles', []));
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     public function inviteUser(Request $request)
    {
        try {
            $user_id = $request->id;
            $company_id = $request->company_id;
            $loggedUser = auth()->user();
            $user = User::where('id', $user_id)->where('company_id', $company_id)->first();
    
            if ($user) {
                return response()->json([
                    'message' => 'User already registered with this company.',
                    'status' => 'fail',
                    'error' => 1
                ]);
            } 
            
            $alrInvuser = CompanyInvitedUsers::where('user_id', $user_id)->where('company_id', $company_id)->where('invited_by', $loggedUser->id)->first();

             if ($alrInvuser) {
                return response()->json([
                    'message' => 'User already registered with this company.',
                    'status' => 'fail',
                    'error' => 1
                ]);
            } 

            // Check logged-in user role
            $isAdmin = $loggedUser->roles()
            ->whereIn('title', ['Admin'])
            ->where('status', 'Y')
            ->whereNull('deleted_at')
            ->exists();

            if($isAdmin){
                $baseComp = User::where('id',$loggedUser->id)->first();
                $usersCompId = CompanyInvitedUsers::where('user_id',$loggedUser->id)->get();

                $usersCompIdArr = [];
                $usersCompIdArr [] = $baseComp->company_id;
                foreach ($usersCompId as $key => $usrCompId) {
                    $usersCompIdArr [] = $usrCompId->company_id;
                }

                if(!in_array($company_id,$usersCompIdArr)){
                     return response()->json([
                        'message' => "Admin don't owns this company.",
                        'status' => 'fail',
                        'error' => 1
                    ]);
                }
            }

             $userComp = \App\Models\CompanyInvitedUsers::create([
                                        'user_id' => $user_id,
                                        'company_id' => $company_id,
                                        'invited_by' => $loggedUser->id
                                    ]);

            if($userComp) {
                return response()->json([
                    'message' => 'User Invited.',
                    'status' => 'success',
                    'error' => 0
                ]);
            }
    
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'data' => $e->getMessage(),
                'status' => 'fail',
                'error' => 1
            ]);
        }
    }
}