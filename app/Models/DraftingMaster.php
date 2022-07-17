<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DraftingMaster extends Model
{
    use HasFactory;
    // use SoftDeletes;
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
        'eta',
        'brand',
        'job_type',
        'category',
        'floor_area',
        'prospect',
        'six_stars',
        'status',
        'six_stars_received_at',
        'six_stars_submitted_at'
    ];
    

    public function assigns(){
        return $this->hasMany(related: JobTimeHistory::class);
    }


}
