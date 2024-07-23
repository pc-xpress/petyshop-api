<?php

use App\Classes\ApiResponseHelper;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ForseJsonResponseMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            ForseJsonResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $throwable) {
            return ApiResponseHelper::sendResponse(
                [],
                false,
                'Validation errors.',
                $throwable->errors(),
                422,
            );
        });

        $exceptions->render(function (AuthenticationException $throwable) {
            return ApiResponseHelper::sendResponse(
                [],
                false,
                'Unauthenticated.',
                [$throwable->getMessage()],
                401,
            );
        });
    })->create();
