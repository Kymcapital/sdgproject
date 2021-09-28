<?php

namespace App\Http\Controllers\Frontend;

use DB;
use App\Models\Division;
use App\Models\Response;
use App\Models\ReviewCycle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index(Request $request,$id, $year = null){

        //Number of year with records
        $reviewYears = ReviewCycle::select("year")->groupBy('year')->get();
        $selectedYear =  $request->input('year');
        //ajax request to group by years result

        if (request()->ajax()) {
            $getCurrentReviewCycle  = ReviewCycle::where('is_current', 1)->get();
            $reviewCycleByYear      = ReviewCycle::where('year', $selectedYear)->get();

            // set current selecte cycle to zero, this wont appear on graphs
            foreach($getCurrentReviewCycle as $key => $val){
                // print_r($value->is_current);
                ReviewCycle::where('id', $val->id)->update([
                    'is_current' => 0
                ]);
            }

            // Set selected cycle to current for graph display
            foreach($reviewCycleByYear as $key => $value){
                // print_r($value->is_current);
               $is_updated = ReviewCycle::where('id', $value->id)->update([
                    'is_current' => 1
                ]);
            }

            if($is_updated){
                return response()->json(['message'=>'success','status' => 200]);
            }

        }

        if($id){

            $divisions = Division::all();
            $division = Division::where('id', $id)->first();

            $responses = DB::table('kpis')
                ->join('sdgtopics', function ($join){
                    $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id');
                })
                ->leftJoin('responses', function ($join) {
                    $join->on('responses.kpi_id', '=', 'kpis.id');
                    // ->where('responses.status', 0);
                })
                ->leftJoin('review_cycles', function ($join) {
                    $join->on('kpis.cycle_id', '=', 'review_cycles.id');

                })

                ->select(DB::raw('kpis.*, review_cycles.id as review_cycle_id, review_cycles.is_current as is_current, review_cycles.label as review_cycle_label, review_cycles.start_date as review_cycle_start_date,review_cycles.year as review_cycle_year, sdgtopics.label as sdgtopics_label, sdgtopics.id as sdgtopics_id, responses.sub_total, responses.total, responses.status, kpis.label as kpis_label, kpis.id as kpis_id'))
                ->where('kpis.deleted_at', NULL)
                ->orderBy('sdgtopics_id', 'ASC')
                ->where('review_cycles.is_current', '=', 1)
                ->get();

                 //Articles::whereYear('created_at', date('Y'))->get();
               // dd($responses);

                // $filteredResponse = [];
                // foreach ($responses as $key => $value) {
                //     if (gettype( json_decode($value->division_id)) === 'array') {
                //         if(in_array($id, json_decode($value->division_id))){
                //             array_push($filteredResponse, $value);
                //         }
                //     }
                // }

                $filteredResponse = [];
                foreach ($responses as $key => $value) {
                    if(($id == $value->division_id)){
                        array_push($filteredResponse, $value);
                    }
                }

            $responses = collect($filteredResponse)->groupBy('sdgtopics_label');
            // dd(  $responses );

            $colorSchemes =array(
                ['#a80034', '#f0004a'],
                ['#52a1ff', '#a3cdff'],
                ['#3c8246', '#52ce61'],
                ['#38437a', '#7087f5'],
                ['#52a1ff', '#a3cdff'],
                ['#3c8246', '#52ce61'],
                ['#ffd400', '#fdea8f'],
                ['#a80034', '#f0004a'],
                ['#4db7ff', '#86cdfd'],
                ['#dc3545', '#dc354573'],
                ['#3c8246', '#52ce61'],
                ['#ffd400', '#fdea8f'],
                ['#a80034', '#f0004a'],
                ['#52a1ff', '#a3cdff'],
                ['#fec30f', '#fee69d'],
                ['#a80034', '#f0004a'],
                ['#3c8246', '#52ce61'],
                ['#ffd400', '#fdea8f'],
                ['#a80034', '#f0004a'],
                ['#4db7ff', '#86cdfd'],
            );

            $chartGoals = array();$i=0;
            $chartjsBarGoals = array();$i=0;
            $sumKPIsAvarage = 0;
            $sumKPIs = 0;
            $sumApprovedKpis = 0;

            foreach ($responses as $key1 => $achievements) {
                $counter = 0;
                $sumKPIs = 0;
                $kpiLabels = [];
                $kpiSubTotal = [];
                $sumApprovedKpis = 0;

                foreach ($achievements as $key2 => $achievement) {
                    if ($i==$key2) {
                        $i=0;
                    }//  Ensure Color scheme array is available to all charts.Only 9 etc options available

                    $chartName = "chartJsGoalScore2021".bin2hex(random_bytes(8)).$i;
                    $chartNameBar = "chartJsGoalScore2021".bin2hex(random_bytes(8)).$i;

                    //calculate percentage
                    if ($achievement->total && $achievement->status == 0) {
                        $counter ++;

                        $sumApprovedKpis += $achievement->total;
                        $sumKPIs = round($sumApprovedKpis / $counter, 2, PHP_ROUND_HALF_UP);
                    }

                    //calculate remainder
                    $remainder = 100-$sumKPIs;

                    //get labels/sub-total with commas
                    if ($achievement->total && $achievement->status == 0) {
                        $vvv = $achievement->total;
                    } else {
                        $vvv = 0;
                    }

                    array_push($kpiLabels, $achievement->kpis_label);
                    array_push($kpiSubTotal, $vvv);

                    //1 .doughnut
                    $chartjs = app()->chartjs
                        ->name($chartName)
                        ->type('doughnut')
                        ->size(['width' => 400, 'height' => 400])
                        ->datasets([
                            [
                                'backgroundColor' => $colorSchemes[$i],
                                'hoverBackgroundColor' => $colorSchemes[$i][1],
                                'data' => [$sumKPIs, $remainder]//[$achievement['value']['val1'], $achievement['value']['val2']],
                            ]
                        ])
                        ->labels(
                            ['achieved', 'remainder']
                        )
                        ->options([
                            'responsive' => true,
                            'percentageTest' => [
                                'center' => [
                                    'text' => $sumKPIs.'%', //$achievement['percentage'].'%',
                                    'color' => $colorSchemes[$i], // Default is #000000
                                    'fontStyle' => '"Montserrat", sans-serif', // Default is 'Montserrat', sans-serif
                                    'sidePadding' => '15', // Default is 15 (as a percentage)
                                    'minFontSize' => '10', // Default is 10 (in px), set to false and text will not wrap.
                                    'lineHeight' => '10' // Default is 15 (in px), used for when text wraps
                                ],
                            ],
                            'title' => [
                                'display' => true,
                                'position' => 'bottom',
                                'fontSize' => 12,
                                'text' => $achievement->sdgtopics_label,
                            ]
                        ]);
                    //end

                    //horizontalBar
                    $chartjsBar = app()->chartjs
                        ->name($chartNameBar)
                        ->type('horizontalBar')
                        ->size(['width' => 400, 'height' => 200])
                        ->labels($kpiLabels)
                        ->datasets([
                            [
                                "label" => "KPI's",
                                'backgroundColor' => [
                                    '#f0004a',
                                    '#a3cdff',
                                    '#fee69d',
                                    '#7087f5',
                                    '#a3cdff',
                                    '#52ce61',
                                    '#fdea8f',
                                    '#f0004a',
                                    '#86cdfd',
                                    '#dc354573',
                                    '#52ce61',
                                    '#fdea8f',
                                    '#f0004a',
                                    '#86cdfd',
                                    '#dc354573',
                                    '#f0004a',
                                    '#a3cdff',
                                    '#fee69d',
                                    '#7087f5',
                                    '#a3cdff',
                                ],
                                'data' => $kpiSubTotal
                            ],
                        ])
                        ->options([
                            // 'scales' => [
                            //     'yAxes' => [
                            //         'barPercentage' => '0.5'
                            //     ]
                            // ],
                            'percentageTest' => [
                                'center' => [
                                    'text' => '', //$achievement->sdgtopics_label,
                                    'color' => '#efefef', // Default is #000000
                                    'fontStyle' => '"Montserrat", sans-serif', // Default is 'Montserrat', sans-serif
                                    'sidePadding' => '15', // Default is 15 (as a percentage)
                                    'minFontSize' => '10', // Default is 10 (in px), set to false and text will not wrap.
                                    'lineHeight' => '10' // Default is 15 (in px), used for when text wraps
                                ],
                            ],
                        ]);
                    ///end
                }

                if (isset($chartjs)) {
                    array_push($chartGoals, $chartjs);
                    $i+=1;
                }

                if (isset($chartjsBar)) {
                    array_push($chartjsBarGoals, $chartjsBar);
                    $i+=1;
                }
            }
        }

        return view('frontend.pages.topics', compact('divisions','division','chartGoals','chartjsBarGoals','reviewYears'));

    }

}
