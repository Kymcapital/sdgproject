<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'divisions';
  
    protected $fillable = [
        'label',
        'company_id',
        'user_id'
    ];

    /**
     * Get the divisions associated with the company.
     */
    public function company(){
        return $this->hasOne(Company::class, 'id');
    }
}
