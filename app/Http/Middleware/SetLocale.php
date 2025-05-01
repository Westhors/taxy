<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->get('locale')
            ?? $request->header('Accept-Language')
            ?? $request->header('lang')
            ?? config('app.locale');

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}