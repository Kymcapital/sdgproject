<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\User;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompanyImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

use Exception;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Company::select('*'))
            ->addColumn('action', 'backend.companies.action')
            ->addColumn('logo', 'backend.companies.logo')
            ->rawColumns(['action','logo'])
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return [
                    'formated_date' => Carbon::parse($user->created_at)->format('M d Y'),
                    'timestamp' => $user->created_at->timestamp
                ];
            })
            ->editColumn('status', function($user) {
                if($user->status):
                    return 'Active';
                else:
                    return 'Not Active';
                endif;
            })
            ->make(true);
        }
        return view('backend.companies.index');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required', //|unique:companies,name
            'contact_email' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1000',
        ]);

        try {
     
            $companyId = $request->company_id;
        
            $details = [
                'name' => $request->name, 
                'contact_email' => $request->contact_email, 
                'user_id' => $request->session()->get('userData')->id
            ];
        
            if ($files = $request->file('logo')) {
                //delete old file
                \File::delete('images/company/'.$request->hidden_logo);
                
                //insert new file
                $destinationPath = 'images/company/'; // upload path
                $profileLogo = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileLogo);
                $details['logo'] = "$profileLogo";
            }
            
            $company = Company::updateOrCreate(['id' => $companyId], $details);

            return response()->json([
                'status' => 'success',
                'message'  => $request->name.' successfully added/updated!',
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $company  = Company::where($where)->first();
      
        return Response::json($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $data = Company::where('id',$id)->first(['logo']);

            //delete folder file
            $image_path = public_path("images/company/".$data->logo);
            if(file_exists($image_path)){
                \File::delete($image_path);
            }

            //delete db file
            $company = Company::findOrFail($id);
            $company->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $company->name.' Deleted successfully',
            ], 200);

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 500);

        }
    }

    /**
     * Update the specified resource from status.
     *
     * @param  int  $id, $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, $status)
    {
        try{
            Company::where('id', $id)
                ->update(['status' => $status]);

            $company = Company::findOrFail($id);
            if($status){
                $statusVal = "un-locked";
            }else{
                $statusVal = "locked";
            }
            return response()->json([
                'success' => 'false',
                'message'  => $company->name.' Successfully '.$statusVal,
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                'success' => 'false',
                'message'  => $e->getMessage(),
            ], 400);

        }
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods|max:2048',
        ]);

        try{

            Excel::import(new CompanyImport, $request->file('file')->store('temp'));

            return response()->json([
                'status' => 'success',
                'message'  => 'All data successfully imported!',
            ], 200);
            
        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 400);

        }

    }
}
