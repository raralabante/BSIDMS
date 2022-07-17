<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTimeHistory extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'id', 
        'user_id',
        'drafting_masters_id',
        'type',
        'morning_start',
        'morning_stop',
        'afternoon_start',
        'afternoon_stop',
        'hours',
    ];

   


}
