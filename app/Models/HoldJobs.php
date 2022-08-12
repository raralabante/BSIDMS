<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoldJobs extends Model
{
    use HasFactory;
    protected $fillable = [
        'drafting_masters_id', 
        'scheduling_masters_id',
        'hold_start',
        'hold_end',
    ];


}
