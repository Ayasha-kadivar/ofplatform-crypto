<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
   public function handle(Request $request, Closure $next)
{
    $uri = $request->getRequestUri();
    // dd($uri);

    // Remove any XSLT-like patterns from the URL
    $sanitizedUri = preg_replace('/%3C(xsl[a-zA-Z0-9%:]*)%3E/', '', $uri);

    if ($uri !== $sanitizedUri) {
        return redirect($sanitizedUri);
    }

    return $next($request);
}

}