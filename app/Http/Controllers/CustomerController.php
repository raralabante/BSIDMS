<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use Error;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
		  return view('customer.customer');
    }

    protected function insert(Request $request)
    {
      // $request->validate([
      //   'customer_name' => 'required|max:2',
      // ]);
      
      $request->validate([
        'customer_name' => 'required|max:255',
        'team' => 'required|max:255'
      
      ]);

        $newCustomer = Customer::firstOrNew([
            'name' => $request->customer_name,
            'team' => $request->team,
        ]);

        if($newCustomer->id == null){
          $newCustomer->save();
          return redirect()->back()->with('success', $newCustomer->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $newCustomer->name . ' is already existing.');
        }
    }

    public function customerList(Request $request) {
      if ($request->ajax()) {
        $query = Customer::select(
                  'id',
                  'name',
                  'team')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_customer', function (Customer $customer) {
                return '<button class="btn btn-danger delete-customer-btn w-100" data-id="'. $customer->id .'" data-customer_name="'. $customer->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            
            ->rawColumns(['delete_customer'])
            ->toJson();
      }
    }

    public function deleteCustomer(Request $request){
     Customer::find($request->id)->delete();
    //   
    }

    public function getCustomers(){
      $name = Customer::select(
        'name')
        ->whereIn('team',function($query) {
          $query->select('team')->from('user_teams')->where('user_id',Auth::user()->id);
      })
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }
}
