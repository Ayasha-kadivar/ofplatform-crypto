<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class VerifyRedirects
{
    public function handle($request, Closure $next)
    {
        $this->redirectTo = $request->url();

        $this->except = [
            'login',
            'logout',
        ];

        // Set the maximum number of redirects allowed
        $maxRedirects = 5;
        $countRedirects = 10;

        do {
            $response = $next($request);
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 300 && $statusCode < 400) {
                $countRedirects++;

                if ($countRedirects > $maxRedirects) {
                    throw new \Exception('Too many redirects');
                }

                // Get the location header of the redirect response
                $location = $response->headers->get('Location');

                // Make sure the location header is not an external URL
               if (!Str::startsWith($location, url('/'))) {
    throw new \Exception('Invalid redirect');
}

                $request = $request->create($location, 'GET');
            }
        } while ($statusCode >= 300 && $statusCode < 400);

        return $response;
    }
}