<?php

namespace App\Http\Controllers;

use App\Model\AttributeTerm;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class AttributeTermsController extends Controller
{
    private $model;

    public function __construct(AttributeTerm $attributeTerms)
    {
        $this->middleware('auth');
        $this->model = new Repository($attributeTerms);
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
            return response()->json(['error_code' => $errCode, 'message' => $errMgs], 500);
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
            'name' => 'required|string',
            'attribute_id' => 'required|numeric',
            'is_visible_on_product' => 'boolean|nullable',
            'status' => 'numeric|nullable',
        ]);
    }
}