<?php

namespace App\Http\Controllers;

use App\Model\Zone;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    private $model;

    public function __construct(Zone $model)
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

    public function geZone($city_id)
    {
        $data = Zone::where('city_id', $city_id)->first();
        return response()->json(['result' => $data], 200);
    }


    public function search(Request $request)
    {
        $this->validate($request, ['searchStr' => 'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = Zone::query()
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
            'city_id' => 'required|numeric',
            'name' => 'required|string',
            'zip_code' => 'required|string',
            'status' => 'numeric',
        ]);
    }

}