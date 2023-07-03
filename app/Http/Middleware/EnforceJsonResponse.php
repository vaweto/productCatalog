<?php

namespace App\Http\Middleware;

use Closure;

class EnforceJsonResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response;
    }
}
