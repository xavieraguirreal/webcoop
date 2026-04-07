@extends('layouts.app')

@section('title', $story->title . ' - Relatos - CooperativaLiberté')

@section('content')

    <section class="py-16 px-[5%]">
        <a href="{{ route('stories.index') }}" class="inline-block mb-8 border-2 border-accent-500 text-accent-500 font-bold px-6 py-2 rounded-md hover:bg-accent-500 hover:text-white transition">&larr; Volver a Relatos</a>

        <div class="max-w-[800px] mx-auto bg-white p-8 lg:p-12 rounded-lg shadow-sm">
            @if($story->featured_image)
                <img src="{{ asset('storage/' . $story->featured_image) }}" alt="{{ $story->title }}" class="w-full h-[350px] object-cover rounded-lg mb-8">
            @endif

            <div class="text-xs font-semibold text-gray-400 uppercase mb-3">
                {{ $story->published_at?->format('d \\d\\e F, Y') }}
                @if($story->author) | {{ $story->author }} @endif
            </div>

            <h2 class="text-primary-500 text-[2.2rem] font-bold mb-4 leading-tight">{{ $story->title }}</h2>

            @if($story->tags->count())
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($story->tags as $tag)
                        <span class="text-xs bg-accent-50 text-accent-700 px-3 py-1 rounded-md font-medium">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <div class="prose prose-lg max-w-none prose-headings:text-primary-500 prose-headings:font-bold text-lg leading-relaxed">
                {!! $story->body !!}
            </div>
        </div>
    </section>

@endsection
