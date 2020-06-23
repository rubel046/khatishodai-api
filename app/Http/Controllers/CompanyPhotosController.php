<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyPhoto;
use App\Repositories\Repository;
use App\Traits\FileUpload;
use Illuminate\Http\Request;

class CompanyPhotosController extends Controller
{
    use FileUpload;
    private $model;

    public function __construct(CompanyPhoto $model, CompanyFilter $companyFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($model,$companyFilter);
    }

    public function index()
    {
        return $this->model->all();
    }


    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->saveImages($request, 'photo', 'company_photo');
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
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->saveImages($request, 'photo', 'company_photo');
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
            'company_id' => 'required|numeric',
            'photo' => $id? $request->hasFile('photo')? 'sometimes|image|mimes:jpeg,png,jpg|max:512':'string':'sometimes|image|mimes:jpeg,png,jpg|max:512',
        ]);
    }
    private function uploadImage(Request $request)
    {
        if ($request->hasFile('photo')) {
            $file_ext = $request->file('photo')->clientExtension();
            $destination_path = base_path('public/upload/company_photo');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('photo')->move($destination_path, $image)) {
                return '/upload/company_photo/' . $image;
            }
        }
        return null;
    }

}