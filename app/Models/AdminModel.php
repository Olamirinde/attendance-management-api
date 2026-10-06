<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'name', 
        'email', 
        'password'
        ];

    protected $hidden = ['password'];
}