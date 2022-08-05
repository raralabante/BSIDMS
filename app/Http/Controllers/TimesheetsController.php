<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DraftingMaster;
use App\Models\SchedulingMaster;
use App\Models\ShiftingSchedule;
use App\Models\Timesheet;
use Error;
use Illuminate\Support\Facades\Auth;


class TimesheetsController extends Controller
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
    public function index_drafting(Request $request)
    {
        $drafting_masters = DraftingMaster::where('id','=',$request->id)->first();
        
		  return view('timesheet.timesheet_drafting',compact('drafting_masters'));
    }

    public function index_scheduling(Request $request)
    {
        $scheduling_masters = SchedulingMaster::where('id','=',$request->id)->first();
        
		  return view('timesheet.timesheet_scheduling',compact('scheduling_masters'));
    }

   public function timeSheetListDrafting(Request $request){

    
    $shifting_schedule = ShiftingSchedule::select(ShiftingSchedule::raw('DATE_FORMAT(morning_start, "%H:%i") as morning_start')
    ,ShiftingSchedule::raw('DATE_FORMAT(morning_end, "%H:%i") as morning_end')
    ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_start, "%H:%i") as afternoon_start')
    ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_end, "%H:%i") as afternoon_end'))->where('id','=','1')->first();
    
     
        $drafting = Timesheet::select(
                  'id',
                  'user_id',
                  'type',
                  'drafting_masters_id',
                  'created_at',
                  Timesheet::raw('CASE WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->morning_start.'" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_start, "%r")
                  WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->morning_end.'" AND "'.$shifting_schedule->afternoon_start.'" THEN TIME_FORMAT(job_start, "%r") 
                  ELSE null
                 END as morning_start'),
                 Timesheet::raw('CASE WHEN TIME(job_stop) BETWEEN "'.$shifting_schedule->morning_start.'" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_stop, "%r") 
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
                ->where('drafting_masters_id','=',$request->id);

                return datatables()->eloquent($drafting)
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
   
      public function timeSheetListScheduling(Request $request){

    
        $shifting_schedule = ShiftingSchedule::select(ShiftingSchedule::raw('DATE_FORMAT(morning_start, "%H:%i") as morning_start')
        ,ShiftingSchedule::raw('DATE_FORMAT(morning_end, "%H:%i") as morning_end')
        ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_start, "%H:%i") as afternoon_start')
        ,ShiftingSchedule::raw('DATE_FORMAT(afternoon_end, "%H:%i") as afternoon_end'))->where('id','=','1')->first();
        
         
            $drafting = Timesheet::select(
                      'id',
                      'user_id',
                      'type',
                      'scheduling_masters_id',
                      'created_at',
                      Timesheet::raw('CASE WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->morning_start.'" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_start, "%r")
                      WHEN TIME(job_start) BETWEEN "'.$shifting_schedule->morning_end.'" AND "'.$shifting_schedule->afternoon_start.'" THEN TIME_FORMAT(job_start, "%r") 
                      ELSE null
                     END as morning_start'),
                     Timesheet::raw('CASE WHEN TIME(job_stop) BETWEEN "'.$shifting_schedule->morning_start.'" AND "'.$shifting_schedule->morning_end.'" THEN TIME_FORMAT(job_stop, "%r") 
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
                    ->where('scheduling_masters_id','=',$request->id);
    
                    return datatables()->eloquent($drafting)
                    ->editColumn('user_id', function (Timesheet $timesheet) {
                        $full_name = User::find($timesheet->user_id);
    
                        if(!empty($full_name)){
                            return $full_name->first_name . " " . $full_name->last_name;
                        }
                       
    
                        })
                    ->editColumn('type', function (Timesheet $timesheet) {
                            if($timesheet->type == "SCHEDULE CHECKING"){
                                return "<span class='badge bg-success'>".$timesheet->type."</span>";
                            }
                            else{
                                return "<span class='badge bg-primary'>".$timesheet->type."</span>";
                            }
                        })
    
                    ->rawColumns(['user_id','type'])
                    ->toJson();

          }
     

}
