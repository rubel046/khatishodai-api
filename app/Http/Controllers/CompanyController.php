<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

class CompanyController extends Controller
{
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
            $datas=$request->all();
            $datas['created_by']=auth()->user()->id;
            $datas['ip_address']=$request->ip();
            $companyData= $company->create($datas);
            $operationalAddress = $request->operational_address;
            $operationalAddress['addressable_id'] = $companyData->id;
            $operationalAddress['addressable_type'] = Company::class;
            $operationalAddress['address_type'] = 'operation';

            $registerAddress = $request->register_address;
            $registerAddress['addressable_id'] =  $companyData->id;;
            $registerAddress['addressable_type'] = Company::class;
            $registerAddress['address_type'] = 'register';

            $company->operationalAddress()->updateOrCreate(['addressable_type' => Company::class], $operationalAddress);
            $company->registerAddress()->updateOrCreate(['addressable_type' => Company::class, 'address_type' => 'register'], $registerAddress);
            $companyData['operational_address']=$operationalAddress;
            $companyData['register_address']=$registerAddress;
            return response()->json(['result' => $companyData, 'message' => SAVE_SUCCESS], 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }



    }


    public function show($id)
    {
        return $this->model->with(['operationalAddress', 'registerAddress','company_certificate','CompanyDetail','CompanyFactory','CompanyNearestPort','CompanyPhoto','CompanyProduct','CompanyTradeInfo','CompanyTradeMembership'])->find($id);
    }


    public function search(Request $request)
    {
        $this->validate($request, ['searchStr' => 'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = Company::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('website', 'LIKE', "%{$searchItem}%")
                ->get();
            if (!$data->isEmpty()) {
                return response()->json(['data' => $data, 'message' => DATA_FOUND], 200);
            } else {
                return response()->json(['data' => $data, 'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            //dd($e);
            return response()->json(['message' => ERROR_MSG], 500);
        }

    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $operationalAddress = $request->operational_address;
        $operationalAddress['addressable_id'] = $id;
        $operationalAddress['addressable_type'] = Company::class;
        $operationalAddress['address_type'] = 'operation';

        $registerAddress = $request->register_address;
        $registerAddress['addressable_id'] = $id;
        $registerAddress['addressable_type'] = Company::class;
        $registerAddress['address_type'] = 'register';

        $company = Company::findOrFail($id);
        try{
            $company->operationalAddress()->update($operationalAddress);
            $company->registerAddress()->update($registerAddress);
        }catch (\Exception $e){
            dd($e->getMessage());
        }
        $data=$request->except('operational_address','register_address');
        // $company->registerAddress()->save([ 'addressable_type' => Company::class, 'address_type' => 'register'],$registerAddress);
        return $this->model->update($data,$id);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'website' => 'required|string|unique:company_basic_infos,website' . ($id ? ', ' . $id : ''),
            'user_id' => 'required|numeric',
            'display_name' => 'required|string',
            'establishment_date' => 'required|date',
            'office_space' => 'string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cell' => 'required|string',
            'fax' => 'required|string',
            'number_of_employee' => 'required|numeric',
            'ownership_type' => 'required|numeric',
            'turnover_id' => 'required|numeric',
            'status' => 'numeric',
        ]);
    }

}