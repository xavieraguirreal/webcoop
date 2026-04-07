@extends('layouts.app')

@section('title', 'Relatos - CooperativaLiberté')

@section('content')

    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">Relatos</h1>
        <p class="text-lg opacity-80">Historias escritas por los miembros de la cooperativa. Voces que transforman.</p>
    </div>

    <section class="bg-white py-16 px-[5%]">
        <div class="max-w-[800px] mx-auto space-y-6">
            @foreach($stories as $story)
                <a href="{{ route('stories.show', $story->slug) }}"
                   class="flex flex-col sm:flex-row items-stretch gap-6 bg-white p-6 border-l-[5px] border-primary-500 rounded-r-lg shadow-sm hover:translate-x-2 hover:shadow-md hover:border-accent-500 transition-all duration-200 animar">
                    <div class="text-left">
                        <div class="text-xs font-semibold text-gray-400 uppercase mb-1">
                            {{ $story->published_at?->format('d \\d\\e F, Y') }}
                            @if($story->author) | {{ $story->author }} @endif
                        </div>
                        <h3 class="text-primary-500 text-[1.3rem] font-bold mb-2">{{ $story->title }}</h3>
                        @if($story->excerpt)
                            <p class="text-gray-600 text-sm italic line-clamp-2">{{ $story->excerpt }}</p>
                        @endif
                        @if($story->tags->count())
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($story->tags as $tag)
                                    <span class="text-xs bg-accent-50 text-accent-700 px-3 py-1 rounded-md font-medium">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        @endif
                        <span class="text-accent-500 font-bold text-sm mt-3 inline-block">Leer relato completo →</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 max-w-[800px] mx-auto">
            {{ $stories->links() }}
        </div>
    </section>

@endsection
