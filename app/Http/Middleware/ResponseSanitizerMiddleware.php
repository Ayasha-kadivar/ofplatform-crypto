<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ResponseSanitizerMiddleware
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
        
        $content = $response->getContent();
        $content = str_replace('__cf_chl_f_tk=', '', $content);
        $response->setContent($content);
        
        return $response;
    }

}