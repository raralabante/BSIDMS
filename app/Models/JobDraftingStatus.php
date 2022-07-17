<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDraftingStatus extends Model
{
    use HasFactory;

    protected $table = 'job_drafting_status';
    protected $date = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = [
        'id', 
        'user_id',
        'drafting_masters_id',
        'status',
        'type',
    ];


}


