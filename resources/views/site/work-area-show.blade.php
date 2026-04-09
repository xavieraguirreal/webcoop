@extends('layouts.app')

@section('title', $area->name . ' — Áreas de Trabajo — Cooperativa Liberté')

@section('content')

    {{-- Page header --}}
    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold mb-3">{{ $area->name }}</h1>
        @if($area->short_description)
            <p class="text-lg opacity-80 max-w-xl mx-auto">{{ $area->short_description }}</p>
        @endif
    </section>

    <article class="py-12 px-6 lg:px-8 bg-base-100">
        <div class="max-w-3xl mx-auto">

            <a href="{{ route('work-areas') }}" class="btn btn-ghost btn-sm mb-8 gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Volver a Áreas
            </a>

            @if($area->featured_image)
                <figure class="rounded-box overflow-hidden mb-8 shadow-md">
                    <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}"
                         class="w-full h-auto max-h-[400px] object-cover">
                </figure>
            @endif

            <div class="prose prose-lg max-w-none
                        prose-headings:font-serif prose-headings:text-secondary prose-headings:font-bold
                        prose-a:text-primary hover:prose-a:underline
                        text-base-content/80 leading-relaxed">
                {!! $area->description !!}
            </div>
        </div>
    </article>

@endsection
