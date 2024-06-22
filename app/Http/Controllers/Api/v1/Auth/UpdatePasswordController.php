<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param UpdatePasswordRequest $request The request object containing the user's new password.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success of the password update.
     */
    public function Update(UpdatePasswordRequest $request)
    {
        // Update the user's password with the new value provided in the request.
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Return a successful response indicating the password was updated successfully.
        return ApiResponseHelper::sendResponse([], true, 'OK', [], 200);
    }
}
