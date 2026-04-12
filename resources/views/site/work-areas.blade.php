@extends('layouts.app')

@section('title', t('Áreas de Trabajo') . ' — Cooperativa Liberté')

@section('content')

    <x-page-header :title="t('Áreas de Trabajo')" :breadcrumbs="[t('Áreas de Trabajo') => null]" />

    @php
        $groupMeta = [
            'talleres-productivos' => [
                'icon' => 'M11.42 15.17l-4.655-5.653a.61.61 0 010-.948l4.655-5.653a.61.61 0 01.949 0l4.655 5.653a.61.61 0 010 .948l-4.655 5.653a.61.61 0 01-.949 0zM21.75 12a9.75 9.75 0 11-19.5 0 9.75 9.75 0 0119.5 0z',
                'bg' => 'linear-gradient(135deg, #d97706, #92400e)',
            ],
            'produccion-agropecuaria' => [
                'icon' => 'M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z',
                'bg' => 'linear-gradient(135deg, #059669, #065f46)',
            ],
            'educacion-formacion' => [
                'icon' => 'M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
                'bg' => 'linear-gradient(135deg, #2563eb, #1e40af)',
            ],
            'bienestar-comunidad' => [
                'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
                'bg' => 'linear-gradient(135deg, #e11d48, #9f1239)',
            ],
        ];
    @endphp

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="max-w-6xl mx-auto space-y-20">
            @foreach(\App\Models\WorkArea::GROUPS as $groupKey => $groupLabel)
                @if(isset($grouped[$groupKey]) && $grouped[$groupKey]->count())
                    @php $meta = $groupMeta[$groupKey] ?? ['icon' => '', 'bg' => 'linear-gradient(135deg, var(--color-primary), var(--color-secondary))']; @endphp

                    <div id="{{ $groupKey }}" class="animar scroll-mt-24">
                        {{-- Group header --}}
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg"
                                 style="background: {{ $meta['bg'] }}">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl lg:text-3xl font-serif font-bold text-secondary">{{ t($groupLabel) }}</h2>
                                <p class="text-base-content/50 text-sm">{{ $grouped[$groupKey]->count() }} {{ t($grouped[$groupKey]->count() === 1 ? 'área' : 'áreas') }}</p>
                            </div>
                        </div>

                        {{-- Areas grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($grouped[$groupKey] as $area)
                                <a href="{{ route('work-area.show', $area->slug) }}"
                                   class="card card-accent-hover bg-base-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group animar">
                                    @if($area->featured_image)
                                        <figure class="h-44 overflow-hidden">
                                            <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                        </figure>
                                    @else
                                        <figure class="h-44 bg-base-300/50 flex items-center justify-center">
                                            <span class="text-4xl font-serif opacity-30">{{ mb_substr($area->name, 0, 1) }}</span>
                                        </figure>
                                    @endif
                                    <div class="card-body items-center text-center p-5">
                                        <h3 class="card-title font-serif text-secondary text-lg">{{ $area->name }}</h3>
                                        @if($area->short_description)
                                            <p class="text-base-content/60 text-sm line-clamp-2">{{ $area->short_description }}</p>
                                        @endif
                                        <span class="text-primary font-semibold text-sm mt-1">{{ t('Ver más') }} &rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

@endsection
