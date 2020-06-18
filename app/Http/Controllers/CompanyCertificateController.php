<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyCertificate;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class CompanyCertificateController extends Controller
{
    private $model;

    public function __construct(CompanyCertificate $companyCertificate, CompanyFilter $companyFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($companyCertificate, $companyFilter);
    }

    public function index()
    {
        return $this->model->all();
    }


    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        $data['certificate_photo_name'] = $this->uploadImage($request);

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
            $data = CompanyCertificate::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('reference_number', 'LIKE', "%{$searchItem}%")
                ->get();

            if (!$data->isEmpty()) {
                return response()->json(['datas' => $data, 'message' => DATA_FOUND], 200);
            } else {
                return response()->json(['datas' => $data, 'message' => NO_DATA], 404);
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
        $data['certificate_photo_name'] = $this->uploadImage($request);

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
            'name' => 'required|string',
            'reference_number' => 'required|string',
            'issued_by' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'certificate_photo_name' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'status' => 'numeric',
        ]);
    }

    private function uploadImage(Request $request)
    {
        if ($request->hasFile('certificate_photo_name')) {
            $file_ext = $request->file('certificate_photo_name')->clientExtension();
            $destination_path = base_path('public/upload/company_certificates');
            $image = uniqid() . '-' . time() . '.' . $file_ext;

            if ($request->file('certificate_photo_name')->move($destination_path, $image)) {
                return '/upload/company_certificates/' . $image;
            }
        }
        return null;
    }

}
