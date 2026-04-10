{{-- Language switcher — dropdown con banderas --}}
@php
    $locales = config('translatable.locales', ['es']);
    $names = config('translatable.locale_names', []);
    $flags = config('translatable.locale_flags', []);
    $current = app()->getLocale();
@endphp

<div class="dropdown dropdown-end" x-data="{ open: false }">
    <button @click="open = !open" class="btn btn-ghost btn-sm gap-1 text-secondary-content">
        <span class="fi fi-{{ $flags[$current] ?? 'ar' }} rounded-sm" style="width:20px;height:14px;display:inline-block;background-size:cover;"></span>
        <span class="hidden sm:inline text-xs uppercase">{{ $current }}</span>
        <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <ul x-show="open" @click.away="open = false" x-cloak x-transition
        class="dropdown-content menu bg-base-100 rounded-box z-50 w-48 p-2 shadow-lg border border-base-300 mt-2">
        @foreach($locales as $locale)
            <li>
                <a href="{{ request()->fullUrlWithQuery(['lang' => $locale]) }}"
                   class="{{ $locale === $current ? 'active' : '' }} flex items-center gap-2">
                    <span class="fi fi-{{ $flags[$locale] ?? 'ar' }} rounded-sm" style="width:20px;height:14px;display:inline-block;background-size:cover;"></span>
                    {{ $names[$locale] ?? $locale }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
