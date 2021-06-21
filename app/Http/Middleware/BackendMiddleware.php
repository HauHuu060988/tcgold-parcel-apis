<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BackendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        echo "backend middleware";
        return $next($request);
    }
}
