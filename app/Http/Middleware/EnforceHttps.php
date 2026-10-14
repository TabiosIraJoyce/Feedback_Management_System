<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isSecure() || app()->environment('local', 'testing')) {
            return $next($request);
        }

        return redirect()->secure($request->getRequestUri());
    }
}
