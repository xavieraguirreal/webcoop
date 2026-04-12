@props(['locale', 'current'])

@php
    $countryMap = config('translatable.locale_country_map', []);
    $country = $countryMap[$locale] ?? ['code' => 'ar', 'name' => $locale];
    $isActive = $current === $locale;
@endphp

<button class="idioma-card group relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all duration-200 cursor-pointer
    {{ $isActive
        ? 'border-primary bg-primary/10 shadow-md'
        : 'border-base-300 bg-base-100 hover:border-primary/50 hover:shadow-sm' }}"
    data-locale="{{ $locale }}">

    <span class="fi fi-{{ $country['code'] }} rounded-sm shadow-sm"
          style="width:36px; height:24px; display:inline-block; background-size:cover;"></span>

    <span class="text-xs font-medium text-center leading-tight {{ $isActive ? 'text-primary font-bold' : 'text-base-content/70' }}">
        {{ t('idioma.' . $locale) }}
    </span>

    @if($isActive)
        <span class="absolute top-1.5 right-1.5 text-primary">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
    @endif
</button>
