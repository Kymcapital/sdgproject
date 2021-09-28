<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewCycle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'review_cycles';

    protected $fillable = [
        'label',
        'start_date',
        'end_date',
        'year',
        'is_current',
        'company_id',
        'user_id'
    ];
}
