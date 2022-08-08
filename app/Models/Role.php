<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function permissions(){
        return $this->hasMany(related: Permission::class);
    }

    public function users(){
        return $this->hasMany(related: User::class);
    }
    
    protected $fillable = [
		'id',
        'role_id',
		'name',
		'slug',
        'priority',
		'active',
	];
   

}
