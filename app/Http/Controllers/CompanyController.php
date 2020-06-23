<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Repositories\Repository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

class CompanyController extends Controller
{
    use ApiResponse;
    private $model;

    public function __construct(Company $company)
    {
        $this->middleware('auth');
        $this->model = new Repository($company);
    }

    public function index()
    {
        return $this->model->paginate();
    }


    public function store(Request $request, Company $company)
    {
        $this->validation($request);
        try {

            $datas = $request->all();
            $datas['user_id'] = auth()->user()->id;
            $datas['created_by'] = auth()->user()->id;
            $datas['ip_address'] = $request->ip();
            $companyData = $company->create($datas);

            $operationalAddress = $request->operational_address;
            $operationalAddress['address_type'] = 'operation';

            $registerAddress = $request->register_address;
            $registerAddress['address_type'] = 'register';
            $companyData->operationalAddress()->updateOrCreate([], $operationalAddress);
            $companyData->registerAddress()->updateOrCreate([], $registerAddress);
            $companyData->businessTypes()->sync($request->business_types);
            $companyData['operational_address'] = $operationalAddress;
            $companyData['register_address'] = $registerAddress;
            $companyData['business_types'] = $request->business_types;
            return $this->createdSuccess($companyData);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

    }


    public function show($id)
    {
        return $this->model->with(['operationalAddress', 'registerAddress', 'company_certificate', 'CompanyDetail', 'CompanyFactory', 'CompanyNearestPort', 'CompanyPhoto', 'CompanyProduct', 'CompanyTradeInfo', 'CompanyTradeMembership'])->find($id);
    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $operationalAddress = $request->operational_address;
        $operationalAddress['address_type'] = 'operation';

        $registerAddress = $request->register_address;
        $registerAddress['address_type'] = 'register';

        $companyData = Company::findOrFail($id);
        try {
            $companyData->operationalAddress()->updateOrCreate([], $operationalAddress);
            $companyData->registerAddress()->updateOrCreate([], $registerAddress);
            $companyData->businessTypes()->sync($request->business_types);

            $companyData['operational_address'] = $operationalAddress;
            $companyData['register_address'] = $registerAddress;
            $companyData['business_types'] = $request->business_types;

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
        $this->model->update($request->all(), $id);
        return $this->updatedSuccess($companyData);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'website' => 'string|unique:company_basic_infos,website' . ($id ? ', ' . $id : ''),
            'display_name' => 'required|string',
            'establishment_date' => 'required|date',
            'office_space' => 'string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cell' => 'string',
            'fax' => 'string',
            'number_of_employee' => 'required|numeric',
            'ownership_type' => 'required|numeric',
            'turnover_id' => 'numeric',
            'status' => 'numeric',
        ]);
    }

}