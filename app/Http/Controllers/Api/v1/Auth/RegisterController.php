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

    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->all());

        return ApiResponseHelper::sendResponse(['user' => AuthResource::make($user)], true, 'OK', [], 200);
    }
}
