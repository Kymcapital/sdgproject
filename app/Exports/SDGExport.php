<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\Response;
use App\Models\KPI;
use App\Models\SDGTopic;
use App\Models\Submission;

use DB;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;

class SDGExport implements FromArray, WithHeadings
//, WithMapping
{
    use Exportable;

    public $sdg_topic_id;

    public function __construct($sdg_topic_id) //int
    {
        $this->sdg_topic_id = $sdg_topic_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'SDG Topic',
            'KPI',
            'Division',
            'Target',
            'Submission',
            'Percentage',
            'Date',
            'Review Cycle',
            //'Review Cycle'
        ];
    }

    public function array():array
    {

        //fetch selected sdg topic
        $sdgtopics_kpis = DB::table('sdgtopics')
            ->where('id', '=', $this->sdg_topic_id)
            ->select('label', 'id')
            ->first();

        $review_cycles = DB::table('review_cycles')
            ->select('label', 'id')
            ->first();

        //fetch filtered kpis,sdg,cycles etc.
        $kpis = DB::table('kpis')
            ->join('sdgtopics', function ($join) use($sdgtopics_kpis){
                if(!empty($this->sdg_topic_id) && $sdgtopics_kpis->id == (int)$this->sdg_topic_id){
                    $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id')
                        ->where('kpis.sdg_topic_id', '=', $this->sdg_topic_id)->whereIn('kpis.cycle_id', $this->id);
                }else{
                    $join->on('kpis.sdg_topic_id', '=', 'sdgtopics.id');
                }
            })
            ->leftJoin('responses', function ($join) {
                $join->on('responses.kpi_id', '=', 'kpis.id');
            })
            // ->leftJoin('review_cycles', function ($join) use($review_cycles){
            //     $join->on('review_cycles.id', '=', 'kpis.cycle_id')
            //         //->whereIn($review_cycles->id, 'review_cycles.id');
            // })

            //->join('review_cycles', 'review_cycles.id', '=', 'kpis.cycle_id')

            ->select(DB::raw('kpis.*, sdgtopics.label as sdgtopics_label, responses.sub_total, responses.total, responses.status')) // review_cycles.label as review_cycles_label,
            ->where('kpis.deleted_at', NULL)
            ->get();

            $kpis = $kpis->groupBy('sdg_topic_id'); //Merge same data by sdg topic

            //dd($kpis);

            $collectedResponse=array();
            foreach ($kpis as $key => $values):
                foreach($values as $key => $kpi):

                    $divisions = DB::table('divisions')
                        ->whereIn('id',json_decode($kpi->division_id))
                        ->select('label')
                        ->get();

                        foreach ($divisions as $key => $division) :

                            $employeeResponse=array(
                                'sdgtopics_label' => $kpi->sdgtopics_label,
                                'label' => $kpi->label, //kpi
                                'division' => $division->label,
                                'target' => $kpi->target,
                                'sub_total' => $kpi->sub_total,
                                'total' => $kpi->total,
                                'created_at' => \Carbon\Carbon::parse($kpi->created_at)->format('Y-m-d'),
                                'review_cycles_label' => env('REVIEW_CYCLE_DATE'),
                                //'review_cycles_label' => $kpi->review_cycles_label,
                            );

                        endforeach;
                        array_push($collectedResponse,$employeeResponse);

                endforeach; 
            endforeach;
            
        return $collectedResponse;

    }

}
