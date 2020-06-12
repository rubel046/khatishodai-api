<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyDetail;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CompanyDetailsController extends Controller
{
    private $model;

    public function __construct(CompanyDetail $model, CompanyFilter $companyFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($model, $companyFilter);
    }

    public function index()
    {
        return $this->model->all();
    }


    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        $data['logo'] = $this->uploadImage($request);

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
            $data = CompanyDetail::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                ->orWhere('about_us', 'LIKE', "%{$searchItem}%")
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
        $data['logo'] = $this->uploadImage($request);

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
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'about_us' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'youtube_link' => 'required|string',
            'fb_link' => 'required|string',
        ]);
    }

    private function uploadImage(Request $request)
    {
        if ($request->hasFile('logo')) {
            $file_ext = $request->file('logo')->clientExtension();
            $destination_path = base_path('public/upload/company_details');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('logo')->move($destination_path, $image)) {
                return '/upload/company_details/' . $image;
            }
        }
        return null;
    }

}