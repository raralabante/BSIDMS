<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Prestart;
use Error;
use App\Events\Message;
class PrestartController extends Controller
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
		  return view('prestart.prestart');
    }

    protected function insert(Request $request)
    {
        $new = Prestart::firstOrNew([
            'name' => $request->prestart_name,
        ]);

        if($new->id == null){
          $new->save();
          return redirect()->back()->with('success', $new->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $new->name . ' is already existing.');
        }
    }

    public function preStartList(Request $request) {
      if ($request->ajax()) {
        $query = Prestart::select(
                  'id',
                  'name')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_prestart', function (Prestart $prestart) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-prestart-btn w-100" data-id="'. $prestart->id .'" data-prestart_name="'. $prestart->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->rawColumns(['delete_prestart'])
            ->toJson();
      }
    }

    public function deletePrestart(Request $request){
        Prestart::find($request->id)->delete();
    //   
    }

    public function getPrestarts(){
      $name = Prestart::select(
        'name')
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }
}
