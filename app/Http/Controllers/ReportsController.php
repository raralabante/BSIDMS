<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DraftingMaster;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Auth;
use App\Models\JobTimeHistory;
use App\Models\User;
use App\Models\ShiftingSchedule;

class ReportsController extends Controller
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
    public function index_multifilters()
    {
		  return view('report.multifilters');
    }

    public function index_usertimesheets($department = null,$team = null, $userid = null)
    {
		  return view('report.usertimesheets',compact('department'));
    }

    public function multifiltersGenerate(Request $request){
      error_log($request->department);
        if($request->department == "DFT"){
            return Self::generateDrafting($request);
        }
        else if($request->department == "SCHEDES"){
            
        }
        
        
    }

    public function timeSheetListByUser(Request $request){

      $shifting_schedule = ShiftingSchedule::select(ShiftingSchedule::raw('DATE_FORMAT(morning_start, "%H:%i") as morning_start')
      ,ShiftingSchedule::raw('DATE_FORMAT(morning_end, "%H:%i") as morning_end')
      ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_start, "%H:%i") as afternoon_start')
      ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_end, "%H:%i") as afternoon_end'))->where('id','=','1')->first();
      
       
          $query = Timesheet::select(
                    'timesheets.id',
                    'timesheets.user_id',
                    'timesheets.type',
                    'timesheets.drafting_masters_id',
                    'timesheets.scheduling_masters_id',
                    'timesheets.created_at',
                    'user_teams.team',
                    Timesheet::raw('CASE WHEN TIME(job_start) BETWEEN "00:01" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_start, "%r")
                    WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->morning_end.'" AND "'.$shifting_schedule->afternoon_start.'" THEN TIME_FORMAT(job_start, "%r") 
                    ELSE null
                   END as morning_start'),
                   Timesheet::raw('CASE WHEN TIME(job_stop) BETWEEN "00:01" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_stop, "%r") 
                   WHEN TIME(job_stop) BETWEEN "'.$shifting_schedule->morning_end.'" AND "'.$shifting_schedule->afternoon_start.'" THEN TIME_FORMAT(job_stop, "%r") 
                   ELSE null
                   END as morning_stop'),
                   Timesheet::raw(' CASE WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->afternoon_start.'" AND "'.$shifting_schedule->afternoon_end.'" THEN TIME_FORMAT(job_start, "%r")  
                   WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->afternoon_end.'" AND "23:59" THEN TIME_FORMAT(job_start, "%r") 
                   ELSE null
                  END as afternoon_start'),
                  Timesheet::raw(' CASE WHEN TIME(job_stop) BETWEEN "'.$shifting_schedule->afternoon_start.'" AND "'.$shifting_schedule->afternoon_end.'" THEN TIME_FORMAT(job_stop, "%r")  
                  WHEN TIME(job_stop) BETWEEN "'.$shifting_schedule->afternoon_end.'" AND "23:59" THEN TIME_FORMAT(job_stop, "%r") 
                  ELSE null
                  END as afternoon_stop'),
                  Timesheet::raw('TIMESTAMPDIFF(SECOND, job_start, job_stop) AS hours '))
                  ->leftJoin('users','timesheets.user_id','users.id')
                  ->leftJoin('user_teams','users.id','user_teams.user_id');

                  if ( $request->from AND $request->to ) {
                    $query = $query->whereDate('timesheets.created_at', '>=', $request->from)
                    ->whereDate('timesheets.created_at', '<=', $request->to);
                }

                  if($request->department == "DFT"){
                    $query = $query->whereNull('timesheets.scheduling_masters_id');
                  }
                  else if($request->department == "SCHEDES"){
                    $query = $query->whereNull('timesheets.drafting_masters_id');
                  }

                  if(!empty($request->team)){
                    $team = $request->team;
                    $query = $query->whereIn('user_teams.team',function($query) use($team){
                      $query->select('team')->from('user_teams')->where('team',$team);
                  });
                  }

                  if(!empty($request->user_id)){
                    $query = $query->where('users.id','=',$request->user_id);
                  }


                  return datatables()->eloquent($query)
                  ->editColumn('user_id', function (Timesheet $timesheet) {
                      $full_name = User::find($timesheet->user_id);
  
                        if(!empty($full_name)){
                            return $full_name->first_name . " " . $full_name->last_name;
                        }

                      })
                  ->editColumn('type', function (Timesheet $timesheet) {
                          if($timesheet->type == "CHECKING"){
                              return "<span class='badge bg-success'>".$timesheet->type."</span>";
                          }
                          else{
                              return "<span class='badge bg-primary'>".$timesheet->type."</span>";
                          }
                      })
  
                  ->rawColumns(['user_id','type'])
                  ->toJson();
        }


    public function generateDrafting($request){
     
            $drafting = DraftingMaster::select(
                'drafting_masters.id',
                'drafting_masters.customer_name',
                'drafting_masters.client_name',
                'drafting_masters.job_number',
                'drafting_masters.address',
                'drafting_masters.type',
                'drafting_masters.ETA',
                'drafting_masters.brand',
                'drafting_masters.job_type',
                'drafting_masters.category',
                'drafting_masters.floor_area',
                'drafting_masters.prospect',
                'drafting_masters.status',
                DraftingMaster::raw("(CASE WHEN drafting_masters.six_stars='0' THEN 'No' ELSE 'Yes' END) as six_stars"),
                'drafting_masters.created_at',
                'timesheets.job_start'
            )->leftJoin('timesheets','timesheets.drafting_masters_id','drafting_masters.id')
            ->groupBy('drafting_masters.id');

            if ( $request->from AND $request->to ) {
                
              $drafting = $drafting->whereDate('drafting_masters.created_at', '>=', $request->from)
                    ->whereDate('drafting_masters.created_at', '<=', $request->to);
            }

            if ($request->status) {
              if($request->status == "On-going"){
                $drafting = $drafting->whereNotNull('timesheets.job_start');
                $drafting = $drafting->whereIn('drafting_masters.status', ['Assigned', 'Ready For Check'
                , 'Ready To Submit', 'Ready For Six Stars', 'In Six Stars']);
              }
              else{
                $drafting = $drafting->where('drafting_masters.status', $request->status);
              }
                
            }

            if ( $request->customer) {
                $drafting = $drafting->where('drafting_masters.customer_name', $request->customer);
            }

            if ( $request->team) {
                $team = $request->team;
                $drafting = $drafting->whereIn('drafting_masters.customer_name',function($query) use ($team){
                    $query->select('name')->from('customers')->where('team','=',$team);
                });
            }

            if ( $request->type) {
                $drafting = $drafting->where('drafting_masters.type', $request->type);
            }

            if ( $request->job_type) {
                $drafting = $drafting->where('drafting_masters.job_type', $request->job_type);
            }

            if ( $request->brand) {
                $drafting = $drafting->where('drafting_masters.brand', $request->brand);
            }

            if ( $request->category) {
                $drafting = $drafting->where('drafting_masters.category', $request->category);
            }

           

            return datatables()->eloquent($drafting)
                ->editColumn('drafters', function (DraftingMaster $draftingmaster) {
                  $drafters_arr = [];
    
                  $jobtimehistory = JobTimeHistory::select(
                    'user_id')
                    ->where('drafting_masters_id', '=', $draftingmaster->id)
                    ->where('type', '=', 'DRAFTING')->get();
    
                    foreach($jobtimehistory as $r){
                      $drafter = User::select(
                        User::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
                        ->where('id', '=', $r->user_id)->first();
                        if(!empty($drafter)){
                          array_push($drafters_arr,  $drafter->full_name);
                        }
                     
                  }
                  
                    if(empty($drafters_arr)){
                      return "<button class='btn btn-danger'>NO DRAFTER</button>";
                    }
                    else{
                      if($draftingmaster->status == "Assigned"){
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white '><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . implode(', ',$drafters_arr) . "</button>";
                      }
                      else{
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white' ><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . implode(', ',$drafters_arr) . "</button>";
                      }
                        
                    }
                  })
                
                  ->editColumn('drafting_hours', function (DraftingMaster $draftingmaster) {
                      return app('App\Http\Controllers\DraftingMasterController')->getTypeHours($draftingmaster,'DRAFTING') ?? "N/A";
                    })
                    ->editColumn('checker', function (DraftingMaster $draftingmaster) {
        
                      $jobtimehistory = JobTimeHistory::select(
                        'user_id')
                        ->where('drafting_masters_id', '=', $draftingmaster->id)
                        ->where('type', '=', 'CHECKING')->first();
        
                      if(!empty($jobtimehistory->user_id)){
                        $checker = User::select(
                          User::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
                          ->where('id', '=', $jobtimehistory->user_id)->first();
                      }
    
                        if(empty($jobtimehistory)){
                          if($draftingmaster->status == "Ready For Check"){
                            return "<button class='btn btn-danger'>NO DRAFTER</button>";
                          }
                          else{
                            return "<button class='btn btn-danger'>NO DRAFTER</button>";
                          }
                        }
                        else{
                          if($draftingmaster->status == "Ready To Submit" || $draftingmaster->status == "Ready For Six Stars" || $draftingmaster->status == "In Six Stars"){
                            return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white  ><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $checker->full_name . "</button>";
                          }
                          else{
                            return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $checker->full_name . "</button>";
                          }
                           
                        }
                      })
                    ->editColumn('checking_hours', function (DraftingMaster $draftingmaster) {
                        return app('App\Http\Controllers\DraftingMasterController')->getTypeHours($draftingmaster,'CHECKING') ?? "N/A";
                      })
                    ->editColumn('status', function (DraftingMaster $draftingmaster) {
                     
                          return $draftingmaster->status;
                      })
                      ->editColumn('total_hours', function (DraftingMaster $draftingmaster) {
    
                        $total_drafting_hours = app('App\Http\Controllers\DraftingMasterController')->getTypeHours($draftingmaster,'DRAFTING');
                        $total_checking_hours = app('App\Http\Controllers\DraftingMasterController')->getTypeHours($draftingmaster,'CHECKING');
                      
                        return ($total_drafting_hours + $total_checking_hours ) ?? "N/A";
        
                        })
                    
                    ->editColumn('job_number', function (DraftingMaster $draftingmaster) {
                      
                      return '<a role="button" class="btn btn-dark-green text-white " href="' . route('timesheets.drafting', $draftingmaster->id) .'">'. $draftingmaster->job_number.'</a>'; 
                      
                    })
                    ->rawColumns(['drafters','drafting_hours','checker','checking_hours','checking_hours','status','total_hours','edit_job','submit_job','job_number','cancel_job'])
                    ->toJson();
          
    
    }

  }



