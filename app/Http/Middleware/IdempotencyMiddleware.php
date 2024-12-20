<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;
class IdempotencyMiddleware
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
        if (in_array($request->method(), ['PATCH', 'POST'])) {
            $idempotencyKey = $request->header('Idempotency-Key');

            if (!$idempotencyKey) {
                $idempotencyKey = Uuid::uuid4()->toString();
                $request->headers->set('Idempotency-Key', $idempotencyKey);
            }

            $cacheKey = 'idempotency_key:' . $idempotencyKey;

            if (Cache::has($cacheKey)) {
                $cachedData = Cache::get($cacheKey);
                return response($cachedData['content'], $cachedData['status']);
            }

            $response = $next($request);

            $responseData = [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
            ];

            Cache::put($cacheKey, $responseData, now()->addMinutes(5));

            return $response;
        }

        return $next($request);
    }
}