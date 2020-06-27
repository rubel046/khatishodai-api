<?php

namespace App\Http\Controllers;

use App\Model\AttributeGroupAssignedTerm;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Repositories\Repository;

class AttrGroupAssignedTermsController extends Controller
{
    use ApiResponse;
    private $model;

    public function __construct(AttributeGroupAssignedTerm $attributeTerms)
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
        $productsData = [];
        $attributeIds = is_array($request->attribute_id) ? array_filter($request->attribute_id) : [];
        if (!empty($attributeIds)) {
            foreach ($attributeIds as $key => $value) {
                $productsData[] = [
                    'attribute_group_id' => $request->attribute_group_id,
                    'attribute_id' => $value,
                    'attribute_term_ids' => $request->attribute_term_ids[$key],
                    'is_variation_maker' => $request->is_variation_maker,
                    'status' => $request->status,
                    'created_by' => auth()->id(),
                    'ip_address' => request()->ip()
                ];
            }
        }

        AttributeGroupAssignedTerm::insert($productsData);
        return $this->createdSuccess($request->all());
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
        $this->validationUpdate($request, $id);
        return $this->model->update($request->all(), $id);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'attribute_group_id' => 'required|numeric',
            'attribute_id' => 'required|array|min:1',
            'attribute_id.*' => 'required|numeric',
            'attribute_term_ids' => 'required|array|min:1',
            'attribute_term_ids.*' => 'required|string',
            'is_variation_maker' => 'required|boolean',
            'status' => 'numeric|nullable',
        ]);
    }

    private function validationUpdate(Request $request, $id = false)
    {
        $this->validate($request, [
            'attribute_group_id' => 'required|numeric',
            'attribute_id' => 'required|numeric',
            'attribute_term_ids' => 'required|string',
            'is_variation_maker' => 'boolean',
            'status' => 'numeric',
        ]);
    }
}