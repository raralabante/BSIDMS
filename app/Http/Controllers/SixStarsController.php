<?php

namespace App\Http\Controllers;

use App\Models\DraftingMaster;
use App\Models\JobTimeHistory;
use Illuminate\Http\Request;
use App\Models\Ammends;
use App\Events\Message;
class SixStarsController extends Controller
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
		  return view('sixstars.sixstars');
    }

    public function getForSixStars(){
        $for_six_stars = DraftingMaster::select(
            'job_number as value')
         ->where('status','=','Ready For Six Stars')->get();
        return $for_six_stars;
    }

    public function addSixStars(Request $request){
        $job_number = $request->id;

        $drafting_masters = DraftingMaster::select('id','status')->where('job_number','=',$job_number)->first();

        if(!empty($drafting_masters)){
            $drafting_masters->status = "In Six Stars";
            $drafting_masters->six_stars_submitted_at = now();
            $drafting_masters->save();

            $description = "Job# " . $job_number. " is now in Six Stars.";
            app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
            app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
            app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );
            event(new Message(''));
            return 1;
        }
        else{
            return 0;
        }
    }

    public function sixStarsList(Request $request) {
     
        if ($request->ajax()) {
          $query = DraftingMaster::select(
                    'id',
                    'job_number',
                    'six_stars_submitted_at',
                    'customer_name')
          ->where('status','=', 'In Six Stars')
          ->where('six_stars','=','1');
  
          return datatables()->eloquent($query)
                ->editColumn('ammend', function (DraftingMaster $draftingmaster) {
                  return '<button class="btn btn-warning ammend" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" ><i class="fa-solid fa-repeat"></i>&nbsp;&nbsp;Ammend</button>';
                })
                ->editColumn('submit', function (DraftingMaster $draftingmaster) {
                  return '<button class="btn btn-success submit" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '"><i class="fa-solid fa-paper-plane"></i>&nbsp;&nbsp;Submit</button>';
                })
              ->rawColumns(['submit','ammend'])
              ->toJson();
        }
      }

      public function ammendJob(Request $request) {

        $draft = DraftingMaster::findOrFail($request->id);

        if(!empty($draft)){
            $draft->status = 'Unassigned';
            $draft->save();
            JobTimeHistory::where('drafting_masters_id','=',$request->id)->delete();
            
        Ammends::create([
            'drafting_masters_id' => $request->id,
        ]);

        $description = "Job# " . $draft->job_number. " has been ammended.";
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );
        event(new Message(''));
        }
       
    }
  
    public function submitJob(Request $request) {
        $draft = DraftingMaster::findOrFail($request->id);
        
        $draft->status = 'Ready To Submit';
        $draft->six_stars_received_at == now();
        $draft->save();

        $description = "Job# " . $draft->job_number. " is now ready to submit.";
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,3 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,4 );
        app('App\Http\Controllers\DraftingMasterController')->addActivity($description,9 );
        event(new Message(''));
      }

      

}
