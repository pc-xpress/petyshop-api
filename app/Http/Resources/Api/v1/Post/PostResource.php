<?php

namespace App\Http\Resources\Api\v1\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'pet_id'            => $this->pet_id,
            'pet_name'          => $this->pet->name,
            'description'       => $this->description,
            'location'          => $this->location,
            'hide_like_view'    => $this->hide_like_view,
            'allow_commenting'  => $this->allow_commenting,
            'type'              => $this->type,
            'visibility'        => $this->visibility,
            'image'             => $this->image,
        ];
    }
}
