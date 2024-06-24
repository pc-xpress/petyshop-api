<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? ApiResponseHelper::sendResponse([], true, 'OK', [], 200)
            : ApiResponseHelper::sendResponse([], false, 'Unauthorized', [], 401);
    }
}
