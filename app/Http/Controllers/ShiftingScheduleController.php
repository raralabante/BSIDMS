<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DraftingMaster;
use App\Models\ShiftingSchedule;
use App\Models\Timesheet;

class ShiftingScheduleController extends Controller
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


    public function index(Request $request)
    {
        $shifting_schedule = ShiftingSchedule::find(1)->first();
		  return view('shiftingschedule.shiftingschedule',compact('shifting_schedule'));
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'morning_start' => 'required',
            'morning_end' => 'required',
            'afternoon_start' => 'required',
            'afternoon_end' => 'required',
            
          ]);

        $shifting_schedule = ShiftingSchedule::find(1)->first();
        
            $shifting_schedule->morning_start = $request->morning_start;
            $shifting_schedule->morning_end = $request->morning_end;
            $shifting_schedule->afternoon_start = $request->afternoon_start;
            $shifting_schedule->afternoon_end = $request->afternoon_end;
		    $shifting_schedule->save();

            return redirect()->back()->with('success', 'Shifting schedules has been updated.');
    }

}
