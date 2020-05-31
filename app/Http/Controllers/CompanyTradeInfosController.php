<?php

namespace App\Http\Controllers;

use App\Model\CompanyTradeInfo;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CompanyTradeInfosController extends Controller
{
    private $model;

    public function __construct(CompanyTradeInfo $model)
    {
        $this->middleware('auth');
        $this->model = new Repository($model);
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
            $data = CompanyTradeInfo::query()
                ->where('company_id', 'LIKE', "%{$searchItem}%")
                //->orWhere('annual_revenue_id', 'LIKE', "%{$searchItem}%")
                ->get();
                
            if(!$data->isEmpty()){
                return response()->json(['datas' => $data,'message' => DATA_FOUND], 200);
            }else{
                return response()->json(['datas' => $data,'message' => NO_DATA], 404);
            }


        } catch (\Exception $e) {
            $errMgs = $e->getMessage();
            return response()->json(['message' => $errMgs], 500);
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
            'annual_revenue_id' => 'required|numeric',
            'export_percent_id' => 'required|numeric',
            'status' => 'required|numeric'
        ]);
    }

}