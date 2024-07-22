<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Post;
use App\Classes\ApiResponseHelper;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\Api\v1\Post\PostResource;
use App\Http\Resources\Api\v1\Post\PostCollection;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Pet $pet)
    {
        Gate::authorize('viewPosts', $pet);
        $posts = $pet->posts()->paginate();
        return ApiResponseHelper::sendResponse(
            new PostCollection($posts),
            true, // The success flag.
            'OK', // The success message.
            [], // The additional data.
            200 // The HTTP status code.
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, Pet $pet)
    {
        $post = $pet->posts()->create($request->validated());
        return ApiResponseHelper::sendResponse(
            ['post' => PostResource::make($post)], // The user resource to be returned.
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet, Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
