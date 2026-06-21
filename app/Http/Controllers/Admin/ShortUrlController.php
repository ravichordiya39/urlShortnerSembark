<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use DataTables;

use App\Models\ShortUrl;
use App\Models\CompanyInvitedUsers;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('short_url_list')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $shorturls = ShortUrl::where('deleted_at', '=', null)
        ->orderBy('id', 'desc')
        ->get();
    
        return view('admin.shorturls.list', compact('shorturls'));
    }

    public function shortUrlsListAjax(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->input('search.value');

        $loggedUser = auth()->user();
         $isAdmin = $loggedUser->roles()
            ->whereIn('title', ['Admin'])
            ->where('status', 'Y')
            ->whereNull('deleted_at')
            ->exists();

        $isMember= $loggedUser->roles()
            ->whereIn('title', ['Member'])
            ->where('status', 'Y')
            ->whereNull('deleted_at')
            ->exists();

        $companies = ShortUrl::select('id', 'original_url','short_url','created_by','status', 'created_at');

        if($isMember){
            $companies->where('created_by',$loggedUser->id);
        }

        if($isAdmin){
            $baseComp = User::where('id',$loggedUser->id)->first();
            $usersCompId = CompanyInvitedUsers::where('user_id',$loggedUser->id)->get();

            $usersCompIdArr = [];
            $usersCompIdArr [] = $baseComp->company_id;
            foreach ($usersCompId as $key => $usrCompId) {
                $usersCompIdArr [] = $usrCompId->company_id;
            }

            $companies->whereIn('company_id',$usersCompIdArr);
        }
    
        if (!empty($searchValue)) {
            $companies->where('short_url', 'like', $searchValue . '%');
        }
    
        $total = $companies->count();
    
        $results = $companies->orderBy('id', 'desc')->offset($start)->take($length)->get()->toArray();
    
        $data = [];
        $no = 1;
    
        foreach ($results as $perm) {
            $perm['no'] = $request->start + $no;
             $perm['title'] = "<a href='".$perm['original_url']."'>".$perm['short_url']."</a>";
              $perm['original_url'] = $perm['original_url'];
            $perm['status'] = $perm['status'] ;
            $perm['created_at_dt'] = !empty($perm["created_at"])
            ? (new \DateTime($perm["created_at"], new \DateTimeZone('UTC')))
                ->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                ->format('Y-m-d h:i:s A')
            : "";
            $perm['action'] = '';
           // $perm['action'] =  '<div class="row"><a href="'.route('admin.shortUrls.edit', $perm["id"]).'" type="button" class="btn bg-gradient-info btn-sm">Edit</a><button type="button" class="btn bg-gradient-danger btn-sm deletePermission" data-id='.$perm["id"].'>Delete</button></div>';
    
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

    public function changeStatusShortUrls(Request $request)
    {
        try {
            $company = ShortUrl::findOrFail($request->id);
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
    


    public function deleteShortUrl(Request $request)
    {
        try {
            $company = ShortUrl::findOrFail($request->id);
    
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
        if (!auth()->user()->can('short_url_add')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $companies = Company::where('status', 'Y')->whereNull('deleted_at')->get();
        return view('admin.shorturls.add',compact('companies'));
    }

public function store(Request $request)
{
    $loggedUser = auth()->user();
    $short_url = "http://".Str::random(10);
    $request->validate([
        'original_url' => 'required',
        'company_id' => 'required',
    ]);

    ShortUrl::create([
        'original_url' => $request->original_url,
        'short_url' => $short_url,
        'created_by' => $loggedUser->id,
        'company_id' => $request->company_id
    ]);

    return redirect()->route('admin.shortUrls.index')->with('success', 'ShortUrl created successfully.');
}


    public function show($id)
    {
       //
    }

    public function edit($id)
    {
        if (!auth()->user()->can('short_url_edit')) {
            return redirect()->back()
                ->with('error','You do not have permission.');
        }
        $shorturl = ShortUrl::findOrFail($id);
        $companies = Company::where('status', 'Y')->whereNull('deleted_at')->get();
        return view('admin.shorturls.edit', compact('shorturl','companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'company_id' => 'required',
        ]);

        $company = ShortUrl::findOrFail($id);
        $company->update([
            'title' => $request->title,
            'updated_at' => now()->timestamp,
        ]);

        return redirect()->route('admin.shortUrls.index')->with('success', 'ShortUrl updated successfully.');
    }

    public function destroy($id)
    {
        $company = ShortUrl::findOrFail($id);
        $company->delete();
    
        return redirect()->route('admin.shortUrls.index')->with('success', 'ShortUrl deleted successfully.');
    }
    
}
