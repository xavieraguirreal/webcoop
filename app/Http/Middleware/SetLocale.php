<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Detecta y aplica el idioma del usuario.
 * Prioridad: cookie → query param → browser Accept-Language → default.
 * Mismo approach que appULIB.
 */
class SetLocale
{
    private const COOKIE_NAME = 'coop_lang';
    private const COOKIE_TTL = 525600; // 1 año en minutos

    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('translatable.locales', ['es']);
        $default = $locales[0] ?? 'es';

        // 1. Query param ?lang=en (para cambiar idioma desde el switcher)
        $locale = $request->query('lang');
        if ($locale && in_array($locale, $locales)) {
            app()->setLocale($locale);
            $response = $next($request);
            // Setear cookie para recordar la elección
            return $response->withCookie(cookie(
                self::COOKIE_NAME, $locale, self::COOKIE_TTL, '/', null, false, false, false, 'Lax'
            ));
        }

        // 2. Cookie
        $locale = $request->cookie(self::COOKIE_NAME);
        if ($locale && in_array($locale, $locales)) {
            app()->setLocale($locale);
            return $next($request);
        }

        // 3. Browser Accept-Language
        $locale = $this->detectFromBrowser($request, $locales);
        if ($locale) {
            app()->setLocale($locale);
            $response = $next($request);
            return $response->withCookie(cookie(
                self::COOKIE_NAME, $locale, self::COOKIE_TTL, '/', null, false, false, false, 'Lax'
            ));
        }

        // 4. Default
        app()->setLocale($default);
        return $next($request);
    }

    private function detectFromBrowser(Request $request, array $locales): ?string
    {
        $accept = $request->header('Accept-Language', '');
        if (!$accept) return null;

        // Parsear Accept-Language: es-AR,es;q=0.9,en;q=0.8
        $langs = [];
        foreach (explode(',', $accept) as $part) {
            $parts = explode(';q=', trim($part));
            $lang = strtolower(trim($parts[0]));
            $q = isset($parts[1]) ? (float) $parts[1] : 1.0;
            // Extraer solo el código base (es de es-AR)
            $base = explode('-', $lang)[0];
            if (!isset($langs[$base]) || $langs[$base] < $q) {
                $langs[$base] = $q;
            }
        }

        arsort($langs);

        foreach ($langs as $lang => $q) {
            if (in_array($lang, $locales)) {
                return $lang;
            }
        }

        return null;
    }
}
