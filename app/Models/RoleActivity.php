<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity_id', 
        'role',
        'user_id',
        'created_at',
    ];
}
