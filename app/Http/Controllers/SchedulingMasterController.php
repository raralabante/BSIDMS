<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchedulingMaster;
use App\Models\JobTimeHistory;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\JobSCHEDULINGStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\RoleActivity;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Events\Message;
class SchedulingMasterController extends Controller
{
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
		  return view('schedulingmaster.schedulingmaster');
    }

    protected function insert(Request $request)
    {
      
       $request->validate([
        'customer_name' => 'required|max:255|exists:customers,name',
        'job_number' => 'required|max:255',
        'client_name' => 'required|max:255',
        'address' => 'required|max:255',
        'type' => 'required|max:255|exists:App\Models\Type,name',
        'prestart' => 'required|max:255|exists:App\Models\Prestart,name',
        'stage' => 'required|max:255',
        'floor_area' => 'nullable|numeric',
        'brand' => 'nullable|exists:App\Models\Brand,name',
        'job_type' => 'nullable|exists:App\Models\JobType,name',
        'category' => 'nullable|exists:App\Models\Categories,name',
      ]);

    //   event(new Message(''));

      $status = "";

      if($request->hitlist == null){
        $request->hitlist = 0;
      }

      if(empty($request->scheduler)){
        $status = "Unassigned";
      }
      else{
        $status = "Assigned";
      }
      
        $newJob = SchedulingMaster::create([
            'customer_name' => $request->customer_name,
            'job_number' => strtoupper($request->job_number),
            'client_name' => $request->client_name,
            'address' => $request->address,
            'type' => $request->type,
            'prestart' => $request->prestart,
            'stage' => $request->stage,
            'brand' => $request->brand,
            'job_type' => $request->job_type,
            'category' => $request->category,
            'floor_area' => $request->floor_area,
            'prospect' => $request->prospect,
            'hitlist' => $request->hitlist,
            'status' => $status,
        ]);

        if(!empty($request->scheduler_label)){
            $description = "(SCHEDULING) Job# " . $request->job_number . " has been assigned to you.";
            $newJob->assigns()->save(new JobTimeHistory(['user_id' => $request->scheduler,'type' => 'SCHEDULING']));
            Self::addActivityById($description,$request->scheduler,20); //20=DRAFTER
          }

        return redirect()->back()->with('success', 'Client Job# ' . $request->job_number . ' has been added.');
    }

    public function SchedulingMasterList(Request $request) {
      if ($request->ajax()) {
        $query = "";
        if(Auth::user()->hasRole('Administrator')){
          $query = SchedulingMaster::select(
            'id',
            'customer_name',
            'client_name',
            'job_number',
            'address',
            'type',
            'prestart',
            'stage',
            'brand',
            'job_type',
            'category',
            'floor_area',
            'prospect',
            'status',
            SchedulingMaster::raw("(CASE WHEN hitlist='0' THEN 'No' ELSE 'Yes' END) as hitlist"),
            'created_at'
              )
              ->where('status','!=','Submitted')
              ->where('status','!=','Cancelled');
                    }
        else{
          $query = SchedulingMaster::select(
            'id',
            'customer_name',
            'client_name',
            'job_number',
            'address',
            'type',
            'prestart',
            'stage',
            'brand',
            'job_type',
            'category',
            'floor_area',
            'prospect',
            'status',
            SchedulingMaster::raw("(CASE WHEN hitlist='0' THEN 'No' ELSE 'Yes' END) as hitlist"),
            'created_at'
              )
              ->whereIn('customer_name',function($query){
                $query->select('name')->from('customers')->where('team','=',Auth::user()->team);
            })
              ->where('status','!=','Submitted')
              ->where('status','!=','Cancelled');
        }

        return datatables()->eloquent($query)
            ->editColumn('scheduler', function (SchedulingMaster $schedulingmaster) {
             
              $jobtimehistory = JobTimeHistory::select(
                'user_id')
                ->where('scheduling_masters_id', '=', $schedulingmaster->id)
                ->where('type', '=', 'SCHEDULING')->first();

                
          
                if(empty($jobtimehistory)){
                  return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_scheduler ' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#assign_scheduler_modal' ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                }
                else{
                  $scheduler = Self::convertIDToFullname($jobtimehistory->user_id);
                  if($schedulingmaster->status == "Assigned"){
                    return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_drafter' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#edit_drafter_modal'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $scheduler->full_name . "</button>";
                  }
                  else{
                    return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_drafter' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#edit_drafter_modal' disabled><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $scheduler->full_name . "</button>";
                  }
                }
                  
                    
                
              })
            
              ->editColumn('scheduling_hours', function (SchedulingMaster $schedulingmaster) {
                  return Self::getTypeHours($schedulingmaster,'SCHEDULING') ?? "N/A";
                })
                ->editColumn('schedule_checker', function (SchedulingMaster $schedulingmaster) {
    
                  $jobtimehistory = JobTimeHistory::select(
                    'user_id')
                    ->where('scheduling_masters_id', '=', $schedulingmaster->id)
                    ->where('type', '=', 'SCHEDULE CHECKING')->first();
    
                 

                    if(empty($jobtimehistory)){
                      if($schedulingmaster->status == "Ready For Check"){
                        return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_checker ' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#assign_checker_modal' ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                      }
                      else{
                        return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_checker ' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#assign_checker_modal' disabled ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                      }
                    }
                    else{
                      $scheduler = Self::convertIDToFullname($jobtimehistory->user_id);
                      if($schedulingmaster->status == "Ready To Submit" || $schedulingmaster->status == "Ready For Six Stars" || $schedulingmaster->status == "In Six Stars"){
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_checker' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#edit_checker_modal' disabled><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $scheduler->full_name . "</button>";
                      }
                      else{
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_checker' data-id='".$schedulingmaster->id."' data-job_number='".$schedulingmaster->job_number."' data-toggle='modal' data-target='#edit_checker_modal'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $scheduler->full_name . "</button>";
                      }
                       
                    }
                  })
                ->editColumn('checking_hours', function (SchedulingMaster $schedulingmaster) {
                    return Self::getTypeHours($schedulingmaster,'SCHEDULE CHECKING') ?? "N/A";
                  })
                ->editColumn('status', function (SchedulingMaster $schedulingmaster) {
                 
                      return $schedulingmaster->status;
                  })
                  ->editColumn('total_hours', function (SchedulingMaster $schedulingmaster) {

                    $total_scheduling_hours = Self::getTypeHours($schedulingmaster,'SCHEDULING');
                    $total_checking_hours = Self::getTypeHours($schedulingmaster,'SCHEDULE CHECKING');
                  
                    return ($total_scheduling_hours + $total_checking_hours ) ?? "N/A";
    
                    })
                ->editColumn('edit_job', function (SchedulingMaster $schedulingmaster) {
                  return '<button type="button" class="btn btn-light border border-dark  edit_job" data-id="'.$schedulingmaster->id.'" data-job_number="'.$schedulingmaster->job_number.'" data-toggle="modal" data-target="#edit_job_modal">
                  <i class="fa-solid fa-pen"></i>
                </button>';
                })
                ->editColumn('cancel_job', function (SchedulingMaster $schedulingmaster) {
                    return '<button class="btn btn-light border border-dark cancel_job" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '"><i class="fa-solid fa-ban"></i></button>';
                })
                ->editColumn('submit_job', function (SchedulingMaster $schedulingmaster) {

                  if($schedulingmaster->status == "Ready To Submit"){
                    return '<button class="btn btn-success submit_job" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '" ><i class="fa-solid fa-paper-plane"></i></button>';
                  }
                  else{
                    return '<button class="btn btn-success submit_job" data-id="'.$schedulingmaster->id.'" data-job_number="' . $schedulingmaster->job_number . '" disabled><i class="fa-solid fa-paper-plane"></i></button>';
                  }
                  
                })
                ->editColumn('job_number', function (SchedulingMaster $schedulingmaster) {
                  
                  return '<a role="button" class="btn btn-dark-green text-white " href="' . route('timesheets.scheduling', $schedulingmaster->id) .'">'. $schedulingmaster->job_number.'</a>'; 
                  
                })
                ->rawColumns(['scheduler','scheduling_hours','schedule_checker','checking_hours','checking_hours','status','total_hours','edit_job','submit_job','job_number','cancel_job'])
                ->toJson();
      }
    }

    protected function fetch(Request $request)
    {
      return SchedulingMaster::where('id','=',$request->id)->first();
    }

    public function convertIDToFullname($id){
      return User::select(
        User::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
        ->where('id', '=', $id)->first();
    }

    protected function edit(Request $request)
    {

      $request->validate([
        'edit_customer_name' => 'required|max:255|exists:customers,name',
        'edit_client_name' => 'required|max:255',
        'edit_address' => 'required|max:255',
        'edit_type' => 'required|max:255|exists:App\Models\Type,name',
        'edit_prestart' => 'required|max:255|exists:App\Models\Prestart,name',
        'edit_stage' => 'required|max:255',
        'edit_floor_area' => 'nullable|numeric',
        'edit_brand' => 'nullable|exists:App\Models\Brand,name',
        'edit_job_type' => 'nullable|exists:App\Models\JobType,name',
        'edit_category' => 'nullable|exists:App\Models\Categories,name',
      ]);
      
    

     
      if($request->edit_hitlist == null){
        $request->edit_hitlist = 0;
      }

      $edit_job = SchedulingMaster::where('id','=',$request->edit_schedule_id)->get()->first();


      // $checkers = JobTimeHistory::select('job_time_histories.type')
      // ->leftJoin('drafting_masters','drafting_masters.id','job_time_histories.drafting_masters_id')
      // ->where('job_time_histories.drafting_masters_id','=', $request->edit_schedule_id)
      // ->where('job_time_histories.type','=','CHECKING')->first();
   
      // if(!empty($checkers)){
      //   if($edit_job->status == "Ready To Submit" OR $edit_job->status == "Ready For Six Stars" OR $edit_job->status == "In Six Stars"){
      //     throw ValidationException::withMessages(['edit_hitlist' => 'You cannot edit six stars after checking.']);
      //   }
       
      // }

      $edit_job->customer_name = $request->edit_customer_name;
      $edit_job->client_name = $request->edit_client_name;
      $edit_job->address = $request->edit_address;
      $edit_job->type = $request->edit_type;
      $edit_job->stage = $request->edit_stage;
      $edit_job->prestart = $request->edit_prestart;
      $edit_job->brand = $request->edit_brand;
      $edit_job->job_type = $request->edit_job_type;
      $edit_job->category = $request->edit_category;
      $edit_job->floor_area = $request->edit_floor_area;
      $edit_job->prospect = $request->edit_prospect;
      $edit_job->hitlist = $request->edit_hitlist;

      $edit_job->save();
      event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' has been updated.');
    }

    public function getTypeHours($schedulingmaster, $type){
      $total_time = 0;
        $time_diff = Timesheet::select(Timesheet::raw('TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) AS difference '))
        ->leftJoin('job_drafting_status','timesheets.scheduling_masters_id','job_drafting_status.scheduling_masters_id')
        ->where('timesheets.type', '=' , $type)
        ->where('job_drafting_status.status', '=' , '0')
        ->where('timesheets.scheduling_masters_id', '=' , $schedulingmaster->id)
        ->whereNotNull('timesheets.job_stop')
        ->groupBy('timesheets.id')->get();
        
        foreach($time_diff as $data){
          $total_time += $data->difference;
        }

        $active_job = Timesheet::select('timesheets.job_start', Timesheet::raw('SUM(TIMESTAMPDIFF(SECOND, timesheets.job_start, now())) AS difference '))
        ->leftJoin('job_drafting_status','timesheets.scheduling_masters_id','job_drafting_status.scheduling_masters_id')
        // ->where('job_drafting_status_status.user_id', '=', Auth::user()->id)
        ->where('timesheets.type', '=' , $type)
        ->where('job_drafting_status.status', '=' , '1')
        ->where('timesheets.scheduling_masters_id', '=' , $schedulingmaster->id)
        ->whereNull('timesheets.job_stop')
        ->first();

        return $total_time + $active_job->difference;
        
}

    public function addActivityById($description,$user_id,$target_role){

        $activity = Activity::create(
          array(
            'user_id' => Auth::user()->id,
            'department' => Auth::user()->department,
            'team' => Auth::user()->team,
            'description' => $description,
            'status' => 0,
          )
        );
        $activity->role_activities()->save(new RoleActivity(['role'=> $target_role, 'user_id' => $user_id]));
       }
    
       public function addActivity($description,$target_role){
    
        $activity = Activity::create(
          array(
            'user_id' => Auth::user()->id,
            'department' => Auth::user()->department,
            'team' => Auth::user()->team,
            'description' => $description,
            'status' => 0,
          )
        );
    
        $activity->role_activities()->save(new RoleActivity(['role'=> $target_role]));
       }
    
    }

