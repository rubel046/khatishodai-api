<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyCertificate;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class CompanyCertificateController extends Controller
{
    use FileUpload;
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
        if ($request->hasFile('certificate_photo_name')) {
            $data['certificate_photo_name'] = $this->saveImages($request, 'certificate_photo_name', 'company_certificates');
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
        if ($request->hasFile('certificate_photo_name')) {
            $data['certificate_photo_name'] = $this->saveImages($request, 'certificate_photo_name', 'company_certificates');
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
            'name' => 'required|string',
            'reference_number' => 'required|string',
            'issued_by' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'certificate_photo_name' => $id? $request->hasFile('certificate_photo_name')? 'sometimes|image|mimes:jpeg,png,jpg|max:512':'string':'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'status' => 'numeric',
        ]);
    }

}
