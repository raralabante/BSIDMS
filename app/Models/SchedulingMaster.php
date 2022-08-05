<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulingMaster extends Model
{
    use HasFactory;

    protected $dates = [
		'created_at',
		'eta',
	];

    protected $fillable = [
        'id', 
        'customer_name',
        'job_number',
        'client_name',
        'address',
        'type',
        'prestart',
        'stage',
        'brand',
        'job_type',
        'category',
        'floor_area',
        'prospect',
        'hitlist',
        'status',
    ];

    public function assigns(){
        return $this->hasMany(JobTimeHistory::class,'scheduling_masters_id');
    }
    
    public function assign_checker(){
        return $this->hasOne(JobTimeHistory::class,'scheduling_masters_id');
    }
}
