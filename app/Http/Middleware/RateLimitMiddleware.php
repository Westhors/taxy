<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 5)
    {
        $keyPrefix = $request->route()->getName() ?? $request->path();

        $keyPrefix = str_replace('/', '_', $keyPrefix);

        $key = "rate_limit:{$keyPrefix}:" . $request->ip();

        $decaySeconds = 60;
        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }

        Cache::put($key, $attempts + 1, now()->addSeconds($decaySeconds));

        return $next($request);
    }
}