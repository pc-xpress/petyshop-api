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
    public function index(Pet $pet)
    {
        Gate::authorize('viewPosts', $pet);
        $posts = $pet->posts()->paginate();
        return ApiResponseHelper::sendResponse(
            new PostCollection($posts),
            true,
            'OK',
            [],
            200
        );
    }

    public function publicPosts()
    {
        $posts = Post::where('visibility', 'public')->paginate();
        return ApiResponseHelper::sendResponse(
            new PostCollection($posts),
            true,
            'OK',
            [],
            200
        );
    }

    public function store(StorePostRequest $request, Pet $pet, Post $post)
    {

        Gate::authorize('viewPosts', $pet);
        $post = $pet->posts()->create($request->validated());
        return ApiResponseHelper::sendResponse(
            ['post' => PostResource::make($post)], // The user resource to be returned.
        );
    }

    public function show(Pet $pet, Post $post)
    {
        Gate::authorize('viewPosts', $pet);
        return ApiResponseHelper::sendResponse(
            ['post' => PostResource::make($post)], // The user resource to be returned.
            true,
            'OK',
            [],
            200
        );
    }

    public function update(UpdatePostRequest $request, Pet $pet, Post $post)
    {
        Gate::authorize('viewPosts', $pet);;
        $post->update($request->validated());
        return ApiResponseHelper::sendResponse(
            ['post' => PostResource::make($post->fresh())], // The user resource to be returned.
            true,
            'OK',
            [],
            200
        );
    }

    public function destroy(Pet $pet, Post $post)
    {
        Gate::authorize('viewPosts', $pet);
        $post->delete();
        return ApiResponseHelper::sendResponse(
            [],
            true,
            'OK',
            [],
            200
        );
    }
}
