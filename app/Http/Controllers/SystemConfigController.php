<?php

namespace App\Http\Controllers;

use App\Model\SystemConfig;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemConfigController extends Controller
{
    private $model;

    public function __construct(SystemConfig $model)
    {
        //$this->middleware('auth');
        $this->model = new Repository($model);
    }

    public function index()
    {
        return Cache::rememberForever('system_config', function () {
            return $this->model->all();
        });
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
            'alias' => 'required|string|unique:system_configs,alias' . ($id ? ', ' . $id : ''),
            'purpose' => 'sometimes|string',
            'data' => 'required|string',
            'status' => 'numeric',
        ]);
    }

}
