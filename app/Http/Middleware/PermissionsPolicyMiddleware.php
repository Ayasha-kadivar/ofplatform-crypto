<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionsPolicyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    //  public function handle($request, Closure $next)
    // {
    //     $response = $next($request);
    //     $response->headers->set('Permissions-Policy', "default-src 'self';");
    //     return $response;
    // }
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $permissionsPolicy = "camera=(), microphone=(), geolocation=(), fullscreen=()";
       $response->header('Permissions-Policy', $permissionsPolicy);

        return $response;
    }
}