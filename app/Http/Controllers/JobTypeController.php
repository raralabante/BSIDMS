<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\JobType;
use Error;

class JobTypeController extends Controller
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
		  return view('jobtype.jobtype');
    }

    protected function insert(Request $request)
    {
        $newjob_type = JobType::firstOrNew([
            'name' => $request->job_type_name,
        ]);

        if($newjob_type->id == null){
          $newjob_type->save();
          return redirect()->back()->with('success', $newjob_type->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $newjob_type->name . ' is already existing.');
        }
    }

    public function jobTypeList(Request $request) {
      if ($request->ajax()) {
        $query = JobType::select(
                  'id',
                  'name')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_job_type', function (JobType $job_type) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-job_type-btn w-100" data-id="'. $job_type->id .'" data-job_type_name="'. $job_type->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->rawColumns(['delete_job_type'])
            ->toJson();
      }
    }

    public function deleteJobType(Request $request){
        JobType::find($request->id)->delete();
    //   
    }

    public function getJobTypes(){
      $name = JobType::select(
        'name')
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }

}
