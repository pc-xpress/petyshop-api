<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
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

        $sent = $status === Password::RESET_LINK_SENT;

        return ApiResponseHelper::sendResponse(
            [],
            true,
            $sent ? 'OK' : 'Error',
            [],
            $sent ? 200 : 500
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        $message = match ($status) {
            Password::INVALID_USER => 'Invalid email.',
            Password::INVALID_TOKEN => 'Invalid token.',
            default => 'OK',
        };

        return ApiResponseHelper::sendResponse(
            [],
            Password::PASSWORD_RESET ? true : false,
            $message,
            [],
            $status === Password::PASSWORD_RESET ? 200 : 500
        );
    }
}
