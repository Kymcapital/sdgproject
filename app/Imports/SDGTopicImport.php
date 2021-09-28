<?php

namespace App\Imports;

use App\Models\SDGTopic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class SDGTopicImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SDGTopic([
            'label'=>$row[0],
            'company_id'=> 1,
            'user_id'=>\Session::get('userData')->id
        ]);
    }
}
