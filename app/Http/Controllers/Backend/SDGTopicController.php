<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SDGTopic;
use App\Models\Company;
use App\Models\User;
use App\Models\GRI;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SDGTopicImport;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,Response,DB;
use File;
use PDF;

use Exception;

class SDGTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       

        $gris = GRI::all();
        $companies = Company::where('status','!=', 0)->get();

        if(request()->ajax()) {
            return datatables()->of(SDGTopic::select('*'))
            ->addColumn('action', 'backend.sdg-topics.action')
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
            ->editColumn('gri_id', function($gri_ids) {

                $gris = DB::table('gris')
                    ->whereIn('id', (array)$gri_ids->gri_id)
                    ->get();

                return json_decode($gris);

            })
            ->make(true);
        }

        return view('backend.sdg-topics.index', compact('companies', 'gris'));
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
            'label' => 'required', //|unique:sdgtopics,label
        ]);

        try {

            $sdg_topicId = $request->sdg_topic_id;

            $details = [
                'label' => $request->label,
                'gri_id' => $request->gri_id,
                'company_id' => $request->company_id,
                'user_id' => $request->session()->get('userData')->id
            ];

            $sdg_topic = SDGTopic::updateOrCreate(['id' => $sdg_topicId], $details);

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
        $sdg_topic  = SDGTopic::where($where)->first();

        return Response::json($sdg_topic);
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

            $sdg_topic = SDGTopic::findOrFail($id);
            $sdg_topic->delete();

            return response()->json([
                'status' => 'success',
                'message'  => $sdg_topic->label.' Deleted successfully',
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

            Excel::import(new SDGTopicImport, $request->file('file')->store('temp'));

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
