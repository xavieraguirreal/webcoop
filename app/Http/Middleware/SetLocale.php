<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Detecta y aplica el idioma+región del usuario.
 * Cookie guarda locale completo (es_AR, pt_BR, etc.).
 * app()->setLocale() se setea con el idioma base (es, en, pt) para Spatie Translatable.
 * La variante regional completa se comparte via config('app.regional_locale').
 *
 * Prioridad: query param → cookie → browser Accept-Language → default.
 * Mismo approach que appULIB.
 */
class SetLocale
{
    private const COOKIE_NAME = 'coop_lang';
    private const COOKIE_TTL = 525600; // 1 año en minutos

    public function handle(Request $request, Closure $next): Response
    {
        $regionalLocales = config('translatable.regional_locales', []);
        $baseLangFallback = config('translatable.base_lang_fallback', []);

        // 1. Query param ?lang=es_AR (locale completo desde /idioma/)
        $locale = $request->query('lang');
        if ($locale && $this->isValidLocale($locale, $regionalLocales, $baseLangFallback)) {
            $locale = $this->normalizeLocale($locale, $regionalLocales, $baseLangFallback);
            $this->applyLocale($locale);
            $response = $next($request);
            return $response->withCookie(cookie(
                self::COOKIE_NAME, $locale, self::COOKIE_TTL, '/', null, false, false, false, 'Lax'
            ));
        }

        // 2. Cookie (ya tiene locale completo)
        $locale = $request->cookie(self::COOKIE_NAME);
        if ($locale && $this->isValidLocale($locale, $regionalLocales, $baseLangFallback)) {
            $this->applyLocale($locale);
            return $next($request);
        }

        // 3. Browser Accept-Language
        $locale = $this->detectFromBrowser($request, $regionalLocales, $baseLangFallback);
        if ($locale) {
            $this->applyLocale($locale);
            $response = $next($request);
            return $response->withCookie(cookie(
                self::COOKIE_NAME, $locale, self::COOKIE_TTL, '/', null, false, false, false, 'Lax'
            ));
        }

        // 4. Default
        $this->applyLocale('es_AR');
        return $next($request);
    }

    private function applyLocale(string $regionalLocale): void
    {
        $baseLang = substr($regionalLocale, 0, 2);
        // Idioma base para Spatie Translatable (contenido)
        app()->setLocale($baseLang);
        // Locale regional completo para strings UI
        config(['app.regional_locale' => $regionalLocale]);
    }

    private function isValidLocale(string $locale, array $regionalLocales, array $baseLangFallback): bool
    {
        $normalized = strtolower(str_replace('-', '_', $locale));
        return isset($regionalLocales[$normalized]) || isset($baseLangFallback[$normalized])
            || in_array($locale, $regionalLocales, true);
    }

    private function normalizeLocale(string $locale, array $regionalLocales, array $baseLangFallback): string
    {
        // Si ya es un locale válido completo (es_AR, pt_BR...)
        if (in_array($locale, $regionalLocales, true)) {
            return $locale;
        }

        $normalized = strtolower(str_replace('-', '_', $locale));

        // Mapeo directo (es_co → es_CO)
        if (isset($regionalLocales[$normalized])) {
            return $regionalLocales[$normalized];
        }

        // Solo idioma base (es → es_AR)
        if (isset($baseLangFallback[$normalized])) {
            return $baseLangFallback[$normalized];
        }

        return 'es_AR';
    }

    private function detectFromBrowser(Request $request, array $regionalLocales, array $baseLangFallback): ?string
    {
        $accept = $request->header('Accept-Language', '');
        if (!$accept) return null;

        // Parsear Accept-Language: es-CO,es;q=0.9,en;q=0.8
        $langs = [];
        foreach (explode(',', $accept) as $part) {
            $parts = explode(';q=', trim($part));
            $tag = trim($parts[0]);
            $q = isset($parts[1]) ? (float) $parts[1] : 1.0;
            $langs[$tag] = $q;
        }

        arsort($langs);

        foreach ($langs as $tag => $q) {
            $tagParts = preg_split('/[-_]/', $tag);

            // Intentar match completo primero (es-CO → es_CO)
            if (count($tagParts) >= 2) {
                $full = strtolower($tagParts[0]) . '_' . strtolower($tagParts[1]);
                if (isset($regionalLocales[$full])) {
                    return $regionalLocales[$full];
                }
            }

            // Fallback al idioma base (es → es_AR)
            $base = strtolower($tagParts[0]);
            if (isset($baseLangFallback[$base])) {
                return $baseLangFallback[$base];
            }
        }

        return null;
    }
}
