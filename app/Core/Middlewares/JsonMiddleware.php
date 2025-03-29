<?php

namespace App\Core\Middlewares;

use Closure;
use Illuminate\Http\Request;

final readonly class JsonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
