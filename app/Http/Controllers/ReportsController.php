<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function draftingTableList(Request $request) {
       
          $query = "";
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
                ->where('status','!=','Submitted')
                ->where('status','!=','Cancelled');
                      
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
                        if($draftingmaster->status == "Ready To Submit" || $draftingmaster->status == "Ready For Six Stars" || $draftingmaster->status == "In Six Stars"){
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
                   
                        return $draftingmaster->status;
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
                    
                    return '<a role="button" class="btn btn-dark-green text-white " href="' . route('timesheets.drafting', $draftingmaster->id) .'">'. $draftingmaster->job_number.'</a>'; 
                    
                  })
                  ->rawColumns(['drafters','drafting_hours','checker','checking_hours','checking_hours','status','total_hours','edit_job','submit_job','job_number','cancel_job'])
                  ->toJson();
        }
      }


