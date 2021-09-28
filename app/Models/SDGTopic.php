<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SDGTopic extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="sdgtopics";

    protected $casts = [
        'gri_id' => 'array'
    ];
  
    protected $fillable = [
        'label',
        'gri_id',
        'company_id',
        'user_id'
    ];

    // Fetch sdg_topics belonging to a specific company
    public function company(){
        return $this->belongsTo('App\Models\Company','company_id');
    }
}
