<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ammends extends Model
{
    use HasFactory;
    protected $fillable = [
        'drafting_masters_id', 
    ];
}
