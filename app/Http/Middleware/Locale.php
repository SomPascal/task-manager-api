<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rawLocale = $request->headers->get('X-Locale', $request->getPreferredLanguage());
        $preferredLocale = config('app.locale');

        if (! is_string($rawLocale)) {
            
            app()->setLocale($preferredLocale);
            return $next($request);
        }
        
        $rawLocaleSplitted = preg_split('/(\-|\_)+/', $rawLocale);
        
        if ($rawLocaleSplitted === false) {
            
            app()->setLocale($preferredLocale);
            return $next($request);
        }
        
        if (! in_array($rawLocaleSplitted[0] ?? null, config('app.supported_locales'))) {

            app()->setLocale($preferredLocale);
            return $next($request);
        }

        app()->setLocale($rawLocaleSplitted[0] ?? $preferredLocale);
        return $next($request);
    }
}
