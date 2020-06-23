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
    use FileUpload;

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
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->saveImages($request, 'logo', 'company_details');
        }

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
        if ($request->hasFile('logo')) {
            $image = $this->saveImages($request, 'logo', 'company_details');
            $data['logo'] = $image;
        }
        return $this->model->update($data, $id);
    }

    public function companyDetailsCreateOrUpdate(Request $request, Company $company)
    {
        $this->validation($request,true);
        try {
            $company = $company->findOrFail($request->company_id);
            $data = $request->all();
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->saveImages($request, 'logo', 'company_details');
            }

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
            'logo' => $id? $request->hasFile('logo')? 'sometimes|image|mimes:jpeg,png,jpg|max:512':'string':'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'about_us' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'string',
            'youtube_link' => 'string',
            'fb_link' => 'string',
        ]);
    }

}
