@extends('layouts.app')

@section('title', $area->name . ' - Áreas de trabajo - CooperativaLiberté')

@section('content')

    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">{{ $area->name }}</h1>
        @if($area->short_description)
            <p class="text-lg opacity-80">{{ $area->short_description }}</p>
        @endif
    </div>

    <section class="py-16 px-[5%]">
        <a href="{{ route('work-areas') }}" class="inline-block mb-8 border-2 border-accent-500 text-accent-500 font-bold px-6 py-2 rounded-md hover:bg-accent-500 hover:text-white transition">&larr; Volver a Áreas</a>

        <div class="max-w-[800px] mx-auto bg-white p-8 lg:p-12 rounded-lg shadow-sm">
            @if($area->featured_image)
                <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}" class="w-full h-[350px] object-cover rounded-lg mb-8">
            @endif

            <div class="prose prose-lg max-w-none prose-headings:text-primary-500 prose-headings:font-bold text-lg leading-relaxed">
                {!! $area->description !!}
            </div>
        </div>
    </section>

@endsection
