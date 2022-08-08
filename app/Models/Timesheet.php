<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheets';
    protected $date = ['created_at','updated_at'];
    protected $fillable = [
        'id',
        'drafting_masters_id',
        'scheduling_masters_id',
        'user_id',
        'type',
        'job_start',
        'job_stop',
    ];
}
