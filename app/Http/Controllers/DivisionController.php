<?php

namespace App\Http\Controllers;

use App\Filters\RegionFilter;
use App\Model\Division;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    private $model;

    public function __construct(Division $division, RegionFilter $regionFilter)
    {
        $this->middleware('auth');

        $this->model = new Repository($division, $regionFilter);
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
            'country_id' => 'required|numeric',
            'name' => 'required|string',
        ]);
    }

}
