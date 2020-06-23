<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Repositories\Repository;
use App\Traits\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    use FileUpload;
    private $model;

    public function __construct(Brand $brand)
    {
        $this->middleware('auth');
        $this->model = new Repository($brand);
    }

    public function index()
    {
        return $this->model->all();
    }

    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImages($request, 'image', 'brands');
        }

        return $this->model->create($data);
    }


    public function show($id)
    {
        return $this->model->show($id);
    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImages($request, 'image', 'brands');
        }

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
            'image' => $id? $request->hasFile('image')? 'sometimes|image|mimes:jpeg,png,jpg|max:512':'string':'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'sort_order' => 'numeric',
            'status' => 'numeric'
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
