<?php

namespace App\Http\Controllers;

use App\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeGroupController extends Controller
{
    private $model;

    public function __construct(AttributeGroup $attributeGroup)
    {
        // $this->middleware('auth');
        // $this->model = new Repository($attributeGroup);
    }

    public function index()
    {
        return $this->model->paginate();
    }


    public function store(Request $request)
    {
        // $this->validation($request);
        $input = $request->all();
        $data = new AttributeGroup;
        // dd($input["name"][0]);
        for($i=0; $i<=count($input["name"]); $i++){
            $data->name = $input["name"][$i];
            $data->created_by = Auth::id();
            $data->ip_address = request()->ip();
            $data->save();
        // dd($data);
        }
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
            $attrGroups = AttributeGroup::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->get();
            if (!$attrGroups->isEmpty()) {
                return response()->json(['data' => $attrGroups, 'message' => 'Result with this query'], 200);
            } else {
                return response()->json(['data' => $attrGroups, 'message' => 'No data found!'], 404);
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
            'name' => 'required|string|unique:attribute_groups,name' . ($id ? ', ' . $id : ''),
            'status' => 'numeric'
        ]);
    }

}