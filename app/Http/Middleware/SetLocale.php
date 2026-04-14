<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'ar'];

        if ($request->is('api/*')) {
            $locale = $request->header('Accept-Language', config('app.locale'));
        } else {
            $locale = session('locale', config('app.locale'));
        }

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.fallback_locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
