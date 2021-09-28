<?php

namespace App\Http\Controllers\Frontend;

use DB;
use Exception;
use App\Models\Division;
use App\Models\ReviewCycle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $year = null)
    {

         //Number of year with records
         $reviewYears = ReviewCycle::select("year")->groupBy('year')->get();
         $selectedYear =  $request->input('year');
         //ajax request to group by years result

         if (request()->ajax()) {

             $getCurrentReviewCycle  = ReviewCycle::where('is_current', 1)->get();
             $reviewCycleByYear      = ReviewCycle::where('year', $selectedYear)->get();

            //  // set current selecte cycle to zero, this wont appear on graphs
             foreach($getCurrentReviewCycle as $key => $val){
                 // print_r($value->is_current);
                 ReviewCycle::where('id', $val->id)->update([
                     'is_current' => 0
                 ]);
             }

            //  // Set selected cycle to current for graph display
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




        //get divisions
        $divisions = Division::all();

        $responses = DB::table('sdgtopics')
            ->leftJoin('responses', function ($join) {
                $join->on('sdgtopics.id', '=', 'responses.sdg_topic_id')
                    ->where('responses.status', 0);
            })
            ->leftJoin('kpis', function ($join) {
                $join->on('responses.kpi_id', '=', 'kpis.id');
            })
            ->leftJoin('review_cycles', function ($join) {
                $join->on('kpis.cycle_id', '=', 'review_cycles.id');

            })

            ->select(DB::raw('responses.*,  review_cycles.id as review_cycle_id, review_cycles.is_current as is_current, review_cycles.label as review_cycle_label, review_cycles.start_date as review_cycle_start_date,review_cycles.year as review_cycle_year, sdgtopics.label as sdgtopics_label, sdgtopics.deleted_at, sdgtopics.id as sdgtopics_id, kpis.label as kpis_label'))
            ->orderBy('sdgtopics_id', 'ASC')
            ->where('sdgtopics.deleted_at', NULL)
            ->where('review_cycles.is_current', '=', 1)

            ->get();

        $responses = $responses->groupBy('sdgtopics_label');

        $colorSchemes =array(
            ['#a80034', '#f0004a'],
            ['#52a1ff', '#a3cdff'],
            ['#fec30f', '#fee69d'],
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
            ['#a80034', '#f0004a'],
            ['#4db7ff', '#86cdfd'],
            ['#3c8246', '#52ce61'],
            ['#ffd400', '#fdea8f'],
            ['#a80034', '#f0004a'],
            ['#4db7ff', '#86cdfd'],
        );

        $chartGoals = array();$i=0;
        $sumKPIs = 0;
        $sumKPIsAvarage = 0;

        foreach($responses as $key1 => $achievements){

            $chartName = "chartJsGoalScore1".bin2hex(random_bytes(8)).$i;

            $counter = 0;
            $sumKPIs = 0;
            $sumApprovedKpis = 0;
            foreach($achievements as $key2 => $achievement){
                if($i==9) $i=0;//  Ensure Color scheme array is available to all charts.Only 9 etc options available

                //calculate percentage
                if($achievement->total && $achievement->status == 0){
                    $counter ++;

                    $sumApprovedKpis += (int)$achievement->total;

                    $sumKPIs = round($sumApprovedKpis / $counter, 2, PHP_ROUND_HALF_UP);
                }

                //calculate remainder
                $remainder = 100-$sumKPIs;

                $chartjs = app()->chartjs
                    ->name($chartName)
                    ->type('doughnut')
                    ->size(['width' => 400, 'height' => 400])
                    ->datasets([
                        [
                            'backgroundColor' => $colorSchemes[$i],
                            'hoverBackgroundColor' => $colorSchemes[$i][1],
                            'data' => [$sumKPIs, $remainder]
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

            }

            array_push($chartGoals,$chartjs);$i+=1;

        }

        return view('frontend.pages.overview', compact('divisions','chartGoals','reviewYears'));
    }

}
