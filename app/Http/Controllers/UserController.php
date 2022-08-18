<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\Activity;

use App\Models\Pivot;
use Illuminate\Support\Facades\Auth;
use App\Events\Message;
use Error;

class UserController extends Controller
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
    public function index(Request $request)
    {
        $user_roles = Role::select(
			'name',
            'id',
            'department')
			->orderBy('name', 'ASC')->get();
        
        $route_permissions = $request->roles;

		return view('user.user', compact('user_roles','route_permissions'));
            
    }

    public function userList(Request $request) {
		if ($request->ajax()) {

			$query = User::select(
                'users.id',
				'users.first_name',
                'users.last_name',
                'users.email',
                'users.department',
                // Pivot::select('code_value')->where('code_name','=','DEPARTMENT')->where('desc1','=','users.department'),
                Role::raw('group_concat(DISTINCT (roles.name) SEPARATOR ", ") as the_roles'),
                Role::raw('group_concat(DISTINCT (user_teams.team) SEPARATOR ", ") as the_teams')
            )
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('user_teams', 'users.id', '=', 'user_teams.user_id')
          
            ->groupBy('users.id');


			return datatables()->eloquent($query)
            ->editColumn('edit_user', function (User $user) {
                // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-primary edit-role-btn" data-toggle="modal" data-target="#edit_role_modal" data-id="'. $user->id .'" data-first_name="'. $user->first_name .'"
                data-last_name="'. $user->last_name .'" data-department="'. $user->department .'" data-teams="'. $user->the_teams .'" data-roles="'. $user->the_roles .'">
                <i class="fa-solid fa-user-pen"></i>
              </button>
             ';
            })
            ->editColumn('delete_user', function (User $user) {
                // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-user-btn" data-id="'. $user->id .'" data-first_name="'. $user->first_name .'"
                data-last_name="'. $user->last_name .'">
                <i class="fa-solid fa-trash-can"></i>
                </button>
             ';
            })

            
            ->editColumn('full_name', function (User $user) {
                
                return $user->first_name . " " . $user->last_name;
            })
            ->editColumn('department', function (User $user) {
                $pivot = Pivot::select('code_value')->where('code_name','=','DEPARTMENT')->where('desc1','=', $user->department)->first();
                return $pivot->code_value;
            })
            ->rawColumns(['edit_user','delete_user','full_name','department'])
            ->toJson();
		}
	}

    // public function loadRoles(Request $request){
    //     if ($request->ajax()) {
           
	// 		$query = User::select(
    //             'roles.id',
    //             'roles.name',
    //         )
    //         ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
    //         ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
	// 		->where('role_user.user_id', '=', $request->id)->get();

    //         return response()->json($query);
	// 	}
    // }loadrouserRole

    public function updateroles(Request $request){

        $newRoles = $request->rolenames;
        $newTeams = $request->teams;
        
        if(empty($newRoles) OR empty($newTeams)){
            return redirect()->back()->with('error', 'Role/Team cannot be empty' . $request->user_id . '.');
        }
        else{
            Permission::where(['user_id'=>$request->user_id])->delete();
            Team::where(['user_id'=>$request->user_id])->delete();


            foreach($newTeams as $team){
                Team::insert(
                   array(
                     'user_id' => $request->user_id,
                     'team' => $team
                   )
                );
            }

            foreach($newRoles as $role){
                Permission::insert(
                   array(
                     'user_id' => $request->user_id,
                     'role_id' => $role
                   )
                );
            }

            // User::where('id','=',$request->user_id)->update(['team' => $request->team]);
            //event(new Message(''));
            return redirect()->back()->with('success', 'User ID #' . $request->user_id . ' has been edited.');
        }
    }

    public function deleteUser($id){
        // $user = User::find($id);
        // $user->roles()->permissions()->where('user_id', '=', $id)->delete();
        // User::find(1)->permissions()->delete();
        $user = User::find($id);
        // delete related   
        $user->permissions()->delete();
        $user->delete();
        //event(new Message(''));
       return redirect()->back()->with('success', 'User ID #' . $id. ' has been deleted.');
    //   
    }

    public function getDrafters(){

        $user_teams = [];
     
      foreach (Auth::user()->teams as $team) {
        array_push($user_teams,$team->team);
      }
     

        $name = User::select(
            'users.id as value', 
            User::raw('CONCAT(users.first_name, " ", users.last_name) AS tag'))
            ->leftJoin('role_user','role_user.user_id','users.id')
            ->leftJoin('user_teams','users.id','user_teams.user_id')
            ->where('role_user.role_id','=',10)
            ->where('users.department','=',Auth::user()->department)
            ->whereIn('user_teams.team',function($query) use($user_teams){
                $query->select('team')->from('user_teams')->whereIn('team',$user_teams);
            })
            ->orderBy('users.first_name', 'ASC')->get();
            return $name;
      }
      
    //   public function getSchedulers(){
    //     $name = User::select(
    //         'users.id as value', 
    //         User::raw('CONCAT(users.first_name, " ", users.last_name) AS label'))
    //         ->leftJoin('role_user','role_user.user_id','users.id')
    //         ->where('role_user.role_id','=',20)
    //         ->where('users.department','=',Auth::user()->department)
    //         ->where('users.team','=',Auth::user()->team)
    //         ->orderBy('users.first_name', 'ASC')->get();
    //         return $name;
    //   }

    //   public function getUsersByTeam(Request $request){

    //     $users = User::select(
    //         'users.id as value', 
    //         User::raw('CONCAT(users.first_name, " ", users.last_name) AS label'))
    //         ->leftJoin('role_user','role_user.user_id','users.id')
    //         ->where('users.department','=',$request->department)
    //         ->where('users.team','=',$request->team)
    //         ->orderBy('users.first_name', 'ASC')
    //         ->groupBy('users.id')->get();
    //         return response()->json($users);
    //   }
      
    // public function getCheckers(){
    //     $name = User::select(
    //         'users.id as value', 
    //         User::raw('CONCAT(users.first_name, " ", users.last_name) AS label'))
    //         ->leftJoin('role_user','role_user.user_id','users.id')
    //         ->where('role_user.role_id','=',11)
    //         ->where('users.department','=',Auth::user()->department)
    //         ->where('users.team','=',Auth::user()->team)
    //         ->orderBy('users.first_name', 'ASC')->get();
        
    //         return $name;
    // }

    public function readActivities(Request $request){
    
        Activity::select('activities.status')
            ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            ->where('activities.department','=', Auth::user()->department)
            ->where('activities.team','=', Auth::user()->team)
            ->where('role_activities.user_id','=',Auth::user()->id)
            ->whereIn('role_activities.role',function($query){
                $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            })->update(['activities.status' => 1]);

        Activity::select('activities.description','activities.created_at','activities.status')
            ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            ->where('activities.user_id','=',0)
            ->update(['activities.status' => 1])
            ;

        Activity::select('activities.status')
            ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            ->where('activities.department','=', Auth::user()->department)
            ->where('activities.team','=', Auth::user()->team)
            ->where('activities.user_id' , '!=', Auth::user()->id)
            
            ->whereNull('role_activities.user_id')
            ->whereIn('role_activities.role',function($query){
                $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            })->update(['activities.status' => 1]);

            
            return 1;
        }

        public function getActivities(Request $request){
            $activities_by_id = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('role_activities.user_id','=',Auth::user()->id)
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->orderBy('created_at','DESC')
                ;

            $activities_global = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.user_id','=',0)
                ->orderBy('created_at','DESC')
                
                ;


            $activities = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('activities.user_id' , '!=', Auth::user()->id)
                
                ->whereNull('role_activities.user_id')
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->union($activities_by_id)->union($activities_global)
                ->orderBy('created_at','DESC')
                ->take(10)
                ->get();

                return $activities;
        }

        public function countActivities(Request $request){
            $activities_by_id_count = Activity::select('activities.description','activities.created_at','activities.status')
            ->leftJoin('role_activities','role_activities.activity_id','activities.id')
            ->where('activities.department','=', Auth::user()->department)
            ->where('activities.team','=', Auth::user()->team)
            ->where('role_activities.user_id','=',Auth::user()->id)
            ->where('activities.status','=', 0)
            ->whereIn('role_activities.role',function($query){
                $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
            })
            ->orderBy('created_at','DESC')
            ;

            $activities_global_count = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.user_id','=',0)
                ->where('activities.status','=', 0)
                ->orderBy('created_at','DESC')
                ;

            $activities_count = Activity::select('activities.description','activities.created_at','activities.status')
                ->leftJoin('role_activities','role_activities.activity_id','activities.id')
                ->where('activities.department','=', Auth::user()->department)
                ->where('activities.team','=', Auth::user()->team)
                ->where('activities.user_id' , '!=', Auth::user()->id)
                ->where('activities.status','=', 0)
                ->whereNull('role_activities.user_id')
                ->whereIn('role_activities.role',function($query){
                    $query->select('role_id')->from('role_user')->where('user_id','=',Auth::user()->id)->get();
                })
                ->union($activities_by_id_count)->union($activities_global_count)
                ->orderBy('created_at','DESC')
                ->get();

                return count($activities_count);
        }

}
