<?php

namespace App\Http\Controllers;

use App\Filters\CompanyFilter;
use App\Model\CompanyFactory;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CompanyFactoriesController extends Controller
{
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
        $this->validate($request, ['searchStr' => 'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = CompanyFactory::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                ->orWhere('location', 'LIKE', "%{$searchItem}%")
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
            'location' => 'required|string',
            'size_id' => 'required|numeric',
            'staff_number_id' => 'required|numeric',
            'rnd_staff_id' => 'required|numeric',
            'production_line_id' => 'required|numeric',
            'annual_output_id' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
    }

}