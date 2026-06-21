<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DataTables;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('company_list')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $companies = Company::where('deleted_at', '=', null)
        ->orderBy('title', 'asc')
        ->get();
    
        return view('admin.companies.list', compact('companies'));
    }

    public function companyListAjax(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->input('search.value');
    
        $companies = Company::select('id', 'title','status', 'created_at');
    
        if (!empty($searchValue)) {
            $companies->where('title', 'like', $searchValue . '%');
        }
    
        $total = $companies->count();
    
        $results = $companies->orderBy('id', 'desc')->offset($start)->take($length)->get()->toArray();
    
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
    
            $perm['action'] =  '<div class="row"><a href="'.route('admin.companies.edit', $perm["id"]).'" type="button" class="btn bg-gradient-info btn-sm">Edit</a><button type="button" class="btn bg-gradient-danger btn-sm deletePermission" data-id='.$perm["id"].'>Delete</button></div>';
    
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

    public function changeStatusCompany(Request $request)
    {
        try {
            $company = Company::findOrFail($request->id);
            $status = strtoupper($request->status);
    
            if ($status === 'N' && $company->roles()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot deactivate this company because it is assigned to one or more roles.',
                    'status' => 'error',
                    'error' => 1
                ], 403);
            }
    
            $company->status = $status;
            $company->save();
    
            return response()->json([
                'message' => 'Company status updated successfully',
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
    


    public function deleteCompany(Request $request)
    {
        try {
            $company = Company::findOrFail($request->id);
    
            if ($company->roles()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete this company because it is assigned to one or more roles.',
                    'status' => 'error',
                    'error' => 1
                ], 403);
            }
    
            $company->delete();
    
            return response()->json([
                'message' => 'Company deleted successfully.',
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
        if (!auth()->user()->can('company_add')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        return view('admin.companies.add');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:50|unique:tbl_companies,title,NULL,id,deleted_at,NULL',
    ], [
        'title.unique' => 'Company has already been taken.',
    ]);

    Company::create([
        'title' => $request->title,
    ]);

    return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
}


    public function show($id)
    {
       //
    }

    public function edit($id)
    {

        if (!auth()->user()->can('company_edit')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:50',
        ]);

        $company = Company::findOrFail($id);
        $company->update([
            'title' => $request->title,
            'updated_at' => now()->timestamp,
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
    
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully.');
    }
    
}
