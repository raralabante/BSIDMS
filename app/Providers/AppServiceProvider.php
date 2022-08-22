<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Activity;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Customer;
use App\Models\JobType;
use App\Models\Pivot;
use App\Models\Type;
use App\Models\User;
use App\Models\DraftingMaster;
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

        View::composer(['auth.register','user.user','customer.customer','report.multifilters','report.usertimesheets'],function($view){
            $teams = Pivot::select(
                'id',
                'code_value',
                'desc1')
                ->where('code_name','=','TEAM')
                ->orderBy('code_value', 'ASC')->get();
                View::share('teams', $teams);
        });

        View::composer('report.multifilters',function($view){
            $customers = Customer::select(
                'name'
                ,'team')
                ->orderBy('name', 'ASC')->get();
            
            $types = Type::select(
                'name')
                ->orderBy('name', 'ASC')->get();

            $brands = Brand::select(
                'name')
                ->orderBy('name', 'ASC')->get();

            $job_types = JobType::select(
                'name')
                ->orderBy('name', 'ASC')->get();
            
            $categories = Categories::select(
                'name')
                ->orderBy('name', 'ASC')->get();

                View::share('categories', $categories);
                View::share('job_types', $job_types);
                View::share('brands', $brands);
                View::share('customers', $customers);
                View::share('types', $types);
        });


        View::composer('schedulingmaster.schedulingmaster',function($view){
            $scheduling_checkers = User::select(
                'users.id as value', 
                User::raw('CONCAT(users.first_name, " ", users.last_name) AS label'))
                ->leftJoin('role_user','role_user.user_id','users.id')
                ->where('role_user.role_id','=',12)
                ->where('users.department','=',Auth::user()->department)
                ->where('users.team','=',Auth::user()->team)
                ->orderBy('users.first_name', 'ASC')->get();
            
            $schedulers = User::select(
                'users.id as value', 
                User::raw('CONCAT(users.first_name, " ", users.last_name) AS label'))
                ->leftJoin('role_user','role_user.user_id','users.id')
                ->where('role_user.role_id','=',20)
                ->where('users.department','=',Auth::user()->department)
                ->where('users.team','=',Auth::user()->team)
                ->orderBy('users.first_name', 'ASC')->get();
                
                View::share('schedulers', $schedulers);
                View::share('scheduling_checkers', $scheduling_checkers);

        });
      

       View::composer(
        '*'
        , function ($view) {
            if (Auth::check()) {
                
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
            
            $mydrafts = DraftingMaster::select(
                DraftingMaster::raw('COUNT(drafting_masters.id) as count'),
                )->leftJoin('job_time_histories', 'drafting_masters.id', '=', 'job_time_histories.drafting_masters_id')
                ->where('job_time_histories.user_id','=', Auth::user()->id)
                ->where('job_time_histories.type','=', 'DRAFTING')
                ->where('drafting_masters.status','=', 'Assigned')->first();
                View::share('mydrafts_count',$mydrafts->count);
    
            $mydraftscheck = DraftingMaster::select(
                DraftingMaster::raw('COUNT(drafting_masters.id) as count'),
                )->leftJoin('job_time_histories', 'drafting_masters.id', '=', 'job_time_histories.drafting_masters_id')
                ->where('job_time_histories.user_id','=', Auth::user()->id)
                ->where('job_time_histories.type','=', 'CHECKING')
                ->where('drafting_masters.status','=', 'Ready For Check')->first();
        
                View::share('mydraftscheck_count',$mydraftscheck->count);

            $six_stars = DraftingMaster::select(
                DraftingMaster::raw('COUNT(id) as count'))
                ->where('status','=', 'In Six Stars')
                ->where('six_stars','=','1')->first();
        
                View::share('six_stars_count',$six_stars->count);
                


        }

       
        });
        


    }
}
