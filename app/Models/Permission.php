<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'role_user';
    protected $guarded = [];
    protected $fillable = [
		'user_id', 'role_id'
	];

    public function roles(){
        return $this->hasMany(related: Role::class);
    }

    public function users(){
        return $this->belongsToMany(related: User::class);
    }

}
