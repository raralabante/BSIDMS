<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedJobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'drafting_masters_id', 
        'scheduling_masters_id', 
        'rejected_by', 
    ];

}
