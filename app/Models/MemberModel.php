<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberModel extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'member_code',
        'name',
        'email',
        'phone',
        'type',
        'department_or_class',
        'status',
        'created_by',
    ];
}