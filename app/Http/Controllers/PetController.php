<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Classes\ApiResponseHelper;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\Api\v1\Pets\PetResource;
use App\Http\Resources\Api\v1\Pets\PetCollection;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = auth()->user()->pets()->paginate();
        return ApiResponseHelper::sendResponse(
            new PetCollection($pets),
            true, // The success flag.
            'OK', // The success message.
            [], // The additional data.
            200 // The HTTP status code.
        );
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
        Gate::authorize('view', $pet);
        return ApiResponseHelper::sendResponse(
            ['pet' => PetResource::make($pet)], // The user resource to be returned.
            true, // The success flag.
            'OK', // The success message.
            [], // The additional data.
            200 // The HTTP status code.
        );
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
        Gate::authorize('delete', $pet);
        $pet->delete();
        return ApiResponseHelper::sendResponse(
            ['pet' => PetResource::make($pet)], // The user resource to be returned.
            true, // The success flag.
            'OK', // The success message.
            [], // The additional data.
            200 // The HTTP status code.
        );
    }
}
