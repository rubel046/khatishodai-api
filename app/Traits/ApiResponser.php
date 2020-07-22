<?php

namespace App\Traits;

trait ApiResponser
{
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

  protected function errorResponse($message = 'Something went wrong', $errors = [], $code = 500)
  {
    return response()->json(
        [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code
        ]);
  }

  protected function response($data, $message = null)
  {
    $isSuccess = false;

    if ($data && !is_array($data)) {
      $isSuccess = true;

    }elseif (is_array($data) && count($data) > 0) {
      $isSuccess = true;
    }else {
      $message = 'No records found!';
    }

    return response()->json([
        'success' => $isSuccess,
        'message' => $message,
        'data' => $data
    ]);
  }
}
