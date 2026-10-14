<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ApplyFrontendPreferences
{
    private const SUPPORTED_LOCALES = ['en', 'fil', 'ilo'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?: $request->session()->get('frontend_locale')
            ?: $request->cookie('fms_locale')
            ?: SettingsService::get('default_language', config('app.locale'));

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        $request->session()->put('frontend_locale', $locale);
        Cookie::queue('fms_locale', $locale, 60 * 24 * 365);

        return $next($request);
    }
}
