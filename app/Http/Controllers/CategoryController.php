<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Repositories\Repository;
use App\Traits\ApiResponse;
use App\Traits\FileUpload;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use FileUpload;
    use ApiResponse;

    private $model;

    public function __construct(Category $category)
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->model = new Repository($category);
    }

    public function index()
    {
        return $this->showAll(Category::whereNull('parent_id')->get());
    }


    public function store(Request $request)
    {
        $this->validation($request);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImages($request, 'image', 'category');
        }

        return $this->model->create($data);
    }


    public function show($id)
    {
        return $this->model->show($id);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveImages($request, 'image', 'category');
        }
        return $this->model->update($data, $id);
    }


    public function destroy($id)
    {
        return $this->model->delete($id);
    }

    private function validation(Request $request, $id = false)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:categories,name' . ($id ? ', ' . $id : ''),
            'parent_id' => 'numeric',
            'description' => 'string',
            'image' => $id? $request->hasFile('image')? 'sometimes|image|mimes:jpeg,png,jpg|max:512':'string':'sometimes|image|mimes:jpeg,png,jpg|max:512',
            'rank' => 'required|numeric'
        ]);
    }

}
