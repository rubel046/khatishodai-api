<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Repositories\Repository;
use Illuminate\Http\Request;

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


    public function store(Request $request)
    {
        $this->validation($request);
        return $this->model->create($request->all());
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
            $data = Company::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('website', 'LIKE', "%{$searchItem}%")
                ->get();
            if(!$data->isEmpty()){
                return response()->json(['data' => $data,'message' => DATA_FOUND], 200);
            }else{
                return response()->json(['data' => $data,'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            //dd($e);
            return response()->json(['message' => ERROR_MSG], 500);
        }

    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        return $this->model->update($request->all(), $id);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'name' => 'required|string' . $id ? '|unique:company_basic_infos,name, ' . $id : '',
            'website' => 'required|string' . $id ? '|unique:company_basic_infos,website, ' . $id : '',
            'user_id' => 'required|numeric',
            'display_name' => 'required|string',
            'establishment_date' => 'required|date',
            'office_space' => 'required|string',
            'operation_address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'cell' => 'required|string',
            'fax' => 'required|string',
            'number_of_employee' => 'required|numeric',
            'ownership_type' => 'required|numeric',
            'turnover_id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
    }

}