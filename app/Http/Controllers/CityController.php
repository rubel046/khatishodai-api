<?php

namespace App\Http\Controllers;

use App\Filters\RegionFilter;
use App\Model\City;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private $model;

    public function __construct(City $model, RegionFilter $regionFilter)
    {
        $this->middleware('auth');
        $this->model = new Repository($model, $regionFilter);
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
            'division_id' => 'required|numeric',
            'name' => 'required|string',
            'status' => 'numeric',
        ]);
    }

}
