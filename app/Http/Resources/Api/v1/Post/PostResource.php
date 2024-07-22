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
            'id' => $this->id,
            'pet_id' => $this->pet_id,
            'pet_name' => $this->pet->name, // Asumiendo que la relación con la mascota está definida
            'content' => $this->content,
            'image' => $this->image,
        ];
    }
}
