<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\CreateUserRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;

class RegisterController extends Controller
{

    /**
     * Store a newly created user in storage.
     *
     * @param CreateUserRequest $request The request object containing the user's data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created user resource.
     */
    public function store(CreateUserRequest $request)
    {
        // Create a new user with the data from the request.
        $user = User::create($request->all());

        // Return a successful response with the created user resource.
        return ApiResponseHelper::sendResponse(
            ['user' => AuthResource::make($user)], // The user resource to be returned.
            true, // The success flag.
            'OK', // The success message.
            [], // The additional data.
            200 // The HTTP status code.
        );
    }
}
