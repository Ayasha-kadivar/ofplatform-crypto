<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateRedirectUrl
{
     private $allowedUrls = [
        // Replace with the allowed patterns for your application
        '/^https?:\/\/cryptofamilyuser\.com\//',
    ];

    public function handle(Request $request, Closure $next)
    {
        $url = $request->input('sitemap.xml');
// dd($url);
        if ($url && !$this->isAllowedUrl($url)) {
            return abort(400, 'Invalid URL');
        }

        return $next($request);
    }

    private function isAllowedUrl($url)
    {
        foreach ($this->allowedUrls as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

}