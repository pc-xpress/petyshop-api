<?php

namespace App\Http\Resources\Api\v1\Pets;

use Illuminate\Http\Request;
use App\Http\Resources\Api\v1\Pets\ResourceCollection;

class PetCollection extends ResourceCollection
{
    public static $wrap = 'pets';
}
