<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SchedulingMaster;
use App\Models\JobDraftingStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use Carbon\Carbon;
use Error;
use App\Events\Message;
class MySchedulesController extends Controller
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
		  return view('myschedules.myschedules');
    }

    public function jobStopper(){
      error_log("JOB STOPPED");
      Timesheet::
      leftJoin('job_drafting_status', 'timesheets.scheduling_masters_id', '=', 'job_drafting_status.scheduling_masters_id')
      ->where('timesheets.user_id','=', Auth::user()->id)
      ->where('job_drafting_status.status','=','1')
      ->where('job_drafting_status.type','=','SCHEDULING')
      ->whereNull('timesheets.job_stop')
      ->update(['timesheets.job_stop' => now()]);

      JobDraftingStatus::
      where('user_id', Auth::user()->id)
      ->where('type','=','SCHEDULING')
      ->where('status','=','1')
      ->update(['status' => 0]);

    }
    public function mySchedulesList(Request $request) {
     
      if ($request->ajax()) {
        $query = SchedulingMaster::select(
                  'scheduling_masters.id',
                  'scheduling_masters.customer_name',
                  'scheduling_masters.job_number',
                  'scheduling_masters.client_name',
                  'scheduling_masters.address',
                  'scheduling_masters.type',
                  'scheduling_masters.prestart',
                  'scheduling_masters.stage',
                  'scheduling_masters.brand',
                  'scheduling_masters.job_type',
                  'scheduling_masters.category',
                  'scheduling_masters.floor_area',
                  'scheduling_masters.prospect',
                  SchedulingMaster::raw("(CASE WHEN scheduling_masters.hitlist='0' THEN 'No' ELSE 'Yes' END) as hitlist"),
                  'scheduling_masters.created_at'

        )->leftJoin('job_time_histories', 'scheduling_masters.id', '=', 'job_time_histories.scheduling_masters_id')
        ->where('job_time_histories.user_id','=', Auth::user()->id)
        ->where('job_time_histories.type','=', 'SCHEDULING')
        ->where('scheduling_masters.status','=', 'Assigned')
        ->groupBy('scheduling_masters.id');
        return datatables()->eloquent($query)
          ->editColumn('active', function (SchedulingMaster $schedulingmaster) {
            $scheduling_status = JobDraftingStatus::select('type','status')
            ->where('user_id', '=', Auth::user()->id)
            ->where('scheduling_masters_id', '=' , $schedulingmaster->id)
            ->where('type', '=' , 'SCHEDULING')->latest('id')->first();

            if (!empty($scheduling_status) AND $scheduling_status->status == 1) {
              return '<div class="form-switch">
              <input class="form-check-input active" type="checkbox" role="switch" data-id="' . $schedulingmaster->id . '" data-job_number="' . $schedulingmaster->job_number . '" checked>
            </div>';
             }
             else{
              return '<div class="form-switch">
              <input class="form-check-input active" type="checkbox" role="switch" data-id="' . $schedulingmaster->id . '"  data-job_number="' . $schedulingmaster->job_number . '" >
            </div>';
             }
            })
            ->editColumn('scheduling_hours', function (SchedulingMaster $schedulingmaster) {
              $difference = 0;
              $total_time = 0;
              $time_diff = Timesheet::select(Timesheet::raw('TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) AS difference '))
              ->leftJoin('job_drafting_status','timesheets.scheduling_masters_id','job_drafting_status.scheduling_masters_id')
              ->where('timesheets.user_id', '=', Auth::user()->id)
              ->where('job_drafting_status.type', '=' , 'SCHEDULING')
              ->where('job_drafting_status.status', '=' , '0')
              ->where('timesheets.scheduling_masters_id', '=' , $schedulingmaster->id)
              ->whereNotNull('timesheets.job_stop')
              ->groupBy('timesheets.id')->get();
              
              foreach($time_diff as $data){
                $total_time += $data->difference;
              }

              $active_job = Timesheet::select('timesheets.job_start', Timesheet::raw(' COALESCE(SUM(TIMESTAMPDIFF(SECOND, timesheets.job_start, now())),0) AS difference '))
              ->leftJoin('job_drafting_status','timesheets.scheduling_masters_id','job_drafting_status.scheduling_masters_id')
              ->where('timesheets.user_id', '=', Auth::user()->id)
              ->where('timesheets.scheduling_masters_id', '=' , $schedulingmaster->id)
              ->whereNull('timesheets.job_stop')
              ->where('job_drafting_status.type', '=' , 'SCHEDULING')
              ->where('job_drafting_status.status', '=' , '1')
              ->groupBy('job_drafting_status.id')->first();
              
              if(empty($active_job)){
                $difference = 0;
              }
              else{
                $difference = $active_job->difference;
              }
              
              return ($total_time + $difference ) ?? "N/A";
              })
              ->editColumn('for_checking', function (SchedulingMaster $schedulingmaster) {

                $job_drafting_status = JobDraftingStatus::select('type')
                ->where('type','=','SCHEDULING')
                ->where('status','=',1)
                ->where('scheduling_masters_id','=',$schedulingmaster->id)->first();

                
                $timesheet = Timesheet::select('timesheets.id')->leftJoin('scheduling_masters','scheduling_masters.id','timesheets.scheduling_masters_id')
                ->where('scheduling_masters.id','=',$schedulingmaster->id)
                ->where('timesheets.type','=','SCHEDULING')->first();
                
                if(empty($timesheet)){
                  error_log("1");
                  return '<button class="btn btn-success for_checking" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '" disabled><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submit</button>';
                }
                else{
                  if(empty($job_drafting_status)){
                    error_log("2");
                    return '<button class="btn btn-success for_checking" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '" ><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submit</button>';
                  }
                  else{
                    error_log("3");
                    return '<button class="btn btn-success for_checking" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '" disabled><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submit</button>';
                  }
                }
               
                
              })
            ->rawColumns(['active','scheduling_hours','for_checking'])
            ->toJson();
      }
    }

    public function setStatusOnOff(Request $request) {

      $active_status = 0;

      //CHECK IF USE IS ACTIVE ON CHECKING
      $checking_user_status = JobDraftingStatus::select('status')
      ->where('user_id','=',Auth::user()->id)
      ->where('type','=','SCHEDULE CHECKING')
      ->where('status','=','1')->first();
      
      if(!empty($checking_user_status)){
        $active_status = 3;
      }
      else{
        // CHECK LATEST STATUS IF ON OR OFF
      $scheduling_status = JobDraftingStatus::select('status')
      ->where('user_id','=',Auth::user()->id)
      ->where('type','=','SCHEDULING')
      ->where('scheduling_masters_id','=',$request->id)
      ->latest()->first();

      //IF QUERY IS EMPTY
      if(empty($scheduling_status)){
        error_log("QUERY IS EMPTY ");

        Self::jobStopper();
        
        JobDraftingStatus::create([
          'user_id' => Auth::user()->id,
          'scheduling_masters_id' => $request->id,
          'status' => '1',
          'type' => 'SCHEDULING',
        ]);

        Timesheet::create([
          'scheduling_masters_id' =>  $request->id,
          'user_id' => Auth::user()->id,
          'type' => 'SCHEDULING',
          'job_start' => now(),
        ]);

        $active_status = 1;
      }
      else{
        // IF TOGGLE STATUS IS ACTIVE THEN SET TO INACTIVE
        if($scheduling_status->status == 1){

          error_log("SET TOGGLE TO INACTIVE");

          Self::jobStopper();

          $active_status = 0;
        }
        // IF TOGGLE IS INACTIVE, SET ALL STATUS TO INACTIVE THEN INSERT ACTIVE STATUS
       else{
        error_log("SET ALL TOGGLE TO INACTIVE THEN ACTIVATE");

         Timesheet::
          leftJoin('job_drafting_status', 'timesheets.scheduling_masters_id', '=', 'job_drafting_status.scheduling_masters_id')
          ->where('job_drafting_status.type','=', 'SCHEDULING')
          ->where('timesheets.user_id','=', Auth::user()->id)
          ->whereNull('timesheets.job_stop')
          ->update(['timesheets.job_stop' => now()]);

        JobDraftingStatus::
          where('user_id', Auth::user()->id)
          ->where('type','=', 'SCHEDULING')
          ->where('status','=','1')
          ->update(['status' => 0]);

          JobDraftingStatus::create([
            'user_id' => Auth::user()->id,
            'scheduling_masters_id' => $request->id,
            'status' => '1',
            'type' => 'SCHEDULING',
          ]);

          Timesheet::create([
            'scheduling_masters_id' =>  $request->id,
            'user_id' => Auth::user()->id,
            'type' => 'SCHEDULING',
            'job_start' => now(),
          ]);
          
          $active_status = 1;
       }
      }
      }
      
      return $active_status;
    }


    public function setJobStatus(Request $request) {

      $job_drafting_status = JobDraftingStatus::select('type')
      ->where('type','=','SCHEDULING')
      ->where('status','=',1)
      ->where('scheduling_masters_id','=',$request->id)->first();

      
      if(empty($job_drafting_status)){
        $draft = SchedulingMaster::findOrFail($request->id);
        $draft->status = 'Ready For Check';
        $draft->save();

        $description = "Job# " . $draft->job_number . " is now ready for checking.";

        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );
        event(new Message(''));
        // Self::jobStopper();
      }
 
    }
}
