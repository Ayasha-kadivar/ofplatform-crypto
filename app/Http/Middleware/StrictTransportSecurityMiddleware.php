<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StrictTransportSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // dd($response);
        $response->headers->set('Strict-Transport-Security', 'max-age=15768000; includeSubDomains; preload');

        return $response;
    }
}