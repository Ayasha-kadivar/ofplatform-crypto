<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;

class HtmlPurifierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $input = $request->all();
        array_walk_recursive($input, function(&$input) use ($purifier) {
            $input = $purifier->purify($input);
        });
        $request->merge($input);

        return $next($request);
    }
}