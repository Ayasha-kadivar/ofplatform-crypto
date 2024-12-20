<?php

namespace App\Http\Middleware;

use Closure;

class ContentSecurityPolicy
{
    public function handle($request, Closure $next)
    {
        // $response = $next($request);

        // // $response->header('Content-Security-Policy', "default-src 'self'");

        // return $response;
        $response = $next($request);

        // $csp = "default-src 'self'; " .
        //     "script-src 'self' ajax.googleapis.com cdn.jsdelivr.net code.jquery.com www.google.com www.gstatic.com testing.phplaravel-1026205-3620236.cloudwaysapps.com dev.phplaravel-1026205-3620236.cloudwaysapps.com phplaravel-1026205-3620236.cloudwaysapps.com 'nonce-{{NONCE}}'; " .
        //     "style-src 'self' fonts.googleapis.com 'nonce-{{NONCE}}'; " .
        //     "font-src 'self' fonts.gstatic.com fonts.googleapis.com data:; " .
        //     "img-src 'self' data:; " .
        //     "manifest-src 'self';";

        // // Replace {{NONCE}} with the actual nonce value
        // $nonce = csp_nonce();
        // $csp = str_replace('{{NONCE}}', $nonce, $csp);

        // $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}