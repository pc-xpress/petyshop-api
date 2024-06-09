<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return ApiResponseHelper::sendResponse([], 'Unauthorized', [], 401);
        }


        return ApiResponseHelper::sendResponse(['token' => $token, 'expires_in' => auth()->factory()->getTTL() * 60], 'OK', [], 200);
    }
}
