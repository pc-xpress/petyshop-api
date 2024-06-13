<?php

namespace App\Http\Controllers\Api\v1\Register;

use App\Classes\ApiResponseHelper;
use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\CreateUserRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->all());

        return ApiResponseHelper::sendResponse(['user' => AuthResource::make($user)], true, 'OK', [], 200);

        // return jsonResponse(data: ['user' => AuthResource::make($user)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        //
    }
}
