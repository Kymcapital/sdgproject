<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KPI extends Model
{
    use HasFactory, SoftDeletes;

    protected $table="kpis";

    // protected $casts = [
    //     'division_id' => 'json',
    //     'cycle_id' => 'json'
    // ];

    protected $fillable = [
        'label',
        'cycle_id',
        'target',
        'company_id',
        'sdg_topic_id',
        'division_id',
        'user_id'
    ];

    // Fetch kpis belonging to a specific company
    public function company(){
        return $this->belongsTo('App\Models\Company','company_id');
    }

    // Fetch kpis belonging to a specific division
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }

    // Fetch kpis belonging to a specific sdg_topic
    public function sdg_topic(){
        return $this->belongsTo('App\Models\Division','sdg_topic_id');
    }

     // Review Cycles
     public function review_cycle(){
        return $this->belongsTo(ReviewCycle::class,'cycle_id');
    }


}
