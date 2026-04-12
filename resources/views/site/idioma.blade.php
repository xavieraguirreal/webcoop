@extends('layouts.app')

@section('title', t('idioma.titulo') . ' — Cooperativa Liberté')

@section('content')

@php
    $regionalLocale = config('app.regional_locale', 'es_AR');
    $countryMap = config('translatable.locale_country_map', []);
@endphp

<x-page-header :title="t('idioma.titulo')" :subtitle="t('idioma.subtitulo')" />

<section class="py-16 px-6 lg:px-8 bg-base-100">
    <div class="max-w-5xl mx-auto">

        {{-- Sudamérica --}}
        <h2 class="text-2xl font-serif font-bold mb-6 text-secondary animar">
            <svg class="w-6 h-6 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ t('idioma.sudamerica') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-12 animar">
            @foreach(['es_AR', 'es_BO', 'pt_BR', 'es_CL', 'es_CO', 'es_EC', 'en_GY', 'es_PY', 'es_PE', 'nl_SR', 'es_UY', 'es_VE'] as $loc)
                <x-idioma-btn :locale="$loc" :current="$regionalLocale" />
            @endforeach
        </div>

        {{-- Norteamérica --}}
        <h2 class="text-2xl font-serif font-bold mb-6 text-secondary animar">
            <svg class="w-6 h-6 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ t('idioma.norteamerica') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-12 animar">
            @foreach(['en_CA', 'fr_CA', 'en_US', 'es_MX'] as $loc)
                <x-idioma-btn :locale="$loc" :current="$regionalLocale" />
            @endforeach
        </div>

        {{-- Centroamérica y Caribe --}}
        <h2 class="text-2xl font-serif font-bold mb-6 text-secondary animar">
            <svg class="w-6 h-6 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ t('idioma.centroamerica') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-12 animar">
            @foreach(['en_AG', 'nl_AW', 'en_BS', 'en_BB', 'en_BZ', 'es_CR', 'es_CU', 'nl_CW', 'en_DM', 'es_SV', 'en_GD', 'es_GT', 'fr_HT', 'es_HN', 'en_JM', 'es_NI', 'es_PA', 'es_PR', 'es_DO', 'en_KN', 'en_LC', 'en_VC', 'en_TT'] as $loc)
                <x-idioma-btn :locale="$loc" :current="$regionalLocale" />
            @endforeach
        </div>

        {{-- Europa --}}
        <h2 class="text-2xl font-serif font-bold mb-6 text-secondary animar">
            <svg class="w-6 h-6 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ t('idioma.europa') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-12 animar">
            @foreach(['es_ES', 'it_IT', 'pt_PT'] as $loc)
                <x-idioma-btn :locale="$loc" :current="$regionalLocale" />
            @endforeach
        </div>

    </div>
</section>

@push('scripts')
<script>
document.querySelectorAll('.idioma-card').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var locale = this.getAttribute('data-locale');
        document.cookie = 'coop_lang=' + locale + '; path=/; max-age=31536000; SameSite=Lax';
        var referrer = document.referrer;
        var origin = window.location.origin;
        if (referrer && referrer.indexOf(origin) === 0 && referrer.indexOf('/idioma') === -1) {
            window.location.href = referrer;
        } else {
            window.location.href = '/';
        }
    });
});
</script>
@endpush

@endsection
