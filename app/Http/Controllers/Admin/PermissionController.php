<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DataTables;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {   
        if (!auth()->user()->can('permission_list')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $permissions = Permission::where('deleted_at', '=', null)
        ->orderBy('title', 'asc')
        ->get();
    
        return view('admin.permissions.list', compact('permissions'));
    }

    public function permissionListAjax(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->input('search.value');
    
        $permissions = Permission::select('id', 'title','status', 'created_at');
    
        if (!empty($searchValue)) {
            $permissions->where('title', 'like', $searchValue . '%');
        }
    
        $total = $permissions->count();
    
        $results = $permissions->orderBy('id', 'desc')->offset($start)->take($length)->get()->toArray();
    
        $data = [];
        $no = 1;
    
        foreach ($results as $perm) {
            $perm['no'] = $request->start + $no;
            $perm['status'] = $perm['status'] ;
            $perm['created_at_dt'] = !empty($perm["created_at"])
            ? (new \DateTime($perm["created_at"], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                ->format('Y-m-d h:i:s A')
            : "";
    
            $perm['action'] =  '<div class="row"><a href="'.route('admin.permissions.edit', $perm["id"]).'" type="button" class="btn bg-gradient-info btn-sm">Edit</a><button type="button" class="btn bg-gradient-danger btn-sm deletePermission" data-id='.$perm["id"].'>Delete</button></div>';
    
            $data[] = $perm;
            $no++;
        }
    
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ]);
    }

    public function changeStatusPermission(Request $request)
    {
        try {
            $permission = Permission::findOrFail($request->id);
            $status = strtoupper($request->status);
    
            if ($status === 'N' && $permission->roles()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot deactivate this permission because it is assigned to one or more roles.',
                    'status' => 'error',
                    'error' => 1
                ], 403);
            }
    
            $permission->status = $status;
            $permission->save();
    
            return response()->json([
                'message' => 'Permission status updated successfully',
                'status' => 'success',
                'error' => 0
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
    


    public function deletePermission(Request $request)
    {
        if (!auth()->user()->can('permission_delete')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        try {
            $permission = Permission::findOrFail($request->id);
    
            if ($permission->roles()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete this permission because it is assigned to one or more roles.',
                    'status' => 'error',
                    'error' => 1
                ], 403);
            }
    
            $permission->delete();
    
            return response()->json([
                'message' => 'Permission deleted successfully.',
                'status' => 'success',
                'error' => 0
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
        if (!auth()->user()->can('permission_add')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        return view('admin.permissions.add');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:50|unique:tbl_permissions,title,NULL,id,deleted_at,NULL',
    ], [
        'title.unique' => 'Permission has already been taken.',
    ]);

    Permission::create([
        'title' => $request->title,
    ]);

    return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
}


    public function show($id)
    {
       //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('permission_edit')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:50',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'title' => $request->title,
            'updated_at' => now()->timestamp,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
    
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
    
}
