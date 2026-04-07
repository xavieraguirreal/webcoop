@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' - CooperativaLiberté')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')

    <section class="py-16 px-[5%]">
        <a href="{{ route('news.index') }}" class="inline-block mb-8 border-2 border-accent-500 text-accent-500 font-bold px-6 py-2 rounded-md hover:bg-accent-500 hover:text-white transition">&larr; Volver a Noticias</a>

        <div class="max-w-[800px] mx-auto bg-white p-8 lg:p-12 rounded-lg shadow-sm">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-[350px] object-cover rounded-lg mb-8">
            @endif

            <div class="text-xs font-semibold text-gray-400 uppercase mb-3">
                {{ $article->published_at?->format('d \\d\\e F, Y') }}
                @if($article->category) | {{ $article->category->name }} @endif
                @if($article->user) | {{ $article->user->name }} @endif
            </div>

            <h2 class="text-primary-500 text-[2.2rem] font-bold mb-6 leading-tight">{{ $article->title }}</h2>

            <div class="prose prose-lg max-w-none prose-headings:text-primary-500 prose-headings:font-bold prose-a:text-accent-500 text-lg leading-relaxed">
                {!! $article->body !!}
            </div>
        </div>
    </section>

    {{-- Relacionadas --}}
    @if($related->count())
        <section class="bg-white py-16 px-[5%]">
            <h2 class="text-center text-primary-500 text-[1.8rem] font-bold mb-3">Noticias relacionadas</h2>
            <div class="w-[60px] h-1 bg-accent-500 mx-auto rounded-full mb-10"></div>
            <div class="max-w-[800px] mx-auto space-y-6">
                @foreach($related as $rel)
                    <a href="{{ route('news.show', $rel->slug) }}"
                       class="flex flex-col sm:flex-row items-stretch gap-6 bg-white p-6 border-l-[5px] border-primary-500 rounded-r-lg shadow-sm hover:translate-x-2 hover:shadow-md hover:border-accent-500 transition-all duration-200">
                        <div class="text-left">
                            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">{{ $rel->published_at?->format('d \\d\\e F, Y') }}</div>
                            <h3 class="text-primary-500 text-[1.2rem] font-bold mb-1">{{ $rel->title }}</h3>
                            <span class="text-accent-500 font-bold text-sm">Leer artículo →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

@endsection
