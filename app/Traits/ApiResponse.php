<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponse
{
    private function apiResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->count() > 0) {
            return $this->apiResponse(['result' => $collection], $code);
        }

        return response('', 204)->setStatusCode(204, 'No data exists');
    }

    protected function pagination($data, $code = 200)
    {
        return $this->apiResponse($data, $code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        return $this->apiResponse(['result' => $model], $code);
    }

    protected function errorResponse($message = 'Something went wrong', $errors = [], $code = 400)
    {
        return $this->apiResponse([
            'success' => false,
            'message' => $message,
            'errors' => $message,
            'code' => $code
        ], $code);
    }

    protected function successResponse($message, $data = null, $code = 200)
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data,
                'code' => $code
            ]);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->apiResponse(['result' => $message], $code);
    }

    public function notifyMessage($data, $message = 'Success', $code = 200)
    {
        return $this->apiResponse(['result' => $data, 'message' => $message], $code);
    }

    public function createdSuccess($data, $message = SAVE_SUCCESS, $code = 201)
    {
        return $this->notifyMessage($data, $message, $code);
    }

    public function updatedSuccess($data, $message = UPDATE_SUCCESS, $code = 200)
    {
        return $this->notifyMessage($data, $message, $code);
    }

    public function deletedSuccess($data, $message = DELETE_SUCCESS, $code = 200)
    {
        return $this->notifyMessage($data, $message, $code);
    }

}
