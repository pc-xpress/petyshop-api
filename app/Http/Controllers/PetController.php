<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Classes\ApiResponseHelper;
use App\Http\Resources\PetResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;

class PetController extends Controller
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
    public function store(StorePetRequest $request)
    {
        $pet = auth()->user()->pets()->create($request->validated());
        return ApiResponseHelper::sendResponse(
            ['pet' => PetResource::make($pet)], // The user resource to be returned.
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        Gate::authorize('update', $pet);
        $pet->update($request->validated());
        return ApiResponseHelper::sendResponse(
            ['pet' => PetResource::make($pet)], // The user resource to be returned.
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        //
    }
}
