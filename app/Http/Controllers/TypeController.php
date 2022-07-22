<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Type;
use Error;
use App\Events\Message;
class TypeController extends Controller
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
		  return view('type.type');
    }

    protected function insert(Request $request)
    {
        $newtype = Type::firstOrNew([
            'name' => $request->type_name,
        ]);

        if($newtype->id == null){
          $newtype->save();
          event(new Message(''));
          return redirect()->back()->with('success', $newtype->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $newtype->name . ' is already existing.');
        }
    }

    public function typeList(Request $request) {
      if ($request->ajax()) {
        $query = Type::select(
                  'id',
                  'name')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_type', function (Type $type) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-type-btn w-100" data-id="'. $type->id .'" data-type_name="'. $type->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->rawColumns(['delete_type'])
            ->toJson();
      }
    }

    public function deleteType(Request $request){
      event(new Message(''));
        type::find($request->id)->delete();
    //   
    }

    public function getTypes(){
      $name = Type::select(
        'name')
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }
}
