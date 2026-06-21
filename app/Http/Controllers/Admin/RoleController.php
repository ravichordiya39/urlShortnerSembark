<?php

namespace App\Http\Controllers\Admin;


use App\Models\Permission;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('role_list')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $roles = Role::with('permissions')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
            
    
        return view('admin.roles.list', compact('roles'));
    }
    

    public function roleListAjax(Request $request)
    {
  
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->input('search.value');
    
        $query = Role::with('permissions')->whereNull('deleted_at');
    
        if (!empty($searchValue)) {
            $query->where('title', 'like', $searchValue . '%');
        }
    
        $total = $query->count();
    
        $results = $query->orderBy('created_at', 'desc')
            ->offset($start)
            ->take($length)
            ->get()
            ->toArray();
    
        $data = [];
        $no = 1;
       
    
        foreach ($results as $role) {
            $permissionsBadges = '';
            if (!empty($role['permissions'])) {
                foreach ($role['permissions'] as $perm) {
                    $permissionsBadges .= '<span class="badge badge-info mr-1">' . $perm['title'] . '</span>';
                }
            }

            $role['action'] = '<div class="row"><a href="'.route('admin.roles.edit', $role["id"]).'" type="button" class="btn bg-gradient-info btn-sm">Edit</a><button type="button" class="btn bg-gradient-danger btn-sm deleteRole" data-id='.$role["id"].'>Delete</button></div>';
            
            $role['no'] = $start + $no;
     
         
            $role['role'] = $role['title'];
        
            $role['permissions'] = $permissionsBadges;
            $role['created_at_dt'] = !empty($role["created_at"])
            ? (new \DateTime($role["created_at"], new \DateTimeZone('UTC')))
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
    

    public function changeStatusRole(Request $request)
    {
        try {
            $role = Role::findOrFail($request->id);
    
            if ($request->status === 'N' && $role->users()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot deactivate this role because it is assigned to one or more users.'
                ], 403);
            }
    
            $role->status = $request->status;
            $role->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Role status updated successfully.'
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'data' => $e->getMessage(),
                'status' => 'fail',
                'error' => 1
            ]);
        }
    }
    
    public function deleteRole(Request $request)
    {
        try {
            $role = Role::findOrFail($request->id); // ✅ Fixed: used $request->id
    
            if ($role->users()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete this role because it is assigned to one or more users.'
                ], 403);
            }
    
            $role->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted successfully.'
            ]);
    
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
        if (!auth()->user()->can('role_add')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $permissions = Permission::where('status', 'Y')
                ->whereNull('deleted_at')
                ->orderBy('title', 'asc')
                ->get();
    
        return view('admin.roles.add', compact('permissions'));
    }
    
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:tbl_roles,title,NULL,id,deleted_at,NULL',
            'permissions' => 'array',
        ], [
            'title.required' => 'Role title is required.',
            'title.unique'   => 'Role has already been taken.',
        ]);
        
    
        $role = Role::create(['title' => $request->title]);
        $role->permissions()->sync($request->permissions);
    
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }
    


    public function show($id)
    {
        // 
    }
    

    public function edit($id)
    {
        if (!auth()->user()->can('role_edit')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $role = Role::with('permissions')->findOrFail($id);
    
        $permissions = Permission::where('status', 'Y')
                        ->whereNull('deleted_at')
                        ->orderBy('title', 'asc')
                        ->get();
    
        return view('admin.roles.edit', compact('role', 'permissions'));
    }
    
    

    public function update(Request $request, $id)
    {
      

        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_roles', 'title')->ignore($id)->whereNull('deleted_at'),
            ],
            'permissions' => 'array',
        ], [
            'title.required' => 'Role title is required.',
            'title.unique'   => 'Role title has already been taken.',
        ]);
    
        $role = Role::findOrFail($id);
        $role->update(['title' => $request->title]);
        $role->permissions()->sync($request->permissions);
    
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }
    

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
    
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
    
}
