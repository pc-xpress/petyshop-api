<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function Update(Request $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponseHelper::sendResponse([], true, 'OK', [], 200);
    }
}
