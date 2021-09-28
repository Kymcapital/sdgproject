<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GRI extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="gris";
  
    protected $fillable = [
        'gri_number',
        'description',
        'company_id',
        'user_id'
    ];
}
