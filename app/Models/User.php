<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
        'department',
        'team'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function teams(){
        return $this->hasMany(related: Team::class);

       
    }

    public function permissions(){
        return $this->hasMany(related: Permission::class);
    }

    public function roles(){
        return $this->hasMany(related: Role::class);
    }

    public function hasRole($role)
    {
        foreach (Auth::user()->permissions as $permission) {
            # code...
            $role_name = \App\Models\Role::select('name')->where('id','=',$permission->role_id)->first()->name;
            if($role_name == $role){
                return true;
            }
        }

    }

    public function inRole($roles)
    {
        foreach ($roles as $role) {
           if(Self::hasRole($role)){
            return true;
           }
        }

    }
}
