<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Http\Request;
use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\StoreAuthRequest;

class LoginController extends Controller
{
    public function login(StoreAuthRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return ApiResponseHelper::sendResponse([], false, 'Unauthorized', [], 401);
        }


        return ApiResponseHelper::sendResponse(['token' => $token, 'expires_in' => auth()->factory()->getTTL() * 60], true, 'OK', [], 200);
    }
}
