<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $model;

    public function __construct(Category $category)
    {
        $this->middleware('auth');
        $this->model = new Repository($category);
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
        $this->validate($request,['searchStr'=>'required|string']);
        try {
            $searchItem = $request->searchStr;
            $data = Category::query()
                ->where('name', 'LIKE', "%{$searchItem}%")
                ->get();
            if(!$data->isEmpty()){
                return response()->json(['data' => $data,'message' => 'Result  with this query'], 200);
            }else{
                return response()->json(['data' => $data,'message' => 'No data found!'], 404);
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
            'name' => 'required|string|unique:categories,name' . ($id ? ', ' . $id : ''),
            'parent_id' => 'required|numeric',
            'rank' => 'required|string'
        ]);
    }

}