<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KPI;
use App\Models\SDGTopic;
use App\Models\Division;
use App\Models\Company;
use App\Models\User;
use App\Models\ReviewCycle;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KPImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

use Exception;

class KPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $companies = Company::where('status','!=', 0)->get();
        // $cycles = ReviewCycle::where('is_current',1)->get();
        $cycles = ReviewCycle::all();
        $sdgtopics = SDGTopic::all();
        $divisions = Division::all();

        if(request()->ajax()) {
            return datatables()->of(KPI::select('*'))
            ->addColumn('action', 'backend.kpis.action')
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return [
                    'formated_date' => Carbon::parse($user->created_at)->format('M d Y'),
                    'timestamp' => $user->created_at->timestamp
                ];
            })
            ->editColumn('cycle_id', function($cycle_ids) {

                $cycles = DB::table('review_cycles')
                    ->whereIn('id', (array)$cycle_ids->cycle_id)
                    ->get();

                return json_decode($cycles);
            })
            ->editColumn('target', function($data) {
                return number_format($data->target);
            })
            ->editColumn('sdg_topic_id', function($user) {

                $sdgtopics = SDGTopic::all();
                foreach ($sdgtopics as $key => $sdgtopic) {
                    if($user->sdg_topic_id == $sdgtopic->id){
                        return $sdgtopic->label;
                    }
                }
            })
            // ->editColumn('division_id', function($user) {

            //     $items = DB::table('divisions')
            //         ->whereIn('id', $user->division_id)
            //         ->get();

            //     $items = json_decode(json_encode($items),true);

            //     return $items;

            // })
            ->editColumn('company_id', function($company_ids) {

                $companies = Company::where('status', '!=', 0)->get();
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

        return view('backend.kpis.index', compact('companies', 'cycles', 'sdgtopics', 'divisions'));
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
            'label' => 'required', //|unique:kpis,label
            'cycle_id' => 'required',
            'target' => 'numeric|min:0'
        ]);


        try {

            $details = [
                'label' => $request->label,
                'cycle_id' =>  $request->cycle_id,
                'target' => (int)$request->target,
                'company_id' => 1, // (int)$request->company_id,
                'sdg_topic_id' => (int)$request->sdg_topic_id,
                'division_id' => $request->division_id,
                'user_id' => $request->session()->get('userData')->id

             ];


            $kpi = KPI::Create($details);

            return response()->json([
                'data'     =>  $details ,
                'status'   => 'success',
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
        $kpi  = KPI::where($where)->first();

        return Response::json($kpi);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate      \Http\Response
     */
    public function destroy($id)
    {
        try{

            $kpi = KPI::findOrFail($id);
            $kpi->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $kpi->label.' Deleted successfully',
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

            Excel::import(new KPImport, $request->file('file')->store('temp'));

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
