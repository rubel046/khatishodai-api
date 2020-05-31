<?php


namespace App\Repositories;


use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Model;

class Repository implements RepositoryInterface
{
    use ApiResponse;

    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        try {
            return $this->showAll($this->model->all());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // Get paginate list of model
    public function paginate()
    {
        try {
            $resutl = $this->model->paginate(PER_PAGE);

            $meta = [
                'per_page' => $resutl->perPage(),
                'total_page' => $resutl->lastPage(),
                'total_item' => $resutl->total(),
                'current_page' => $resutl->currentPage()
            ];
            return $this->pagination(['result' => $resutl->items(), 'meta' => $meta]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    // create a new record in the database
    public function create(array $data)
    {
        try {
            $data['created_by'] = auth()->id();
            $data['ip_address'] = request()->ip();
            return $this->createdSuccess($this->model->create($data));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // update record in the database
    public function update(array $data, $id)
    {
        try {
            $record = $this->model->findOrFail($id);
            $data['updated_by'] = auth()->id();
            $data['ip_address'] = request()->ip();
            $record->update($data);
            return $this->updatedSuccess($record);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // remove record from the database
    public function delete($id)
    {
        try {
            $model = $this->model->findOrFail($id);
            return $this->deletedSuccess($model->delete());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // show the record with the given id
    public function show($id)
    {
        try {
            return $this->showOne($this->model->findOrFail($id));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    // Get the associated model
    public
    function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public
    function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }
}
