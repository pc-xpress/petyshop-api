<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\UpdateUserRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateUserRequest $request The request object containing the user's updated profile data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated user resource.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated());

        return ApiResponseHelper::sendResponse(
            ['user' => AuthResource::make($user->fresh())],
            true,
            'OK',
            [],
            200
        );
    }
}
