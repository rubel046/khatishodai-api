<?php

namespace App\Http\Controllers;

use App\Model\Country;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    private $model;

    public function __construct(Country $model)
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->model = new Repository($model);
    }

    public function index()
    {
        $this->middleware('guest');
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
            $data = Country::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->orWhere('code', 'LIKE', "%{$searchItem}%")
                ->get();

            if (!$data->isEmpty()) {
                return response()->json(['datas' => $data, 'message' => 'Result  with this query'], 200);
            } else {
                return response()->json(['datas' => $data, 'message' => 'No data found!'], 404);
            }


        } catch (\Exception $e) {

            return response()->json(['message' => 'Error found!'], 500);
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
            'code' => 'required|string|unique:countries,code' . ($id ? ', ' . $id : ''),
            'name' => 'required|string|unique:countries,name' . ($id ? ', ' . $id : ''),
            'status' => 'numeric'
        ]);
    }

}
