<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;


class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'user/deposit',
        'ipn*'
    ];
    protected function addCookieToResponse($request, $response)
    {
        $config = config('session');

        $tokenValue = $this->getTokenFromRequest($request);
        $expireTime = $config['expire_on_close'] ? 0 : time() + 60 * $config['lifetime'];

        $response->headers->setCookie(new SymfonyCookie(
            'XSRF-TOKEN', $tokenValue, $expireTime, '/', null, true, true
        ));

        return $response;
    }
}