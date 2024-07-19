<?php

namespace App\Http\Resources\Api\v1\Pets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as Base;

class ResourceCollection extends Base
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            static::$wrap  => $this->collection,
            'total'        => $this->total(),
            'count'        => $this->count(),
            'per_page'     => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages'  => $this->lastPage(),
        ];
    }
}
