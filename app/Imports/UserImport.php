<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class UserImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'first_name'=>$row[0],
            'last_name'=>$row[1],
            'email'=>$row[2],
            'company_id'=>1,
            'user_id'=>\Session::get('userData')->id
        ]);
    }
}
