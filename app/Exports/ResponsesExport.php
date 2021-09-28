<?php

namespace App\Exports;

use App\Models\Response;
use App\Models\User;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResponsesExport implements FromArray,WithHeadings,WithMultipleSheets,WithTitle
{

}
