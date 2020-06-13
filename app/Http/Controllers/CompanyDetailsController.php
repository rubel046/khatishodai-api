<?php

namespace App\Http\Controllers;

use App\Traits\FileUpload;
use App\Filters\CompanyFilter;
use App\Model\CompanyDetail;
use App\Repositories\Repository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Model\Company;


class CompanyDetailsController extends Controller
{
    use ApiResponse;

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

    public function detailsByCompany($company_id)
    {
        return $this->showOne(CompanyDetail::whereCompanyId($company_id)->first());
    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        $data['logo'] = $this->uploadImage($request);
        return $this->model->update($data, $id);
    }

    public function companyDetailsCreateOrUpdate(Request $request, Company $company)
    {
        $this->validation($request);
        try {
            $company = $company->findOrFail($request->company_id);
            $data = $request->all();
            $image = FileUpload::saveImages($request, 'logo', 'company_details');
            $data['logo'] = $image;
            $details = $company->CompanyDetail()->updateOrCreate(['company_id' => $request->company_id], $data);
            return $this->updatedSuccess($details);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

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
            'vision' => 'string',
            'youtube_link' => 'string',
            'fb_link' => 'string',
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
