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
    

        $unassigned_count = Self::countJobByStatus('Unassigned')->count;
        $ready_to_submit_count = Self::countJobByStatus('Ready To Submit')->count;;
        $submitted_count = DraftingMaster::select(DraftingMaster::raw('COUNT(status) as count'))->where('status','=','Submitted')
        ->where('submitted_by','=',Auth::user()->team)->first()->count;
        
        $latest_job = DraftingMaster::select('id','job_number','created_at')->orderBy('created_at','DESC')->limit(1)->first();
           
        $active_users = Self::getActiveUsers();
        $active_users_count = count(Self::getActiveUsers());
            
        $inactive_users = Self::getInactiveUsers();
        $inactive_users_count = count(Self::getInactiveUsers());

		  return view('dashboard.dashboard',compact(['unassigned_count','ready_to_submit_count'
          ,'submitted_count','latest_job'
        ,'active_users','active_users_count','inactive_users','inactive_users_count']));
    }

    public function getActiveUsers(){
        
         
            $active_users = User::select(User::raw('CONCAT(users.first_name, " ", users.last_name) AS full_name')
            ,'job_drafting_status.drafting_masters_id'
            ,'drafting_masters.job_number'
            ,'drafting_masters.type'
            ,'drafting_masters.job_type'
            )
            ->leftJoin('job_drafting_status','job_drafting_status.user_id','users.id')
            ->leftJoin('drafting_masters','job_drafting_status.drafting_masters_id','drafting_masters.id')
            ->where('users.team','=',Auth::user()->team)
            ->where('job_drafting_status.status','=',1)->get();

            return $active_users;
		
    }

    public function getInactiveUsers(){
      
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
           
           
            foreach ($active_user_arr as $key) {
                array_splice($all_users_arr, array_search($key, $all_users_arr ), 1);
            }

            return  $all_users_arr;
		
    }


    public function countJobByStatus($status){
         return DraftingMaster::select(DraftingMaster::raw('COUNT(status) as count'))->where('status','=',$status)
         ->whereIn('customer_name',function($query){
            $query->select('name')->from('customers')->where('team','=',Auth::user()->team);
        })->first();
        
    }



   
    
}
