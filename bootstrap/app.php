<?php

use App\Core\Middlewares\JsonMiddleware;
use App\Modules\User\Commands\UserCommands;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withCommands(
        ...array_merge([
            UserCommands::commands(),
        ]),
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            JsonMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
