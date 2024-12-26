<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'usertype',
        'hospital_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
