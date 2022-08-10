<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;


use App\Models\Pivot;
use Illuminate\Support\Facades\Auth;
use App\Events\Message;
use App\Models\DraftingMaster;
use App\Models\Timesheet;
use Error;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		  return view('dashboard.dashboard');
    }

    public function getActiveUsers(Request $request){
        if ($request->ajax()) {
         
            $active_users = User::select(User::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name')
            ,'job_drafting_status.drafting_masters_id'
            ,'drafting_masters.job_number'
            ,'drafting_masters.type'
            )
            ->leftJoin('job_drafting_status','job_drafting_status.user_id','users.id')
            ->leftJoin('drafting_masters','job_drafting_status.drafting_masters_id','drafting_masters.id')
            ->where('users.team','=',Auth::user()->team)
            ->where('job_drafting_status.status','=',1)->get();

            return response()->json($active_users);
		}
    }

    public function getInactiveUsers(Request $request){
        if ($request->ajax()) {
        
            $active_user_arr = array();
            $all_users_arr = array();


            $active_users = User::select(User::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))
            ->leftJoin('job_drafting_status','job_drafting_status.user_id','users.id')
            ->where('users.team','=',Auth::user()->team)
            ->where('job_drafting_status.status','=',1)->get();

           

            $all_users = User::select(User::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name'))
            ->where('users.team','=',Auth::user()->team)
            ->get();

            foreach ($active_users as $key) {
                array_push($active_user_arr,$key->full_name);
            }
            foreach ($all_users as $key) {
                array_push($all_users_arr,$key->full_name);
            }
           
            $result = array_unique(array_merge($active_user_arr,$all_users_arr), SORT_REGULAR);

        
            foreach ($active_user_arr as $key) {
                array_splice($all_users_arr, array_search($key, $all_users_arr ), 1);
            }

            return  $all_users_arr;
		}
    }

    public function countUserJobs(Request $request){
       $countUserJobs = DraftingMaster::select(
       User::raw('CONCAT(users.first_name, " " ,users.last_name) as full_name')
       ,DraftingMaster::raw("SUM(CASE WHEN drafting_masters.status = 'Submitted' THEN 1 else 0 END) as count")
       ,Timesheet::raw("SUM(case when timesheets.user_id = job_time_histories.user_id then TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) else 0 end ) AS average"))
       ->leftJoin('job_time_histories','drafting_masters.id','job_time_histories.drafting_masters_id')
        ->leftJoin('timesheets','drafting_masters.id','timesheets.drafting_masters_id')
        ->leftJoin('users','users.id','job_time_histories.user_id')
        ->where('drafting_masters.status','=','Submitted')
        ->where('job_time_histories.type','=','DRAFTING')
        
        ->groupBy('job_time_histories.user_id')
        ->get();

        return response()->json($countUserJobs);

// //        SELECT  a.status, (SELECT first_name FROM users WHERE id = b.user_id) as user_id 
// , SUM(case when a.status = "Submitted"  AND b.type = "DRAFTING" AND b.user_id = c.user_id  then 1 else 0 end) as count
// , SUM(case when c.user_id = b.user_id then TIMESTAMPDIFF(SECOND, c.job_start, c.job_stop) else 0 end ) AS seconds FROM drafting_masters as a LEFT JOIN job_time_histories as b ON a.id = b.drafting_masters_id 
// LEFT JOIN timesheets as c ON a.id = c.drafting_masters_id
// AND b.type = "DRAFTING" AND a.status = "Submitted"
// GROUP BY b.user_id;
    }

    public function getFeeds(Request $request){
        
        if ($request->ajax()) {
            
            $feeds_arr = array();

            $unassigned_jobs = DraftingMaster::select(DraftingMaster::raw('COUNT(status) as unassigned_jobs'))->where('status','=','Unassigned')->first();
            $submitted_jobs = DraftingMaster::select(DraftingMaster::raw('COUNT(status) as submitted_jobs'))->where('status','=','Submitted')
            ->where('submitted_by','=',Auth::user()->team)->first();
            $ready_to_submit_jobs = DraftingMaster::select(DraftingMaster::raw('COUNT(status) as ready_to_submit_jobs'))->where('status','=','Ready To Submit')->first();
            $latest_job = DraftingMaster::select('customer_name as latest_job','created_at as latest_job_date')->orderBy('created_at','DESC')->limit(1)->first();
           
            array_push($feeds_arr,$unassigned_jobs->unassigned_jobs);
            array_push($feeds_arr,$ready_to_submit_jobs->ready_to_submit_jobs);
            array_push($feeds_arr,$submitted_jobs->submitted_jobs);
            array_push($feeds_arr,$latest_job->latest_job);
            array_push($feeds_arr,$latest_job->latest_job_date);
            
            return  $feeds_arr;
		}
    }

    public function getAverageDraftingHours(Request $request){
        error_log($request->input('from'));
        $total_seconds = 0;
        $submitted_jobs = DraftingMaster::select(DraftingMaster::raw('COUNT(status) as submitted_jobs'))
        ->where('status','=','Submitted')
            ->where('submitted_by','=',Auth::user()->team)->first();

       $drafting_hours = DraftingMaster::select(Timesheet::raw("TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) AS seconds"))
       ->leftJoin('timesheets','timesheets.drafting_masters_id','drafting_masters.id')
       ->where('drafting_masters.status','=','Submitted')
       ->where('drafting_masters.submitted_by','=',Auth::user()->team)
       ->where('timesheets.type','=','DRAFTING')->get();

        foreach ($drafting_hours as $key) {
            $total_seconds += $key->seconds;
            
        }

       return $submitted_jobs->submitted_jobs;

    }
    
}
