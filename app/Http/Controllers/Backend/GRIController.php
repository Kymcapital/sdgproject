<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GRI;
use App\Models\Company;
use App\Models\User;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GRIImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

class GRIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $companies = Company::where('status','!=', 0)->get();

        if(request()->ajax()) {
            return datatables()->of(GRI::select('*'))
            ->addColumn('action', 'backend.gri.action')
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return [
                    'formated_date' => Carbon::parse($user->created_at)->format('M d Y'),
                    'timestamp' => $user->created_at->timestamp
                ];
            })
            ->editColumn('company_id', function($company_ids) {
                
                $companies = Company::where('status','!=', 0)->get();
                foreach ($companies as $key => $company) {
                    if($company_ids->company_id == $company->id){
                        return $company->name;
                    }
                }
            })
            ->editColumn('user_id', function($user_ids) {
                
                $users = User::all();
                foreach ($users as $key => $user) {
                    if($user_ids->user_id == $user->id){
                        return $user->first_name.' '.$user->last_name;
                    }
                }
            })
            ->make(true);
        }

        return view('backend.gri.index', compact('companies'));
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
            'gri_number' => 'required|numeric|min:0', //unique:gris,gri_number|
        ]);

        try {
     
            $griId = $request->gri_id;
        
            $details = [
                'gri_number' => $request->gri_number, 
                'description' => $request->description,
                'company_id' => $request->company_id,
                'user_id' => $request->session()->get('userData')->id
            ];
            
            $gri = GRI::updateOrCreate(['id' => $griId], $details);

            return response()->json([
                'status' => 'success',
                'message'  => $request->gri_number.' successfully added/updated!',
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
        $gri  = GRI::where($where)->first();
      
        return Response::json($gri);
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

            $gri = GRI::findOrFail($id);
            $gri->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $gri->gri_number.' Deleted successfully',
            ], 200);

        }catch(Exception $e){

            return response()->json([
                'status' => 'error',
                'message'  => $e->getMessage(),
            ], 500);

        }
    }
    /**
     * Import the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,ods|max:2048',
        ]);

        try{

            Excel::import(new GRIImport, $request->file('file')->store('temp'));

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
