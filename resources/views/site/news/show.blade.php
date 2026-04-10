@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' — Cooperativa Liberté')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')

    <article class="py-12 px-6 lg:px-8 bg-base-100">
        <div class="max-w-3xl mx-auto">

            {{-- Back link --}}
            <a href="{{ route('news.index') }}" class="btn btn-ghost btn-sm mb-8 gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Volver a Noticias
            </a>

            {{-- Featured image --}}
            @if($article->featured_image)
                <figure class="rounded-box overflow-hidden mb-8 shadow-md cursor-zoom-in"
                        @click="$dispatch('lightbox', '{{ asset('storage/' . $article->featured_image) }}')">
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                         class="w-full h-auto max-h-[450px] object-cover hover:scale-105 transition-transform duration-500">
                </figure>
            @endif

            {{-- Metadata --}}
            <div class="flex flex-wrap items-center gap-3 text-sm text-base-content/50 mb-4">
                <time datetime="{{ $article->published_at?->toDateString() }}">
                    {{ $article->published_at?->translatedFormat('d \\d\\e F, Y') }}
                </time>
                @if($article->category)
                    <span class="badge badge-primary badge-outline badge-sm">{{ $article->category->name }}</span>
                @endif
                @if($article->user)
                    <span>por <strong class="text-base-content/70">{{ $article->user->name }}</strong></span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-3xl lg:text-4xl font-serif font-bold text-secondary leading-tight mb-8">{{ $article->title }}</h1>

            {{-- Body --}}
            <div class="prose prose-lg max-w-none
                        prose-headings:font-serif prose-headings:text-secondary prose-headings:font-bold
                        prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-box prose-img:shadow-md
                        prose-blockquote:border-primary prose-blockquote:bg-base-200 prose-blockquote:rounded-r-box prose-blockquote:py-1 prose-blockquote:px-6
                        text-base-content/80 leading-relaxed">
                {!! $article->body !!}
            </div>

            {{-- Share / divider --}}
            <div class="divider my-12"></div>
        </div>
    </article>

    {{-- Related --}}
    @if($related->count())
        <section class="pb-16 px-6 lg:px-8 bg-base-200">
            <div class="max-w-5xl mx-auto pt-14">
                <h2 class="text-2xl font-serif font-bold text-secondary mb-8 text-center">Noticias relacionadas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}"
                           class="card bg-base-100 shadow-sm hover:shadow-md transition-shadow group overflow-hidden">
                            @if($rel->featured_image)
                                <figure class="h-40 overflow-hidden">
                                    <img src="{{ asset('storage/' . $rel->featured_image) }}" alt=""
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                </figure>
                            @endif
                            <div class="card-body p-4">
                                <div class="text-xs text-base-content/40 uppercase">{{ $rel->published_at?->translatedFormat('d \\d\\e F, Y') }}</div>
                                <h3 class="font-serif font-bold text-secondary group-hover:text-primary transition-colors line-clamp-2">{{ $rel->title }}</h3>
                                <span class="text-primary text-sm font-semibold mt-auto">Leer &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
