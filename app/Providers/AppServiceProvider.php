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

        View::composer('auth.register',function($view){
            $departments = Pivot::select(
                'code_value',
                'desc1')
                ->where('code_name','=','DEPARTMENT')
                ->orderBy('code_value', 'ASC')->get();
                View::share('departments', $departments);
        });

      

       View::composer(
        '*'
        , function ($view) {
            if (Auth::check()) {
            //     $activities_by_id = Activity::select('activities.description','activities.created_at','activities.status')
            //     ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            //     ->where('activities.department','=', Auth::user()->department)
            //     ->where('activities.team','=', Auth::user()->team)
            //     ->where('role_activities.user_id','=',Auth::user()->id)
            //     ->whereIn('role_activities.role',function($query){
            //         $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            //     })
            //     ->orderBy('created_at','DESC')
            //     ;

            // $activities = Activity::select('activities.description','activities.created_at','activities.status')
            //     ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            //     ->where('activities.department','=', Auth::user()->department)
            //     ->where('activities.team','=', Auth::user()->team)
            //     ->where('activities.user_id' , '!=', Auth::user()->id)
                
            //     ->whereNull('role_activities.user_id')
            //     ->whereIn('role_activities.role',function($query){
            //         $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            //     })
            //     ->union($activities_by_id)
            //     ->orderBy('created_at','DESC')
            //     ->take(10)
            //     ->get();
                
                
            $activities_by_id_count = Activity::select('activities.description','activities.created_at','activities.status')
            ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            ->where('activities.department','=', Auth::user()->department)
            ->where('activities.team','=', Auth::user()->team)
            ->where('role_activities.user_id','=',Auth::user()->id)
            ->where('activities.status','=', 0)
            ->whereIn('role_activities.role',function($query){
                $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            })
            ->orderBy('created_at','DESC')
            ;

            $activities_global_count = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.user_id','=',0)
                ->where('activities.status','=', 0)
                ->orderBy('created_at','DESC')
                ;

            $activities_count = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('activities.user_id' , '!=', Auth::user()->id)
                ->where('activities.status','=', 0)
                ->whereNull('role_activities.user_id')
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->union($activities_by_id_count)->union($activities_global_count)
                ->orderBy('created_at','DESC')
                ->get();


            $notification_count = count($activities_count);
            View::share('notification_count', $notification_count);
            // View::share('activities', $activities);
            
        }
      
    });

    
    }
}
