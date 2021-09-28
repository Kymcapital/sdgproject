<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Response extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'division_id' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kpi_id',
        'target',
        'achievement',
        'sub_total',
        'total',
        'company_id',
        'sdg_topic_id',
        'division_id',
        'user_id',
        'status'
    ];
    
    // Fetch responses belonging to a specific question
    public function question(){
        return $this->belongsTo('App\Models\Division','question_id');
    }
    
    // Fetch responses belonging to a specific sdg_topic
    public function sdg_topic(){
        return $this->belongsTo('App\Models\Division','sdg_topic_id');
    }

    // Fetch responses belonging to a specific company
    public function company(){
        return $this->belongsTo('App\Models\Company','company_id');
    }
    
    // Fetch responses belonging to a specific division
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
}
