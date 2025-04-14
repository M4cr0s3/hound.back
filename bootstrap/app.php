<?php

use App\Core\Middlewares\JsonMiddleware;
use App\Modules\Healthcheck\Commands\HealthCheckCommands;
use App\Modules\User\Commands\UserCommands;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands(
        [
            ...UserCommands::commands(),
            ...HealthCheckCommands::commands(),
        ],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            JsonMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
