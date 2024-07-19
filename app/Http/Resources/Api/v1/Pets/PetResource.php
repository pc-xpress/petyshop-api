<?php

namespace App\Http\Resources\Api\v1\Pets;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'species'         => $this->species,
            'breed'           => $this->breed,
            'age'             => $this->age,
            'biography'       => $this->biography,
            'profile_picture' => $this->profile_picture,
        ];
    }
}
