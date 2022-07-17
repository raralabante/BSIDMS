<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    use HasFactory;
    
    protected $fillable = [
		'code_name', 
        'code_value',
        'desc1',
        'desc2',
        'desc3'
	];
}
