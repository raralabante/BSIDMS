<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Brand;
use Error;

class BrandController extends Controller
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
		  return view('brand.brand');
    }

    protected function insert(Request $request)
    {
        $newBrand = Brand::firstOrNew([
            'name' => $request->brand_name,
        ]);

        if($newBrand->id == null){
          $newBrand->save();
          return redirect()->back()->with('success', $newBrand->name . ' has been added.');
        }
        else{
          return redirect()->back()->with('error',  $newBrand->name . ' is already existing.');
        }
    }

    public function brandList(Request $request) {
      if ($request->ajax()) {
        $query = Brand::select(
                  'id',
                  'name')
                  ->orderBy('id','DESC');
        return datatables()->eloquent($query)
          ->editColumn('delete_brand', function (Brand $brand) {
            // return '<a href="#" class="view-summary" data-id="' . $joborder->id . '" data-company="' . $joborder->company_name . '" data-toggle="modal" data-target="#viewSummary">VIEW</a>';
                return '<button class="btn btn-danger delete-brand-btn w-100" data-id="'. $brand->id .'" data-brand_name="'. $brand->name .'">
              <i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;DELETE
              </button>';
            })
            ->rawColumns(['delete_brand'])
            ->toJson();
      }
    }

    public function deleteBrand(Request $request){
        Brand::find($request->id)->delete();
    //   
    }

    public function getBrands(){
      $name = Brand::select(
        'name')
        ->orderBy('name', 'ASC')->get();
        return response()->json($name);
    }

}
