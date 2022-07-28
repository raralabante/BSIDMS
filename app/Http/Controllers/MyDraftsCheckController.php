<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\DraftingMaster;
use App\Models\JobDraftingStatus;
use App\Models\JobTimeHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;
use Carbon\Carbon;
use Error;
use App\Events\Message;
class MyDraftsCheckController extends Controller
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
		  return view('mydraftscheck.mydraftscheck');
    }

    public function jobStopper(){
      Timesheet::
      leftJoin('job_drafting_status', 'timesheets.drafting_masters_id', '=', 'job_drafting_status.drafting_masters_id')
      ->where('timesheets.user_id','=', Auth::user()->id)
      ->where('job_drafting_status.status','=','1')
      ->where('job_drafting_status.type','=','CHECKING')
      ->whereNull('timesheets.job_stop')
      ->update(['timesheets.job_stop' => now()]);

      JobDraftingStatus::
      where('user_id', Auth::user()->id)
      ->where('type','=','CHECKING')
      ->where('status','=','1')
      ->update(['status' => 0]);

    }
    public function myDraftsCheckList(Request $request) {
     
      if ($request->ajax()) {
        $query = DraftingMaster::select(
                  'drafting_masters.id',
                  'drafting_masters.customer_name',
                  'drafting_masters.job_number',
                  'drafting_masters.client_name',
                  'drafting_masters.address',
                  'drafting_masters.type',
                  'drafting_masters.ETA',
                  'drafting_masters.brand',
                  'drafting_masters.job_type',
                  'drafting_masters.category',
                  'drafting_masters.floor_area',
                  'drafting_masters.prospect',
                  DraftingMaster::raw("(CASE WHEN drafting_masters.six_stars='0' THEN 'No' ELSE 'Yes' END) as six_stars"),
                  'drafting_masters.created_at'

        )->leftJoin('job_time_histories', 'drafting_masters.id', '=', 'job_time_histories.drafting_masters_id')
        ->where('job_time_histories.user_id','=', Auth::user()->id)
        ->where('job_time_histories.type','=', 'CHECKING')
        ->where('drafting_masters.status','=', 'Ready For Check');

        return datatables()->eloquent($query)
          ->editColumn('active', function (DraftingMaster $draftingmaster) {
            $drafting_status = JobDraftingStatus::select('type','status')
            ->where('user_id', '=', Auth::user()->id)
            ->where('drafting_masters_id', '=' , $draftingmaster->id)
            ->where('type', '=' , 'CHECKING')->latest('id')->first();

            if (!empty($drafting_status) AND $drafting_status->status == 1) {
              return '<div class="form-switch">
              <input class="form-check-input active" type="checkbox" role="switch" data-id="' . $draftingmaster->id . '" data-job_number="' . $draftingmaster->job_number . '" checked>
            </div>';
             }
             else{
              return '<div class="form-switch">
              <input class="form-check-input active" type="checkbox" role="switch" data-id="' . $draftingmaster->id . '"  data-job_number="' . $draftingmaster->job_number . '" >
            </div>';
             }
            })
            ->editColumn('checking_hours', function (DraftingMaster $draftingmaster) {

              $total_time = 0;
              $time_diff = Timesheet::select(Timesheet::raw('TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) AS difference '))
              ->leftJoin('job_drafting_status','timesheets.drafting_masters_id','job_drafting_status.drafting_masters_id')
              ->where('timesheets.user_id', '=', Auth::user()->id)
              ->where('timesheets.type', '=' , 'CHECKING')
              ->where('job_drafting_status.status', '=' , '0')
              ->where('timesheets.drafting_masters_id', '=' , $draftingmaster->id)
              ->whereNotNull('timesheets.job_stop')
              ->groupBy('timesheets.id')->get();
              
              foreach($time_diff as $data){
                $total_time += $data->difference;
              }

              $active_job = Timesheet::select('timesheets.job_start', Timesheet::raw('SUM(TIMESTAMPDIFF(SECOND, timesheets.job_start, now())) AS difference '))
              ->leftJoin('job_drafting_status','timesheets.drafting_masters_id','job_drafting_status.drafting_masters_id')
              ->where('job_drafting_status.user_id', '=', Auth::user()->id)
              ->where('timesheets.type', '=' , 'CHECKING')
              ->where('job_drafting_status.status', '=' , '1')
              ->where('timesheets.drafting_masters_id', '=' , $draftingmaster->id)
              ->whereNull('timesheets.job_stop')
              ->first();

              return ($total_time + $active_job->difference ) ?? "N/A";
              })
              ->editColumn('reject', function (DraftingMaster $draftingmaster) {
                return '<button class="btn btn-danger reject" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" >Reject</button>';
              })
              ->editColumn('submit', function (DraftingMaster $draftingmaster) {

                $timesheet = Timesheet::select('timesheets.id')->leftJoin('drafting_masters','drafting_masters.id','timesheets.drafting_masters_id')
                ->where('drafting_masters.id','=',$draftingmaster->id)
                ->where('timesheets.type','=','CHECKING')->first();

                if(empty($timesheet)){
                  return '<button class="btn btn-success submit" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" data-six_stars="' . $draftingmaster->six_stars . '" disabled>Submit</button>';
                }else{
                  return '<button class="btn btn-success submit" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" data-six_stars="' . $draftingmaster->six_stars . '" >Submit</button>';
                }

               
              })
            ->rawColumns(['active','checking_hours','submit','reject'])
            ->toJson();
      }
    }

    public function setStatusOnOff(Request $request) {
      $active_status = 0;

      //CHECK IF USER IS ACTIVE ON CHECKING
      $checking_user_status = JobDraftingStatus::select('status')
      ->where('user_id','=',Auth::user()->id)
      ->where('type','=','DRAFTING')
      ->where('status','=','1')->first();
      
      if(!empty($checking_user_status)){
        $active_status = 3;
      }
      else{

      // CHECK LATEST STATUS IF ON OR OFF
      $drafting_status = JobDraftingStatus::select('status')
      ->where('user_id','=',Auth::user()->id)
      ->where('type','=','CHECKING')
      ->where('drafting_masters_id','=',$request->id)
      ->latest()->first();

      //IF DATA TABLE IS EMPTY
      if(empty($drafting_status)){
        error_log("DATA TABLE IS EMPTY ");
        // JobDraftingStatus::
        //   where('user_id', Auth::user()->id)
        //   ->where('status','=','1')
        //   ->update(['status' => 0]);

        Self::jobStopper();
        
        JobDraftingStatus::create([
          'user_id' => Auth::user()->id,
          'drafting_masters_id' => $request->id,
          'status' => '1',
          'type' => 'CHECKING',
        ]);

        Timesheet::create([
          'drafting_masters_id' =>  $request->id,
          'user_id' => Auth::user()->id,
          'type' => 'CHECKING',
          'job_start' => now(),
        ]);

        $active_status = 1;
      }
      else{
        // IF TOGGLE STATUS IS ACTIVE THEN SET TO INACTIVE
        if($drafting_status->status == 1){

          error_log("SET TOGGLE TO INACTIVE");

          Self::jobStopper();

          $active_status = 0;
        }
        // IF TOGGLE IS INACTIVE, SET ALL STATUS TO INACTIVE THEN INSERT ACTIVE STATUS
       else{
        error_log("SET ALL TOGGLE TO INACTIVE THEN ACTIVATE");

         Timesheet::
          leftJoin('job_drafting_status', 'timesheets.drafting_masters_id', '=', 'job_drafting_status.drafting_masters_id')
          ->where('job_drafting_status.type','=', 'CHECKING')
          ->where('timesheets.user_id','=', Auth::user()->id)
          ->whereNull('timesheets.job_stop')
          ->update(['timesheets.job_stop' => now()]);

        JobDraftingStatus::
          where('user_id', Auth::user()->id)
          ->where('type','=', 'CHECKING')
          ->where('status','=','1')
          ->update(['status' => 0]);

         
          JobDraftingStatus::create([
            'user_id' => Auth::user()->id,
            'drafting_masters_id' => $request->id,
            'status' => '1',
            'type' => 'CHECKING',
          ]);

          Timesheet::create([
            'drafting_masters_id' =>  $request->id,
            'user_id' => Auth::user()->id,
            'type' => 'CHECKING',
            'job_start' => now(),
          ]);
          
          $active_status = 1;
       }
      }
      }
      return $active_status;
    }


    public function setJobStatus(Request $request) {
      $draft = DraftingMaster::findOrFail($request->id);
      
      if($draft->six_stars == 1){
        $draft->status = 'Ready For Six Stars';
        $description = "Job# " . $draft->job_number . " is now ready for Six Stars.";
      }else{
        $draft->status = 'Ready To Submit';
        $description = "Job# " . $draft->job_number . " is now ready to submit.";
      }

        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );

      $draft->save();
        Self::jobStopper();
        event(new Message(''));
    }
    
    public function rejectCheck(Request $request) {
      $draft = DraftingMaster::findOrFail($request->id);
      $draft->status = 'Unassigned';
      $draft->save();
      Self::jobStopper();
      JobTimeHistory::where('drafting_masters_id','=',$request->id)->delete();
      
      $description = "Job# " . $draft->job_number . " has been rejected.";
      app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );
        event(new Message(''));
  }

}
