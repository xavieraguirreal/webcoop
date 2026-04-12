<?php

if (!function_exists('t')) {
    /**
     * Traduce un string con soporte para overrides regionales.
     * Carga el idioma base desde el JSON + aplica el override regional.
     * No depende de __() de Laravel para evitar conflictos con keys
     * que contienen punto (ej: "idioma.es_AR").
     *
     * Misma firma que la función t() de appULIB.
     */
    function t(string $key, string $fallback = ''): string
    {
        static $translations = null;
        static $loadedLocale = null;

        $regionalLocale = config('app.regional_locale', 'es_AR');

        if ($translations === null || $loadedLocale !== $regionalLocale) {
            $baseLang = app()->getLocale(); // es, en, pt, fr, it

            // 1. Cargar JSON base del idioma
            $baseFile = lang_path($baseLang . '.json');
            $translations = file_exists($baseFile)
                ? (json_decode(file_get_contents($baseFile), true) ?: [])
                : [];

            // 2. Si no es español, mergear español como fallback
            if ($baseLang !== 'es') {
                $esFallback = json_decode(file_get_contents(lang_path('es.json')), true) ?: [];
                $translations = array_merge($esFallback, $translations);
            }

            // 3. Mergear override regional si existe (es_CO, pt_PT, etc.)
            $overrideFile = lang_path($regionalLocale . '.json');
            if (file_exists($overrideFile) && $regionalLocale !== $baseLang) {
                $overrides = json_decode(file_get_contents($overrideFile), true) ?: [];
                $translations = array_merge($translations, $overrides);
            }

            $loadedLocale = $regionalLocale;
        }

        return $translations[$key] ?? ($fallback ?: $key);
    }
}
