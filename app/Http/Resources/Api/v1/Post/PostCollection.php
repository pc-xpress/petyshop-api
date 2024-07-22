<?php

namespace App\Http\Resources\Api\v1\Post;

use Illuminate\Http\Request;
use App\Http\Resources\Api\v1\Pets\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public static $wrap = 'posts';
}
