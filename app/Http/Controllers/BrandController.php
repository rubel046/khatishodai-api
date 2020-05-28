<?php

namespace App\Http\Controllers;

use App\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $resutls= Brand::paginate(PER_PAGE);
        $items=$resutls->items();
        $meta=[
            'per_page'=> $resutls->perPage(),
            'total_page'=> $resutls->lastPage(),
            'total_item'=> $resutls->total(),
            'current_page'=> $resutls->currentPage()
        ];
        return response()->json(['results' => $items,'meta'=>$meta], 200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'code' => 'required|string|unique:brands',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'sort_order' => 'required|numeric'
        ]);

        try {
            $brand = new Brand;
            $brand->name = $request->name;
            $brand->code = $request->code;
            $brand->sort_order = $request->sort_order;
            $brand->ip_address = $request->ip();
            $brand->save();

            if ($request->hasFile('image')) {
                $original_filename = $request->file('image')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/brands/';
                $image = $brand->id.'-' . time() . '.' . $file_ext;

                if ($request->file('image')->move($destination_path, $image)) {
                    $brand->image = '/upload/brands/' . $image;
                }
            } else {
                $brand->image ='';
            }
            $brand->save();

            return response()->json(['brands' => $brand, 'message' => SAVE_SUCCESS], 201);

        } catch (\Exception $e) {

            return response()->json(['message' => ERROR_MSG,], 409);
        }
    }


    public function show($id)
    {
        try {
            $brands = Brand::findOrFail($id);

            return response()->json(['brands' => $brands], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => NO_DATA], 404);
        }
    }


    public function search(Request $request)
    {
        $this->validate($request,['searchStr'=>'required|string']);
        try {
            $searchItem = $request->searchStr;
            $brand = Brand::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('code', 'LIKE', "%{$searchItem}%")
                ->get();
                
            if(!$brand->isEmpty()){
                return response()->json(['datas' => $brand,'message' => 'Result  with this query'], 200);
            }else{
                return response()->json(['datas' => $brand,'message' => 'No data found!'], 404);
            }


        } catch (\Exception $e) {

            return response()->json(['message' => 'Error found!'], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'code' => 'sometimes|required|string|unique:brands,code,' . $id,
            'image' => 'required||image|mimes:jpeg,png,jpg|max:512',
            'sort_order' => 'required|numeric',
            'status' => 'required|numeric'
        ]);

        try {
            $brands = Brand::findOrFail($id);
            $data = $request->except('image');
            $brands-> updated_by = 1;
            $brands-> updated_at = Carbon::now();
            $brands-> ip_address = $request->ip();

            if ($request->hasFile('image')) {
                $original_filename = $request->file('image')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/brands/';
                $image = $id.'-' . time() . '.' . $file_ext;

                if ($request->file('image')->move($destination_path, $image)) {
                    $filename = base_path().'/public'.$brands->image;
                    File::delete($filename);
                    $brands->image = '/upload/brands/' . $image;
                }
            } else {
                $brands->image = '';
            }
            $brands->save();

            return response()->json(['message' => UPDATE_SUCCESS], 200);
        } catch (\Exception $e) {
            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['error code'=>$errCode,'message' => $errMgs ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            Brand::findOrFail($id)->delete();
            return response()->json(['message' => 'Data deleted successfully'], 200);

        } catch (\Exception $e) {

            $errCode=$e->getCode();
            $errMgs=$e->getMessage();
            return response()->json(['error code'=>$errCode,'message' => $errMgs ], 500);
        }
    }

}