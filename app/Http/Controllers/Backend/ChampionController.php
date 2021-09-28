<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KPI;
use App\Models\SDGTopic;
use App\Models\Response;
use App\Models\Submission;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,DB;
use File;
use PDF;

use Exception;

class ChampionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //fetch all sdg topics
        $sdgtopics = SDGTopic::all();

        try {
            
            //fetch selected sdg topic
            $sdgtopics_kpis = DB::table('sdgtopics')
                ->where('id', '=', $request->sdg_topic_id)
                ->select('label', 'id')
                ->first();

            //fetch filtered kpis,sdg,cycles etc.
            $kpis = DB::table('kpis')
                ->join('sdgtopics', function ($join) use($request,$sdgtopics_kpis){
                    if(!empty($request->sdg_topic_id) && $sdgtopics_kpis->id == (int)$request->sdg_topic_id){
                        $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id')
                            ->where('kpis.sdg_topic_id', '=', $request->sdg_topic_id);
                    }else{
                        $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id');
                    }
                })
                ->leftJoin('responses', function ($join) {
                    $join->on('responses.kpi_id', '=', 'kpis.id');
                })
                //->join('review_cycles', 'review_cycles.id', '=', 'kpis.cycle_id')
                ->select(DB::raw('kpis.*, sdgtopics.label as sdgtopics_label, responses.sub_total, responses.total')) //review_cycles.label as review_cycles_label,
                ->where('kpis.deleted_at', NULL)
                ->get();
                $kpis = $kpis->groupBy('sdg_topic_id'); //Merge same data by sdg topic

                //get submission history
                $submissions = DB::table('submissions')
                    ->join('responses', function ($join){
                        $join->on('submissions.response_id', '=', 'responses.id');
                    })
                    ->leftJoin('users', function ($join) {
                        $join->on('users.id', '=', 'responses.user_id');
                    })
                    ->select(DB::raw('submissions.*, users.first_name, submissions.last_submission'))
                    ->get();
                $submissions = $submissions->groupBy('kpi_id');

                //dd(json_decode($submissions));
                
            if(!empty($request->sdg_topic_id) && $sdgtopics_kpis->id == (int)$request->sdg_topic_id){

                return view('backend.champions.index',compact('sdgtopics','kpis','submissions'))->with('success', 'You selected: '.$sdgtopics_kpis->label);

            }
            return view('backend.champions.index',compact('sdgtopics','kpis','submissions'));
            
        }catch(Exception $e){

            return view('backend.champions.index',compact('sdgtopics'))->with('error', $e->getMessage());

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{

            $input = $request->except('_token');

            $kpi_ids = $request->kpi_id;
            $targets = $request->target;
            $sub_totals = $request->sub_total;
            $sdg_topic_ids = $request->sdg_topic_id;
            $division_ids = $request->division_id;
            
            $count=0;
            foreach ($kpi_ids as $index => $kpi_id) {

                // request()->validate([
                //     'sub_total' => 'sometimes|exists_or_null:responses,sub_total',
                // ]);

                //dd(array_filter($sub_totals));

                //if($sub_totals[$index] != 0){

                    //1. insert update responses
                    $responses = Response::updateOrCreate(
                        ['kpi_id' => $kpi_id],
                        [
                            'status' => (int)1,
                            'target' => $targets[$index], 
                            'sub_total' => (int)$sub_totals[$index],
                            'sdg_topic_id' => $sdg_topic_ids[$index],
                            'division_id' => json_decode($division_ids[$index]),
                            'total'=> (int)$sub_totals[$index] / (int)$targets[$index] * 100,
                            'company_id' => 1,
                            'user_id' => $request->session()->get('userData')->id
                        ]
                    );
                    $id = $responses->id;

                    //dd($sdg_topic_ids);

                    //2. save submission history
                    Submission::updateOrCreate(
                        ['last_submission' => $sub_totals[$index]],
                        [   
                            'response_id' => $id,
                            'kpi_id' => $kpi_id,
                            'company_id' => 1,
                            'user_id' => $request->session()->get('userData')->id
                        ]
                    );

                // }else{
                //     return redirect()->route('champions.index')->with('warning', 'Empty value or Zero(0) is not required!');
                // }

                $count++;

            }

            return redirect()->route('champions.index')->with('success', 'Submissions added/updated successfully!');

        }catch(Exception $e){

            return redirect()->route('champions.index')->with('error', $e->getMessage());

        }

    }
}
