<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyProduct;
use App\Repositories\Repository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CompanyProductsController extends Controller
{
    use ApiResponse;
    private $model;

    public function __construct(CompanyProduct $model, CompanyFilter $companyFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($model, $companyFilter);
    }

    public function index()
    {
        return $this->model->paginate();
    }


    public function store(Request $request)
    {
        $this->validation($request);
        $productsData = [];
        $mainProducts = is_array($request->main_products) ? array_filter($request->main_products) : [];
        $otherProducts = is_array($request->other_products) ? array_filter($request->other_products) : [];
        if (!empty($mainProducts)) {
            foreach ($mainProducts as $key => $value) {
                $productsData[] = [
                    'company_id' => $request->company_id,
                    'name' => $value,
                    'is_main' => 1,
                    'created_by' => auth()->id(),
                    'ip_address' => request()->ip()
                ];
            }
        }

        if (!empty($otherProducts)) {
            foreach ($otherProducts as $key => $value) {
                $productsData[] = [
                    'company_id' => $request->company_id,
                    'name' => $value,
                    'is_main' => 0,
                    'created_by' => auth()->id(),
                    'ip_address' => request()->ip()
                ];
            }
        }

        CompanyProduct::insert($productsData);
        return $this->createdSuccess($request->all());
    }


    public function show($id)
    {
        return $this->model->show($id);
    }

    public function companyProductDetails($company_id)
    {
        $data = CompanyProduct::whereCompanyId($company_id)->get();
        $productsData = [
            'company_id' => $company_id,
        ];
        foreach ($data as $value) {
            if ($value->is_main == 1) {
                $productsData['main_products'][] = $value->name;
            } else {
                $productsData['other_products'][] = $value->name;
            }
        }
        return $this->showMessage($productsData);
    }

    public function companyProductsCreateOrUpdate(Request $request, CompanyProduct $companyProduct)
    {
        $this->validation($request);
        try {

            // CompanyProduct::where('company_id',$request->company_id)->delete();
            CompanyProduct::where('company_id', $request->company_id)->forceDelete();
            $productsData = [];
            $mainProducts = is_array($request->main_products) ? array_filter($request->main_products) : [];
            $otherProducts = is_array($request->other_products) ? array_filter($request->other_products) : [];

            if (!empty($mainProducts)) {
                foreach ($mainProducts as $key => $value) {
                    $productsData[] = [
                        'company_id' => $request->company_id,
                        'name' => $value,
                        'is_main' => 1,
                        'created_by' => auth()->id(),
                        'ip_address' => request()->ip()
                    ];
                }
            }

            if (!empty($otherProducts)) {
                foreach ($otherProducts as $key => $value) {
                    $productsData[] = [
                        'company_id' => $request->company_id,
                        'name' => $value,
                        'is_main' => 0,
                        'created_by' => auth()->id(),
                        'ip_address' => request()->ip()
                    ];
                }
            }


            CompanyProduct::insert($productsData);
            return $this->updatedSuccess($request->all());

            //$data=CompanyProduct::whereCompanyId($request->company_id)->get();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
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
            'company_id' => 'required|numeric',
            "main_products" => "required_without:other_products|array|min:1",
            "main_products.*" => "required|string",
            "other_products" => "required_without:main_products|min:1",
            "other_products.*" => "required|string",
        ]);
    }

}