<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\SDGExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\EmailSubmissions;

use App\Models\Response;
use App\Models\KPI;
use App\Models\SDGTopic;
use App\Models\Submission;
use App\Models\Division;

use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Redirect,DB;
use File;
use PDF;

use Exception;

class ResponseController extends Controller
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
        $divisions = Division::all();

        try {

            //fetch selected sdg topic
            $sdgtopics_kpis = DB::table('sdgtopics')
                ->where('id', '=', $request->sdg_topic_id)
                ->select('label', 'id')
                ->first();

            //filter selected division
            $divisions_kpis = DB::table('divisions')
                ->where('id', '=', $request->sdg_division_id)
                ->select('label', 'id')
                ->first();

            //fetch filtered kpis,sdg,cycles etc.
            $kpis = DB::table('kpis')
                ->join('sdgtopics', function ($join) use($request,$sdgtopics_kpis){
                    if(!empty($request->sdg_topic_id) && $sdgtopics_kpis->id == (int)$request->sdg_topic_id){
                        $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id')
                            ->where('kpis.sdg_topic_id', '=', $request->sdg_topic_id);
                    } else{
                        $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id');
                    }
                })
                ->leftJoin('responses', function ($join) {
                    $join->on('responses.kpi_id', '=', 'kpis.id');
                })
                ->select(DB::raw('kpis.*, sdgtopics.label as sdgtopics_label, responses.sub_total, responses.total, responses.status')) //, review_cycles.label as review_cycles_label
                ->where('kpis.deleted_at', NULL)
                ->orderBy('sdg_topic_id')
                ->get();
                $kpis = $kpis->groupBy('sdg_topic_id'); //Merge same data by sdg topic

                // filter by division
                if(!empty($request->sdg_division_id) && $divisions_kpis->id === (int)$request->sdg_division_id && $request->session()->get('userData')->division_id !== $request->sdg_division_id){

                    $getDivision = DB::table('divisions')->where('id', (int)$request->sdg_division_id)
                        ->select('label')
                        ->first();

                    $kpisArray = [];
                    foreach ($kpis as $key => $value) {
                        foreach ($value as $key2 => $items) {
                            // print_r($items->division_id);
                            // if(in_array($request->sdg_division_id,json_decode($items->division_id))){

                                $result = (array) $items;

                                $result['division_name'] = $getDivision->label;
                                array_push($kpisArray, $result);

                            // }
                        }
                    }
                    $kpis = collect($kpisArray);
                    $kpis = $kpis->groupBy('sdg_topic_id');

                } else if($request->session()->get('userData')->division_id){ // filter by users division

                    $getDivision = DB::table('divisions')->where('id', $request->session()->get('userData')->division_id)
                        ->select('label')
                        ->first();

                    $kpisArray = [];
                    foreach ($kpis as $key => $value) {
                        foreach ($value as $key2 => $items) {
                           // if(in_array($request->session()->get('userData')->division_id,json_decode($items->division_id))){

                                $result = (array) $items;

                                $result['division_name'] = $getDivision->label;
                                array_push($kpisArray, $result);

                           // }
                        }
                    }
                    $kpis = collect($kpisArray);
                    $kpis = $kpis->groupBy('sdg_topic_id');
                }

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

            if(!empty($request->sdg_topic_id) && $sdgtopics_kpis->id == (int)$request->sdg_topic_id OR !empty($request->sdg_division_id) && $divisions_kpis->id == (int)$request->sdg_division_id){

                if(!empty($request->sdg_division_id)){
                    $selectedDivision = $divisions_kpis->id;
                    $labelDivision = $divisions_kpis->label.' - ';
                } else {
                    $selectedDivision = '';
                    $labelDivision = '';
                }

                if(!empty($request->sdg_topic_id)){
                    $selectedSDGTopic = $sdgtopics_kpis->id;
                    $labelTopic = $sdgtopics_kpis->label;
                } else {
                    $selectedSDGTopic = '';
                    $labelTopic = '';
                }

                return view('backend.responses.index',compact('sdgtopics','kpis','submissions', 'selectedSDGTopic', 'selectedDivision', 'divisions'))->with('info', 'You selected: '.$labelDivision.' '.$labelTopic);

            }
            $selectedSDGTopic = '';
            $selectedDivision = '';
            return view('backend.responses.index',compact('sdgtopics','kpis','submissions','selectedDivision', 'divisions', 'selectedSDGTopic'));

        }catch(Exception $e){

            $selectedSDGTopic = '';
            $selectedDivision = '';
            return view('backend.responses.index',compact('sdgtopics','selectedDivision', 'divisions','selectedSDGTopic'))->with('error', $e->getMessage());

        }
    }

    /**
     * Update the specified resource status to either approve or reject.
     *
     * @param  int  $status
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id, $status)
    {

        Response::updateOrCreate(
            ['kpi_id' => $id],
            [
                'status' => $status,
            ]
        );

        $kpi = DB::table('kpis')
            ->where('id', '=', $id)
            ->select('label', 'id')
            ->first();

        // $user = DB::table('responses')
        //     ->join('users', function ($join) use($id) {
        //         $join->on('users.id', '=', 'responses.user_id')
        //             ->where('responses.kpi_id', '=', $id);
        //     })
        //     ->leftJoin('kpis', function ($join) {
        //         $join->on('kpis.id', '=', 'responses.kpi_id');
        //     })
        //     ->leftJoin('sdgtopics', function ($join) {
        //         $join->on('sdgtopics.id', '=', 'responses.sdg_topic_id');
        //     })
        //     ->select(DB::raw('users.id, users.first_name, users.email, users.last_name, sdgtopics.label as sdgtopics_label, kpis.label as kpis_label, responses.target, responses.achievement, responses.sub_total, responses.total')) //, review_cycles.label as review_cycles_label
        //     ->where('responses.deleted_at', NULL)
        //     ->first();

        // \Mail::to($user->email)
        //     ->send(new ApprovedNotApproved($user));

        if($status){
            $status = ' (Not Approved) '.$kpi->label;
            return redirect()->route('responses.index')->with('warning', 'Successfully '.$status.'. Kindly filter to see the results!');
        }else{
            $status = ' (Approved) '.$kpi->label;
            return redirect()->route('responses.index')->with('success', 'Successfully '.$status.'. Kindly filter to see the results!');
        }

    }

    public function export(Request $request)
    {

        return Excel::download(new SDGExport(request()->get('sdg_topic_id')), 'sdgs.xlsx');

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

                //1. insert update responses
                $responses = Response::updateOrCreate(
                    ['kpi_id' => $kpi_id],
                    [
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

                $count++;

            }

            $kpis = DB::table('kpis')
                ->where('id', '=', $kpi_ids)
                ->select('label', 'id')
                ->first();

            $user = [];
            \Mail::to($request->session()->get('userData')->email)
                ->send(new EmailSubmissions($user));

            return redirect()->route('responses.index')->with('success', 'Submission(s) added/updated successfully. Kindly filter to see the results!');

        }catch(Exception $e){

            return redirect()->route('responses.index')->with('error', $e->getMessage());

        }

    }

}
