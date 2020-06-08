<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    private $model;

    public function __construct(Brand $brand)
    {
        $this->middleware('auth');
        $this->model = new Repository($brand);
    }

    public function index()
    {
        return $this->model->paginate();
    }

    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        $data['image'] = $this->uploadImage($request);

        return $this->model->create($data);
    }


    public function show($id)
    {
        return $this->model->show($id);
    }


    public function search(Request $request)
    {
        $this->validate($request, ['searchStr' => 'required|string']);
        try {
            $searchItem = $request->searchStr;
            $brand = Brand::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('code', 'LIKE', "%{$searchItem}%")
                ->get();

            if (!$brand->isEmpty()) {
                return response()->json(['datas' => $brand, 'message' => 'Result  with this query'], 200);
            } else {
                return response()->json(['datas' => $brand, 'message' => 'No data found!'], 404);
            }


        } catch (\Exception $e) {

            return response()->json(['message' => 'Error found!'], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        $data['image'] = $this->uploadImage($request);

        return $this->model->update($data, $id);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'code' => 'required|string|unique:brands,code' . ($id ? ', ' . $id : ''),
            'image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'sort_order' => 'required|numeric',
            'status' => 'required|numeric'
        ]);
    }

    private function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file_ext = $request->file('image')->clientExtension();
            $destination_path = base_path('public/upload/brands');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('image')->move($destination_path, $image)) {
                return '/upload/brands/' . $image;
            }
        }
        return null;
    }

}
