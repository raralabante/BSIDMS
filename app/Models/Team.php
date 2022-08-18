<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  protected $table = 'user_teams';
    use HasFactory;

    protected $fillable = [
		'user_id',
        'team',
	];



}
