<?php

namespace App\Imports;

use App\Models\KPI;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class KPImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Auth::user();
        
        return new KPI([
            'label'=>$row[0],
            'target'=>$row[1],
            'company_id'=> 1,
            'cycle_id'=> [0],
            'sdg_topic_id'=> 0,
            'division_id'=> 0,
            'user_id'=>\Session::get('userData')->id

        ]);
    }
}
