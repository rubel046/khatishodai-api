<?php

namespace App\Http\Controllers;

use App\Attribute;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class AttributeController extends Controller
{
    private $model;

    public function __construct(Attribute $attribute)
    {
        $this->middleware('auth');
        $this->model = new Repository($attribute);
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
            $attributes = Attribute::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->get();
            if (!$attributes->isEmpty()) {
                return response()->json(['data' => $attributes, 'message' => 'Result with this query'], 200);
            } else {
                return response()->json(['data' => $attributes, 'message' => 'No data found!'], 404);
            }


        } catch (\Exception $e) {
            $errCode = $e->getCode();
            $errMgs = $e->getMessage();
            return response()->json(['error code' => $errCode, 'message' => $errMgs], 500);
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
            'name' => 'required|string|unique:attributes,name' . ($id ? ', ' . $id : ''),
            'display_name' => 'string|nullable',
            'slug' => 'string|unique:attributes,slug' . ($id ? ', ' . $id : ''),
            'type' => 'string|nullable',
            'is_filter_criteria' => 'boolean|nullable',
        ]);
    }
}