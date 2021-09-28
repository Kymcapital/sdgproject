<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;
  
    protected $fillable = [
        'response_id',
        'kpi_id',
        'last_submission',
        'company_id',
        'user_id'
    ];
}
