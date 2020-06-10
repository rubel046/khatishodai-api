<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyPhoto;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CompanyPhotosController extends Controller
{
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
        $data['photo'] = $this->uploadImage($request);

        return $this->model->create($data);
    }


    public function show($id)
    {
        return $this->model->show($id);
    }


    public function search(Request $request)
    {
        $this->validate($request,['searchStr'=>'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = CompanyPhoto::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
               //` ->orWhere('about_us', 'LIKE', "%{$searchItem}%")
                ->get();
                
            if(!$data->isEmpty()){
                return response()->json(['datas' => $data,'message' => DATA_FOUND], 200);
            }else{
                return response()->json(['datas' => $data,'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        $data['photo'] = $this->uploadImage($request);

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
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'status' =>  'required|numeric',
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