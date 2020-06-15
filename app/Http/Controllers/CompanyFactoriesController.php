<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyFactory;
use App\Repositories\Repository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CompanyFactoriesController extends Controller
{
    use ApiResponse;
    private $model;

    public function __construct(CompanyFactory $model, CompanyFilter $companyFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($model, $companyFilter);
    }

    public function index()
    {
        return $this->model->all();
    }


    public function store(Request $request,CompanyFactory $companyFactory)
    {
        $this->validation($request);
        $data = $request->except('address');
        //dd($request->address);
        $data['created_by'] = auth()->user()->id;
        $data['ip_address'] = $request->ip();
        $companyFactoryData = $companyFactory->create($data);
        $companyFactoryData->address()->create($request->address);
        $companyFactoryData['address']=$request->address;
        return $this->createdSuccess($companyFactoryData);

    }


    public function show($id)
    {
        return $this->model->with(['address'])->find($id);
    }


    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $companyFactory=CompanyFactory::findOrFail($id);
        $companyFactory->address()->updateOrCreate(['addressable_id' => $id, 'addressable_type' => CompanyFactory::class], $request->address);
        $this->model->update($request->all(), $id);
        return $this->updatedSuccess($request->except('_method'));
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'company_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'staff_number_id' => 'required|numeric',
            'rnd_staff_id' => 'required|numeric',
            'production_line_id' => 'required|numeric',
            'annual_output_id' => 'required|numeric',
        ]);
    }

}