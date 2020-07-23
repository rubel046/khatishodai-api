<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respondWithToken($token, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user(),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 600,
            'message' => 'You have logged in successfully.'
        ], $code);
    }
}
