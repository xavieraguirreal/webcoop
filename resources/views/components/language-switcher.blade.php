{{-- Language switcher — muestra bandera del país actual y linkea a /idioma/ --}}
@php
    $regionalLocale = config('app.regional_locale', 'es_AR');
    $countryMap = config('translatable.locale_country_map', []);
    $country = $countryMap[$regionalLocale] ?? ['code' => 'ar', 'name' => 'Argentina'];
@endphp

<a href="{{ route('idioma') }}"
   class="btn btn-ghost btn-sm gap-1.5 text-secondary-content"
   title="{{ t('idioma.titulo') }}">
    <span class="fi fi-{{ $country['code'] }} rounded-sm" style="width:20px;height:14px;display:inline-block;background-size:cover;"></span>
    <span class="hidden sm:inline text-xs">{{ $country['name'] }}</span>
</a>
