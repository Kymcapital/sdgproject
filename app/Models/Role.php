<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
  
    protected $fillable = [
        'name',
        'description'
    ];
    
    /**
     * Get the permisions associated with the role.
     */
    public function permission(){
        return $this->hasOne(Permission::class, 'id');
    }
}
