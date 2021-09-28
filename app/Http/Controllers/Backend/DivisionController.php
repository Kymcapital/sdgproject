<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Division;
use App\Models\Company;
use App\Models\User;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DivisionImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

use Exception;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $companies = Company::where('status','!=',0)->get();

        if(request()->ajax()) {
            return datatables()->of(Division::select('*'))
            ->addColumn('action', 'backend.divisions.action')
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

        return view('backend.divisions.index', compact('companies'));
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
            'label' => 'required', //|unique:divisions,label
        ]);

        try {

            $divisionId = $request->division_id;

            // $details = [
            //     'label' => $request->label,
            //     'company_id' => $request->company_id,
            //     'user_id' => $request->session()->get('userData')->id
            // ];

            // $division = Division::updateOrCreate(['id' => $divisionId, $details);

            $division = Division::updateOrCreate(
                [
                    'id' => $divisionId,
                    'label' => $request->label,
                    'company_id' => $request->company_id,
                    'user_id' => $request->session()->get('userData')->id
                ]
            );

            return response()->json([
                'status' => 'success',
                'message'  => $request->label.' successfully added/updated!',
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
        $division  = Division::where($where)->first();

        return Response::json($division);
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

            $division = Division::findOrFail($id);
            $division->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $division->label.' Deleted successfully',
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

            Excel::import(new DivisionImport, $request->file('file')->store('temp'));

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
