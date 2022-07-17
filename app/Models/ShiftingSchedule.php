<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'morning_start', 
        'morning_stop',
        'afternoon_start', 
        'afternoon_stop',
    
    ];

}
