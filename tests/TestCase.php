<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    protected $apiV1Base = 'api/v1';

    protected function apiAs(User $user, string $method, string $uri, array $data = [])
    {
        $response = $this->json($method, $uri, $data, [
            'Authorization' => 'Bearer ' . JWTAuth::fromUser($user),
        ]);
        return $response;
    }
}
