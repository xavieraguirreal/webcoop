<?php

return [
    /*
     * Idiomas base para el contenido traducible (Spatie Translatable).
     * El primero es el idioma por defecto (source of truth).
     */
    'locales' => ['es', 'en', 'pt', 'fr', 'it'],

    /*
     * Nombres de los idiomas base para el admin (Filament).
     */
    'locale_names' => [
        'es' => 'Español',
        'en' => 'English',
        'pt' => 'Português',
        'fr' => 'Français',
        'it' => 'Italiano',
    ],

    /*
     * Banderas para los idiomas base en Filament (ISO 3166-1 alpha-2).
     */
    'locale_flags' => [
        'es' => 'ar',
        'en' => 'gb',
        'pt' => 'br',
        'fr' => 'fr',
        'it' => 'it',
    ],

    /*
     |--------------------------------------------------------------------------
     | Variantes regionales (UI del sitio público)
     |--------------------------------------------------------------------------
     | Mismas variantes que appULIB. El contenido usa los 5 idiomas base,
     | pero la UI (strings estáticos) se adapta al regionalismo del visitante.
     */

    // Mapeo idioma+país -> locale (para Accept-Language parsing)
    'regional_locales' => [
        'es_ar' => 'es_AR', 'es_uy' => 'es_UY', 'es_co' => 'es_CO',
        'es_ec' => 'es_EC', 'es_pe' => 'es_PE', 'es_es' => 'es_ES',
        'es_mx' => 'es_MX', 'es_gt' => 'es_GT', 'es_hn' => 'es_HN',
        'es_sv' => 'es_SV', 'es_ni' => 'es_NI', 'es_cr' => 'es_CR',
        'es_pa' => 'es_PA', 'es_cu' => 'es_CU', 'es_do' => 'es_DO',
        'es_pr' => 'es_PR', 'es_cl' => 'es_CL', 'es_ve' => 'es_VE',
        'es_bo' => 'es_BO', 'es_py' => 'es_PY',
        'pt_br' => 'pt_BR', 'pt_pt' => 'pt_PT',
        'en_us' => 'en_US', 'en_ca' => 'en_CA', 'en_gy' => 'en_GY',
        'en_bz' => 'en_BZ', 'en_jm' => 'en_JM', 'en_tt' => 'en_TT',
        'en_bs' => 'en_BS', 'en_ag' => 'en_AG', 'en_bb' => 'en_BB',
        'en_dm' => 'en_DM', 'en_gd' => 'en_GD', 'en_kn' => 'en_KN',
        'en_vc' => 'en_VC', 'en_lc' => 'en_LC',
        'fr_ca' => 'fr_CA', 'fr_ht' => 'fr_HT',
        'nl_sr' => 'nl_SR', 'nl_aw' => 'nl_AW', 'nl_cw' => 'nl_CW',
    ],

    // Fallback por idioma base (cuando el browser solo manda "es" sin país)
    'base_lang_fallback' => [
        'es' => 'es_AR',
        'pt' => 'pt_BR',
        'en' => 'en_US',
        'fr' => 'fr_HT',
        'nl' => 'nl_SR',
        'it' => 'it_IT',
    ],

    // Mapeo locale regional -> datos del país (bandera + nombre)
    'locale_country_map' => [
        'es_AR' => ['code' => 'ar', 'name' => 'Argentina'],
        'es_UY' => ['code' => 'uy', 'name' => 'Uruguay'],
        'es_CO' => ['code' => 'co', 'name' => 'Colombia'],
        'es_EC' => ['code' => 'ec', 'name' => 'Ecuador'],
        'es_PE' => ['code' => 'pe', 'name' => 'Perú'],
        'es_ES' => ['code' => 'es', 'name' => 'España'],
        'es_MX' => ['code' => 'mx', 'name' => 'México'],
        'es_GT' => ['code' => 'gt', 'name' => 'Guatemala'],
        'es_HN' => ['code' => 'hn', 'name' => 'Honduras'],
        'es_SV' => ['code' => 'sv', 'name' => 'El Salvador'],
        'es_NI' => ['code' => 'ni', 'name' => 'Nicaragua'],
        'es_CR' => ['code' => 'cr', 'name' => 'Costa Rica'],
        'es_PA' => ['code' => 'pa', 'name' => 'Panamá'],
        'es_CU' => ['code' => 'cu', 'name' => 'Cuba'],
        'es_DO' => ['code' => 'do', 'name' => 'Rep. Dominicana'],
        'es_PR' => ['code' => 'pr', 'name' => 'Puerto Rico'],
        'es_CL' => ['code' => 'cl', 'name' => 'Chile'],
        'es_VE' => ['code' => 've', 'name' => 'Venezuela'],
        'es_BO' => ['code' => 'bo', 'name' => 'Bolivia'],
        'es_PY' => ['code' => 'py', 'name' => 'Paraguay'],
        'pt_BR' => ['code' => 'br', 'name' => 'Brasil'],
        'pt_PT' => ['code' => 'pt', 'name' => 'Portugal'],
        'en_US' => ['code' => 'us', 'name' => 'Estados Unidos'],
        'en_CA' => ['code' => 'ca', 'name' => 'Canadá'],
        'en_GY' => ['code' => 'gy', 'name' => 'Guyana'],
        'en_BZ' => ['code' => 'bz', 'name' => 'Belice'],
        'en_JM' => ['code' => 'jm', 'name' => 'Jamaica'],
        'en_TT' => ['code' => 'tt', 'name' => 'Trinidad y Tobago'],
        'en_BS' => ['code' => 'bs', 'name' => 'Bahamas'],
        'en_AG' => ['code' => 'ag', 'name' => 'Antigua y Barbuda'],
        'en_BB' => ['code' => 'bb', 'name' => 'Barbados'],
        'en_DM' => ['code' => 'dm', 'name' => 'Dominica'],
        'en_GD' => ['code' => 'gd', 'name' => 'Granada'],
        'en_KN' => ['code' => 'kn', 'name' => 'San Cristóbal y Nieves'],
        'en_VC' => ['code' => 'vc', 'name' => 'San Vicente y las Granadinas'],
        'en_LC' => ['code' => 'lc', 'name' => 'Santa Lucía'],
        'fr_HT' => ['code' => 'ht', 'name' => 'Haití'],
        'fr_CA' => ['code' => 'ca', 'name' => 'Canadá'],
        'nl_SR' => ['code' => 'sr', 'name' => 'Surinam'],
        'nl_AW' => ['code' => 'aw', 'name' => 'Aruba'],
        'nl_CW' => ['code' => 'cw', 'name' => 'Curaçao'],
        'it_IT' => ['code' => 'it', 'name' => 'Italia'],
    ],
];
