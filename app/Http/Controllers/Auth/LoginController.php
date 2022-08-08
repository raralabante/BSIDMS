<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller

{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    // protected $redirectTo = "";
   
    protected function authenticated(Request $request, $user)
    {
      
        foreach (Auth::user()->permissions as $permission) {
            $role_name = \App\Models\Role::select('name')->where('id','=',$permission->role_id)->first()->name;
            error_log($role_name);
            if($role_name == "Administrator" OR $role_name == "Drafting Manager" ){

                return redirect()->route('dashboard');
            }
            if($role_name == "Drafting TL"){

                return redirect()->route('drafting_master');
            }
            else if($role_name == "Drafter" || $role_name == "Drafting Checker"){
                return redirect()->route('my_drafts');
            }
            else if($role_name == "Scheduler" || $role_name == "Scheduling Checker"){
                return redirect()->route('my_schedules');
            }
            else{
                return redirect()->route('dashboard');
            }
          
        }
       
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
