<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_id',
        'division_id',
        'role_id',
        'permission_id',
        'user_id'
    ];

    // Fetch users belonging to a specific company
    public function company(){
        return $this->belongsTo('App\Models\Company','company_id');
    }
    
    // Fetch users belonging to a specific division
    public function division(){
        return $this->belongsTo('App\Models\Division','division_id');
    }
}
