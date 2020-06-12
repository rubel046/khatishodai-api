<?php

namespace App\Http\Controllers;

use App\Model\ExportPercentageBreakdown;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class ExportPercentageBreakdownController extends Controller
{
    private $model;

    public function __construct(ExportPercentageBreakdown $model)
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
            'name' => 'required|string',
        ]);
    }

}