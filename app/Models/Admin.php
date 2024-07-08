<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name', 'role', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
