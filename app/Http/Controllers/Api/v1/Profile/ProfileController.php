<?php

namespace App\Http\Controllers\Api\v1\Profile;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\UpdateUserRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;
use App\Models\User;

class ProfileController extends Controller
{
    public function Update(UpdateUserRequest $request, User $user)
    {
        auth()->user()->update($request->validated());
        $user = AuthResource::make(auth()->user()->fresh());
        return ApiResponseHelper::sendResponse(['user' => AuthResource::make($user)], true, 'OK', [], 200);
    }
}
