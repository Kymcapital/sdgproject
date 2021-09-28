<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies';
  
    protected $fillable = [
        'name',
        'contact_email',
        'user_id',
        'status',
        'logo'
    ];
    /**
     * Get the divisions associated with the company.
     */
    public function division(){
        return $this->hasOne(Division::class);
    }
}
