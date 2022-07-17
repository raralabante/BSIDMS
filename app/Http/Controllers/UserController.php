<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pivot;
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
    public function index()
    {
        $user_roles = Role::select(
			'name',
            'id')
			->orderBy('name', 'ASC')->get();

            
		return view('user.user', compact('user_roles'));
            
    }

    public function userList(Request $request) {
		if ($request->ajax()) {
           
            $sub = Pivot::select('code_value')->where('code_name','=','DEPARTMENT')->where('desc1','=','users.department');
			$query = User::select(
                'users.id',
				'users.first_name',
                'users.last_name',
                'users.email',
                'users.team',
                'users.department',
                // Pivot::select('code_value')->where('code_name','=','DEPARTMENT')->where('desc1','=','users.department'),
                Role::raw('group_concat(roles.name SEPARATOR ", ") as the_role')
            )
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->groupBy('users.id');


			return datatables()->eloquent($query)
            ->editColumn('edit_role', function (User $user) {
                // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-primary edit-role-btn btn-sm" data-toggle="modal" data-target="#edit_role_modal" data-id="'. $user->id .'" data-first_name="'. $user->first_name .'"
                data-last_name="'. $user->last_name .'">
                <i class="fa-solid fa-user-pen"></i>&nbsp;&nbsp;EDIT
              </button>
              <button class="btn btn-danger delete-user-btn btn-sm" data-id="'. $user->id .'" data-first_name="'. $user->first_name .'"
              data-last_name="'. $user->last_name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->editColumn('full_name', function (User $user) {
                
                return $user->first_name . " " . $user->last_name;
            })
            ->editColumn('department', function (User $user) {
                $pivot = Pivot::select('code_value')->where('code_name','=','DEPARTMENT')->where('desc1','=', $user->department)->first();
                return $pivot->code_value;
            })
            ->rawColumns(['edit_role','full_name','department'])
            ->toJson();
		}
	}

    public function loadRoles(Request $request){
        if ($request->ajax()) {
           
			$query = User::select(
                'roles.id',
                'roles.name',
            )
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
			->where('role_user.user_id', '=', $request->id)->get();

            return response()->json($query);
		}
    }

    public function updateroles(Request $request){
        // error_log(dd($request));
        $userRole = $request->input('rolename');

        if(empty($userRole)){
            return redirect()->back()->with('error', 'Error while editing User ID #' . $request->user_id . '.');
        }
        else{
            Permission::where(['user_id'=>$request->user_id])->delete();
            foreach($userRole as $role){
                Permission::insert(
                   array(
                     'user_id' => $request->user_id,
                     'role_id' => $role
                   )
                );
            }
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
       return redirect()->back()->with('success', 'User ID #' . $id. ' has been deleted.');
    //   
    }

    public function getUser(){
        $name = User::select(
          'id as value',
          User::raw('CONCAT(first_name, " ", last_name) AS tag'))
          ->orderBy('first_name', 'ASC')->get();
          return $name;
      }
    
    public function getCheckers(){
    $name = User::select(
       
        'id as value', 
        User::raw('CONCAT(first_name, " ", last_name) AS label'))
        ->orderBy('first_name', 'ASC')->get();
        return $name;
    }


    
}
