<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';
    protected $fillable = [
        'user_id', 
        'department',
        'team',
        'activity',
        'description',
        'status',
        'created_at',
    ];

    public function role_activities(){
        return $this->hasOne(RoleActivity::class);
    }


    
}
