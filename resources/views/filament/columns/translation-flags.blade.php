@php
    $flags = config('translatable.locale_flags', []);
    $localeNames = config('translatable.locale_names', []);
    $locales = config('translatable.locales', []);
    $record = $getRecord();
@endphp

<div class="flex items-center gap-1.5">
    @foreach ($locales as $locale)
        @php
            $title = $record->getTranslation('title', $locale, false);
            $flag = $flags[$locale] ?? $locale;
            $name = $localeNames[$locale] ?? $locale;
        @endphp
        @if (!empty($title))
            <img
                src="https://flagcdn.com/w40/{{ $flag }}.png"
                srcset="https://flagcdn.com/w80/{{ $flag }}.png 2x"
                width="24"
                height="16"
                alt="{{ $name }}"
                title="{{ $name }}"
                style="border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,.3);"
            >
        @else
            <img
                src="https://flagcdn.com/w40/{{ $flag }}.png"
                srcset="https://flagcdn.com/w80/{{ $flag }}.png 2x"
                width="24"
                height="16"
                alt="{{ $name }}"
                title="{{ $name }} (sin traducir)"
                style="border-radius: 2px; opacity: 0.2; filter: grayscale(100%);"
            >
        @endif
    @endforeach
</div>
