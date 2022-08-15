<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Categories;
use Error;

class CategoriesController extends Controller
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
		  return view('categories.categories');
    }

    protected function insert(Request $request)
    {
        $newcategories = Categories::firstOrNew([
            'name' => $request->categories_name,
        ]);

        if($newcategories->id == null){
          $newcategories->save();

          return redirect()->back()->with('success', $newcategories->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $newcategories->name . ' is already existing.');
        }
    }

    public function categoriesList(Request $request) {
      if ($request->ajax()) {
        $query = Categories::select(
                  'id',
                  'name')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_categories', function (Categories $categories) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-categories-btn w-100" data-id="'. $categories->id .'" data-categories_name="'. $categories->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->rawColumns(['delete_categories'])
            ->toJson();
      }
    }

    public function deleteCategories(Request $request){

        Categories::find($request->id)->delete();
    //   
    }

    public function getCategories(){
      $name = Categories::select(
        'name')
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }

}
