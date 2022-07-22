<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\DraftingMaster;
use App\Models\Timesheet;
use App\Models\JobDraftingStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\RoleActivity;
use App\Models\JobTimeHistory;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Events\Message;
class DraftingMasterController extends Controller
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
		  return view('draftingmaster.draftingmaster');
    }

    public function index_submitted()
    {
		  return view('draftingmaster.draftingmastersubmitted');
    }
    
    public function index_cancelled()
    {
		  return view('draftingmaster.draftingmastercancelled');
    }

    protected function insert(Request $request)
    {
      
       $request->validate([
        'customer_name' => 'required|max:255|exists:customers,name',
        'job_number' => 'required|max:255|unique:App\Models\DraftingMaster,job_number',
        'client_name' => 'required|max:255',
        'address' => 'required|max:255',
        'type' => 'required|max:255|exists:App\Models\Type,name',
        'eta' => 'required|max:255|date',
        'floor_area' => 'nullable|numeric',
        'brand' => 'nullable|exists:App\Models\Brand,name',
        'job_type' => 'nullable|exists:App\Models\JobType,name',
        'category' => 'nullable|exists:App\Models\Categories,name',
      ]);

      event(new Message(''));

      $status = "";

      if($request->six_stars == null){
        $request->six_stars = 0;
      }

      $drafters = $request->drafters;
      if(empty($drafters)){
        $status = "Unassigned";
      }
      else{
        $status = "Assigned";
      }
        $newJob = DraftingMaster::create([
            'customer_name' => $request->customer_name,
            'job_number' => strtoupper($request->job_number),
            'client_name' => $request->client_name,
            'address' => $request->address,
            'type' => $request->type,
            'eta' => $request->eta,
            'brand' => $request->brand,
            'job_type' => $request->job_type,
            'category' => $request->category,
            'floor_area' => $request->floor_area,
            'prospect' => $request->prospect,
            'six_stars' => $request->six_stars,
            'status' => $status,
        ]);
      
        $description = "(DRAFTING) Job# " . $request->job_number . " has been assigned to you.";
        if(!empty($drafters)){
          $drafters_arr = explode (",", $drafters); 
          foreach($drafters_arr as $draft){
            JobTimeHistory::insert(
              array(
                'user_id' => $draft,
                'drafting_masters_id' => $newJob->id,
                'type' => 'DRAFTING',
                'created_at' => now(),
              )
            );
            
            Self::addActivityById($description,$draft,10); //10=DRAFTER
          }
          
      }
      
      $newJob->save();
      
        return redirect()->back()->with('success', 'Client Job# ' . $request->job_number . ' has been added.');
    }

    protected function fetch(Request $request)
    {
      return DraftingMaster::where('id','=',$request->id)->first();
    }

    protected function fetchDrafters(Request $request)
    {
      return User::select(
        User::raw('group_concat(users.id SEPARATOR ", ") as users_id'),
        User::raw('group_concat(CONCAT(users.first_name, " ", users.last_name) SEPARATOR ", ") as full_name'))
        ->leftJoin('job_time_histories','job_time_histories.user_id','users.id')
        ->where('job_time_histories.drafting_masters_id', '=', $request->id)
        ->where('job_time_histories.type', '=', 'DRAFTING')->first();
    }
    
    protected function fetchChecker(Request $request)
    {
      return User::select(
        User::raw('group_concat(users.id SEPARATOR ", ") as users_id'),
        User::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
        ->leftJoin('job_time_histories','job_time_histories.user_id','users.id')
        ->where('job_time_histories.drafting_masters_id', '=', $request->id)
        ->where('job_time_histories.type', '=', 'CHECKING')->first();
    }

    protected function edit(Request $request)
    {

      $request->validate([
        'edit_customer_name' => 'required|max:255|exists:customers,name',
        'edit_client_name' => 'required|max:255',
        'edit_address' => 'required|max:255',
        'edit_type' => 'required|max:255|exists:App\Models\Type,name',
        // 'edit_eta' => 'required|max:255|date',
        'edit_floor_area' => 'nullable|numeric',
        'edit_brand' => 'nullable|exists:App\Models\Brand,name',
        'edit_job_type' => 'nullable|exists:App\Models\JobType,name',
        'edit_category' => 'nullable|exists:App\Models\Categories,name',
      ]);
      
     
      if($request->edit_six_stars == null){
        $request->edit_six_stars = 0;
      }

      $edit_job = DraftingMaster::where('id','=',$request->edit_draft_id)->get()->first();


      $checkers = JobTimeHistory::select('job_time_histories.type')
      ->leftJoin('drafting_masters','drafting_masters.id','job_time_histories.drafting_masters_id')
      ->where('job_time_histories.drafting_masters_id','=', $request->edit_draft_id)
      ->where('job_time_histories.type','=','CHECKING')->first();
   
      if(!empty($checkers)){
        if($edit_job->status == "Ready To Submit" OR $edit_job->status == "Ready For Six Stars" OR $edit_job->status == "In Six Stars"){
          throw ValidationException::withMessages(['edit_six_stars' => 'You cannot edit six stars after checking.']);
        }
       
      }

      $edit_job->customer_name = $request->edit_customer_name;
      $edit_job->client_name = $request->edit_client_name;
      $edit_job->address = $request->edit_address;
      $edit_job->type = $request->edit_type;
      // $edit_job->eta = $request->edit_eta;
      $edit_job->brand = $request->edit_brand;
      $edit_job->job_type = $request->edit_job_type;
      $edit_job->category = $request->edit_category;
      $edit_job->floor_area = $request->edit_floor_area;
      $edit_job->prospect = $request->edit_prospect;
      $edit_job->six_stars = $request->edit_six_stars;

      $edit_job->save();
      event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' has been updated.');
    }

    protected function assignDrafters(Request $request)
    {

      $request->validate([
        'drafters' => 'required|max:255',
      ]);

      $edit_job = DraftingMaster::where('id','=',$request->draft_id)->get()->first();
      $edit_job->status = "Assigned";
      $edit_job->save();
   
 
      $drafters = $request->drafters;

      $description = "(DRAFTING) Job# " . $edit_job->job_number . " has been assigned to you.";
      if(!empty($drafters)){
        $drafters_arr = explode (",", $drafters); 
      foreach($drafters_arr as $user_id){
        JobTimeHistory::insert(
           array(
             'user_id' => $user_id,
             'drafting_masters_id' => $request->draft_id,
             'type' => 'DRAFTING',
             'created_at' => now(),
           )
        );
        Self::addActivityById($description,$user_id,10); //10=DRAFTER
      }
            

    }
        event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->job_number . ' drafters has been assigned.');
    }

    protected function assignChecker(Request $request)
    {
      $request->validate([
        'checker' => 'required|max:255',
      
      ]);

        JobTimeHistory::insert(
           array(
             'user_id' => $request->checker,
             'drafting_masters_id' => $request->draft_id,
             'type' => 'CHECKING',
             'created_at' => now(),
           )
        );

        $description = "(CHECKING) Job# " . $request->job_number . " has been assigned to you.";
            Self::addActivityById($description,$request->checker,11); //10=DRAFTER
            event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->job_number . ' checker has been assigned.');
    }

    protected function editDrafters(Request $request)
    {
      $assigned_user_arr = [];
      $assigned_user_and_active_arr = [];
      $new_assigned_user_arr = [];

      
      $drafters = $request->edit_drafters;
      $drafters_arr = explode (",", $drafters); 
      foreach($drafters_arr as $user_id){
        array_push($new_assigned_user_arr , $user_id);
      }

      $assigned_user = JobTimeHistory::select('user_id')->where('drafting_masters_id','=',$request->edit_draft_id)
      ->where('type','=','DRAFTING')->get();

      foreach($assigned_user as $r){
        array_push($assigned_user_arr , $r->user_id);
        
      }
    
      $assigned_user_and_active = JobDraftingStatus::select('user_id')->where('drafting_masters_id','=',$request->edit_draft_id)
      ->where('status','=','1')
      ->where('type','=','DRAFTING')->get();

      foreach($assigned_user_and_active as $r){
        array_push($assigned_user_and_active_arr , $r->user_id);
      }

      $active_users = array_intersect($assigned_user_and_active_arr, $assigned_user_arr);

      $new_assigned_user = array_intersect($active_users,$new_assigned_user_arr);
      error_log('ACTIVE USERS COUNT: '. count($active_users));
      error_log('NEW ASSIGNED USERS COUNT: '. count($new_assigned_user));

      
      if(count($active_users) > 0){ //IF THERE ARE ACTIVE USERS
        if(count($new_assigned_user) == 0){ //IF NEW ASSIGNED USER IS ACTIVE
          error_log("1");
          return redirect()->back()->with('error', 'Error while editing drafters for Client Job# ' . $request->edit_job_number. ' one or more drafter is currently active');
        }
        else{
          error_log("2");
          //DELETE ALL ASSIGNED DRAFTERS AND INSERT NEW DRAFTERS
            JobTimeHistory::where('drafting_masters_id','=',$request->edit_draft_id)
            ->where('type','=','DRAFTING')->delete();

            foreach($drafters_arr as $user_id){
              JobTimeHistory::insert(
                array(
                  'user_id' => $user_id,
                  'drafting_masters_id' => $request->edit_draft_id,
                  'type' => 'DRAFTING',
                  'created_at' => now(),
                )
              );

              $description = "(DRAFTING) Job# " . $request->edit_job_number . " has been assigned to you.";
            Self::addActivityById($description,$user_id,10); //10=DRAFTER

        }
        event(new Message(''));
          return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' drafters has been updated.');
        }
       
      }
      else{
        
        //DELETE ALL ASSIGNED DRAFTERS
        if(empty($drafters_arr)){
          JobTimeHistory::where('drafting_masters_id','=',$request->edit_draft_id)
          ->where('type','=','DRAFTING')->delete();
        }
        else{
          JobTimeHistory::where('drafting_masters_id','=',$request->edit_draft_id)
            ->where('type','=','DRAFTING')->delete();
            foreach($drafters_arr as $user_id){
              JobTimeHistory::insert(
                array(
                  'user_id' => $user_id,
                  'drafting_masters_id' => $request->edit_draft_id,
                  'type' => 'DRAFTING',
                  'created_at' => now(),
                )
              );
        }
    }
        $description = "(DRAFTING) Job# " . $request->edit_job_number . " has been assigned to you.";
        Self::addActivityById($description,$user_id,10); //10=DRAFTER
        event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' drafters has been updated.');
      }
    }
    
    protected function editChecker(Request $request)
    {
      $assigned_user_and_active = JobDraftingStatus::select('user_id')->where('drafting_masters_id','=',$request->edit_draft_id)
      ->where('status','=','1')
      ->where('type','=','CHECKING')->first();
      
      $check_new_user_status = JobDraftingStatus::select('user_id')->where('drafting_masters_id','=',$request->edit_draft_id)
      ->where('status','=','1')
      ->where('user_id','=',$request->checker)
      ->where('type','=','CHECKING')->first();

      if(!empty($assigned_user_and_active)){ //IF THERE IS ASSIGNED USER AND ACTIVE
        if(!empty($check_new_user_status)){ // CHECK IF NEW ASSIGNED USER IS ALREADY ASSIGED
          error_log('1');

          JobTimeHistory::where('drafting_masters_id','=',$request->edit_draft_id)
            ->where('type','=','CHECKING')->delete();

          JobDraftingStatus::where('drafting_masters_id','=',$request->edit_draft_id)->where('type','=','CHECKING');
          JobTimeHistory::insert(
            array(
              'user_id' => $request->checker,
              'drafting_masters_id' => $request->edit_draft_id,
              'type' => 'CHECKING',
              'created_at' => now(),
            )
         );

            $description = "(CHECKING) Job# " . $request->edit_job_number . " has been assigned to you.";
            Self::addActivityById($description,$request->checker,11); //10 DRAFTER, 11=CHECKER


            return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' checker has been updated.');
        }
        else{
          //IF THE NEW ASSIGNED USER IS  NOT YET ASSIGNED
          error_log('2');
          return redirect()->back()->with('error', 'Error while editing checker for Client Job# ' . $request->edit_job_number. ' a checker is currently active');
        }
       
      }
      else{ //IF THERE IS NO ASSIGNED USER
        error_log('3');
        JobTimeHistory::where('drafting_masters_id','=',$request->edit_draft_id)
            ->where('type','=','CHECKING')->delete();

          JobDraftingStatus::where('drafting_masters_id','=',$request->edit_draft_id)->where('type','=','CHECKING');
          JobTimeHistory::insert(
            array(
              'user_id' => $request->checker,
              'drafting_masters_id' => $request->edit_draft_id,
              'type' => 'CHECKING',
              'created_at' => now(),
            )
         );

         $description = "(CHECKING) Job# " . $request->edit_job_number . " has been assigned to you.";
            Self::addActivityById($description,$request->checker,11); //10 DRAFTER, 11=CHECKER
            
            event(new Message(''));
        return redirect()->back()->with('success', 'Client Job# ' . $request->edit_job_number . ' checker has been updated.');
      }

    }
        
    public function draftingMasterList(Request $request) {
      if ($request->ajax()) {
        $query = DraftingMaster::select(
                  'id',
                  'customer_name',
                  'client_name',
                  'job_number',
                  'address',
                  'type',
                  'ETA',
                  'brand',
                  'job_type',
                  'category',
                  'floor_area',
                  'prospect',
                  'status',
                  DraftingMaster::raw("(CASE WHEN six_stars='0' THEN 'No' ELSE 'Yes' END) as six_stars"),
                  'created_at'
        )
        ->whereIn('customer_name',function($query){
          $query->select('name')->from('customers')->where('team','=',Auth::user()->team);
       })
        ->where('status','!=','Submitted')
        ->where('status','!=','Cancelled');
                 
        return datatables()->eloquent($query)
          ->editColumn('delete_draft', function (DraftingMaster $draftingmaster) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-categories-btn w-100" data-id="'. $draftingmaster->id .'" data-customer_name="'. $draftingmaster->customer_name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
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
                  return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_drafter ' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#assign_drafter_modal' ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                }
                else{
                  if($draftingmaster->status == "Assigned"){
                    return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_drafter' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#edit_drafter_modal'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . implode(', ',$drafters_arr) . "</button>";
                  }
                  else{
                    return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_drafter' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#edit_drafter_modal' disabled><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . implode(', ',$drafters_arr) . "</button>";
                  }
                    
                }
              })
            
              ->editColumn('drafting_hours', function (DraftingMaster $draftingmaster) {
                  return Self::getTypeHours($draftingmaster,'DRAFTING') ?? "N/A";
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
                        return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_checker ' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#assign_checker_modal' ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                      }
                      else{
                        return "<button type='button' class='btn btn-shrimpy w-100 text-white assign_checker ' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#assign_checker_modal' disabled ><i class='fa-solid fa-handshake-simple'></i>&nbsp;&nbsp;ASSIGN</button>";
                      }
                    }
                    else{
                      if($draftingmaster->status == "Ready To Submit"){
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_checker' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#edit_checker_modal' disabled><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $checker->full_name . "</button>";
                      }
                      else{
                        return "<button type='button' class='btn btn-dark-green btn-sm w-100 text-white edit_checker' data-id='".$draftingmaster->id."' data-job_number='".$draftingmaster->job_number."' data-toggle='modal' data-target='#edit_checker_modal'><i class='fa-solid fa-pen'></i>&nbsp;&nbsp;" . $checker->full_name . "</button>";
                      }
                       
                    }
                  })
                ->editColumn('checking_hours', function (DraftingMaster $draftingmaster) {
                    return Self::getTypeHours($draftingmaster,'CHECKING') ?? "N/A";
                  })
                ->editColumn('status', function (DraftingMaster $draftingmaster) {
                 
                      return Self::getStatusColor($draftingmaster->status);
                  })
                  ->editColumn('total_hours', function (DraftingMaster $draftingmaster) {

                    $total_drafting_hours = Self::getTypeHours($draftingmaster,'DRAFTING');
                    $total_checking_hours = Self::getTypeHours($draftingmaster,'CHECKING');
                  
                    return ($total_drafting_hours + $total_checking_hours ) ?? "N/A";
    
                    })
                ->editColumn('edit_job', function (DraftingMaster $draftingmaster) {
                  return '<button type="button" class="btn btn-light border border-dark  edit_job" data-id="'.$draftingmaster->id.'" data-job_number="'.$draftingmaster->job_number.'" data-toggle="modal" data-target="#edit_job_modal">
                  <i class="fa-solid fa-pen"></i>
                </button>';
                })
                ->editColumn('cancel_job', function (DraftingMaster $draftingmaster) {
                    return '<button class="btn btn-light border border-dark cancel_job" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '"><i class="fa-solid fa-ban"></i></button>';
                })
                ->editColumn('submit_job', function (DraftingMaster $draftingmaster) {

                  if($draftingmaster->status == "Ready To Submit"){
                    return '<button class="btn btn-success submit_job" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" ><i class="fa-solid fa-paper-plane"></i></button>';
                  }
                  else{
                    return '<button class="btn btn-success submit_job" data-id="'.$draftingmaster->id.'" data-job_number="' . $draftingmaster->job_number . '" disabled><i class="fa-solid fa-paper-plane"></i></button>';
                  }
                  
                })
                ->editColumn('job_number', function (DraftingMaster $draftingmaster) {
                  
                  return '<a role="button" class="btn btn-dark-green text-white " href="' . route('timesheets', $draftingmaster->id) .'">'. $draftingmaster->job_number.'</a>'; 
                  
                })
                ->rawColumns(['drafters','drafting_hours','checker','checking_hours','checking_hours','status','total_hours','edit_job','submit_job','job_number','cancel_job'])
                ->toJson();
      }
    }

    public function getTypeHours($draftingmaster, $type){
                $total_time = 0;
                  $time_diff = Timesheet::select(Timesheet::raw('TIMESTAMPDIFF(SECOND, timesheets.job_start, timesheets.job_stop) AS difference '))
                  ->leftJoin('job_drafting_status','timesheets.drafting_masters_id','job_drafting_status.drafting_masters_id')
                  ->where('timesheets.type', '=' , $type)
                  ->where('job_drafting_status.status', '=' , '0')
                  ->where('timesheets.drafting_masters_id', '=' , $draftingmaster->id)
                  ->whereNotNull('timesheets.job_stop')
                  ->groupBy('timesheets.id')->get();
                  
                  foreach($time_diff as $data){
                    $total_time += $data->difference;
                  }
    
                  $active_job = Timesheet::select('timesheets.job_start', Timesheet::raw('SUM(TIMESTAMPDIFF(SECOND, timesheets.job_start, now())) AS difference '))
                  ->leftJoin('job_drafting_status','timesheets.drafting_masters_id','job_drafting_status.drafting_masters_id')
                  // ->where('job_drafting_status.user_id', '=', Auth::user()->id)
                  ->where('timesheets.type', '=' , $type)
                  ->where('job_drafting_status.status', '=' , '1')
                  ->where('timesheets.drafting_masters_id', '=' , $draftingmaster->id)
                  ->whereNull('timesheets.job_stop')
                  ->first();
  
                  return $total_time + $active_job->difference;
                  
   }

   public function getStatusColor($status){
    $color_success = ['bg-secondary','bg-warning text-dark','bg-primary','bg-info','bg-light text-dark','bg-dark', 'bg-success'];
      if($status == "Unassigned"){
        return '<span class="badge '. $color_success[0] .'">'.$status.'</span>';
      }
      else if($status == "Assigned"){
        return '<span class="badge '. $color_success[1] .'">'.$status.'</span>';
      }
      else if($status == "Ready For Check"){
        return '<span class="badge '. $color_success[2] .'">'.$status.'</span>';
      }
      else if($status == "Ready To Submit"){
        return '<span class="badge '. $color_success[3] .'">'.$status.'</span>';
      }
      else if($status == "Ready For Six Stars"){
        return '<span class="badge '. $color_success[4] .'">'.$status.'</span>';
      }
      else if($status == "In Six Stars"){
        return '<span class="badge '. $color_success[5] .'">'.$status.'</span>';
      }
      
   }

   public function submitJob(Request $request){
    $drafting_masters = DraftingMaster::find($request->id);
            if($drafting_masters->status == "Ready To Submit"){
              $description = "(COMPLETED) Job# " . $drafting_masters->job_number . " has been submitted.";
              Self::addActivity($description,3 );
              Self::addActivity($description,4 );
              Self::addActivity($description,9 );
              event(new Message(''));
              return DraftingMaster::where('id','=', $request->id)
              ->update(['status' => "Submitted",'submitted_at' => now(),'submitted_by' => Auth::user()->team]);
            }
            else{
              return 0;
            }
   }

   public function cancelJob(Request $request){
    $drafting_masters = DraftingMaster::find($request->id);
            if($drafting_masters->status != "Submitted"){

              $description = "Job# " . $drafting_masters->job_number . " has been cancelled.";
              Self::addActivity($description,3 );
              Self::addActivity($description,4 );
              Self::addActivity($description,9 );
              event(new Message(''));
              return DraftingMaster::where('id','=', $request->id)
              ->update(['status' => "Cancelled"]);
            }
            else{
              return 0;
            }
   }

   public function fetchByStatusList(Request $request) {

    if ($request->ajax()) {
      $query = DraftingMaster::select(
                'id',
                'customer_name',
                'client_name',
                'job_number',
                'address',
                'type',
                'ETA',
                'brand',
                'job_type',
                'category',
                'floor_area',
                'prospect',
                'status',
                DraftingMaster::raw("(CASE WHEN six_stars='0' THEN 'No' ELSE 'Yes' END) as six_stars"),
                'created_at',
                'submitted_at'
      )
      ->whereIn('customer_name',function($query){
        $query->select('name')->from('customers')->where('team','=',Auth::user()->team);
     })
      ->where('status','=',$request->status);
               
      return datatables()->eloquent($query)
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
            

              return  '<button class="btn btn-dark-green text-white">'.implode(', ',$drafters_arr).'</button>';
              
            })
          
            ->editColumn('drafting_hours', function (DraftingMaster $draftingmaster) {
                return Self::getTypeHours($draftingmaster,'DRAFTING') ?? "N/A";
              })
              ->editColumn('checker', function (DraftingMaster $draftingmaster) {
                $checker_fullname = "";
                $jobtimehistory = JobTimeHistory::select(
                  'user_id')
                  ->where('drafting_masters_id', '=', $draftingmaster->id)
                  ->where('type', '=', 'CHECKING')->first();
  
                if(!empty($jobtimehistory->user_id)){
                  $checker = User::select(
                    User::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))
                    ->where('id', '=', $jobtimehistory->user_id)->first();

                    $checker_fullname = $checker->full_name;
                }
                return  '<button class="btn btn-dark-green text-white">'.$checker_fullname.'</button>';
                    
                })
              ->editColumn('checking_hours', function (DraftingMaster $draftingmaster) {
                  return Self::getTypeHours($draftingmaster,'CHECKING') ?? "N/A";
                })
                ->editColumn('total_hours', function (DraftingMaster $draftingmaster) {

                  $total_drafting_hours = Self::getTypeHours($draftingmaster,'DRAFTING');
                  $total_checking_hours = Self::getTypeHours($draftingmaster,'CHECKING');
                
                  return ($total_drafting_hours + $total_checking_hours ) ?? "N/A";
  
                  })
                ->editColumn('total_hours', function (DraftingMaster $draftingmaster) {

                  $total_drafting_hours = Self::getTypeHours($draftingmaster,'DRAFTING');
                  $total_checking_hours = Self::getTypeHours($draftingmaster,'CHECKING');
                
                  return ($total_drafting_hours + $total_checking_hours ) ?? "N/A";
  
                  })
                  ->editColumn('job_number', function (DraftingMaster $draftingmaster) {
                  
                    return '<a role="button" class="btn btn-dark-green text-white " href="' . route('timesheets', $draftingmaster->id) .'">'. $draftingmaster->job_number.'</a>'; 
                    
                  })
              ->rawColumns(['drafters','drafting_hours','checker','checking_hours','total_hours','job_number'])
              ->toJson();
    }
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

      RoleActivity::insert(
        array(
          'activity_id' => $activity->id,
          'role' => $target_role,
          'user_id' => $user_id,
          'created_at' => now(),
        )
      );
    $activity->save();
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

      RoleActivity::insert(
        array(
          'activity_id' => $activity->id,
          'role' => $target_role,
          'created_at' => now(),
        )
      );
    $activity->save();
   }

}
