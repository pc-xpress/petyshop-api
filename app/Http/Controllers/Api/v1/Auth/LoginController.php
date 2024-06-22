<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\StoreAuthRequest;

class LoginController extends Controller
{
    /**
     * Handle the user login request.
     *
     * @param StoreAuthRequest $request The request object containing user credentials.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the authentication token.
     */
    public function login(StoreAuthRequest $request)
    {
        // Get the email and password from the request.
        $credentials = request(['email', 'password']);

        // Attempt to authenticate the user with the given credentials.
        if (!$token = auth()->attempt($credentials)) {
            // If authentication fails, return an unauthorized response.
            return ApiResponseHelper::sendResponse([], false, 'Unauthorized', [], 401);
        }

        // Generate the token expiration time.
        $expiresIn = auth()->factory()->getTTL() * 60;

        // Return a successful response with the token and expiration time.
        return ApiResponseHelper::sendResponse(
            ['token' => $token, 'expires_in' => $expiresIn],
            true,
            'OK',
            [],
            200
        );
    }
}
