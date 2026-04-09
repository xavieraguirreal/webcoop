@extends('layouts.app')

@section('title', 'Áreas de Trabajo — Cooperativa Liberté')

@section('content')

    {{-- Page header --}}
    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold mb-3">Áreas de Trabajo</h1>
        <p class="text-lg opacity-80 max-w-xl mx-auto">Producción profesional con compromiso cooperativo</p>
    </section>

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($areas as $area)
                <a href="{{ route('work-area.show', $area->slug) }}"
                   class="card bg-base-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group animar">
                    @if($area->featured_image)
                        <figure class="h-52 overflow-hidden">
                            <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </figure>
                    @else
                        <figure class="h-52 bg-gradient-to-br from-primary/10 to-primary/20 flex items-center justify-center">
                            <span class="text-primary/30 text-7xl font-serif">{{ mb_substr($area->name, 0, 1) }}</span>
                        </figure>
                    @endif
                    <div class="card-body items-center text-center p-6">
                        <h3 class="card-title font-serif text-secondary text-xl">{{ $area->name }}</h3>
                        @if($area->short_description)
                            <p class="text-base-content/60 text-sm">{{ $area->short_description }}</p>
                        @endif
                        <span class="text-primary font-semibold text-sm mt-2">Ver más &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

@endsection
