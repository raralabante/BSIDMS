<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Activity;

use App\Models\Pivot;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
       
       
       View::composer(
        '*'
        , function ($view) {
            if (Auth::check()) {
                $activities_by_id = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('role_activities.user_id','=',Auth::user()->id)
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->orderBy('created_at','DESC')
                ;

            $activities = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('activities.user_id' , '!=', Auth::user()->id)
                
                ->whereNull('role_activities.user_id')
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->union($activities_by_id)
                ->orderBy('created_at','DESC')
                
                ->get();
                
            
            View::share('activities', $activities);
        }
      
    });

    
    }
}
