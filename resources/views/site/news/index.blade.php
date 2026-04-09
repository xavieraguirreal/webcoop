@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' — Noticias' : 'Noticias — Cooperativa Liberté')

@section('content')

    {{-- Page header --}}
    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold mb-3">{{ isset($category) ? $category->name : 'Noticias' }}</h1>
        <p class="text-lg opacity-80 max-w-xl mx-auto">Últimas noticias y novedades de nuestra cooperativa</p>
    </section>

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="max-w-5xl mx-auto">

            {{-- Category filters --}}
            @if(isset($categories) && $categories->count())
                <div class="flex flex-wrap gap-2 justify-center mb-14">
                    <a href="{{ route('news.index') }}"
                       class="btn btn-sm {{ !isset($category) ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                        Todas
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('news.category', $cat->slug) }}"
                           class="btn btn-sm {{ isset($category) && $category->id === $cat->id ? 'btn-primary' : 'btn-ghost bg-base-200' }}">
                            {{ $cat->name }} <span class="badge badge-sm ml-1">{{ $cat->news_count }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Featured article --}}
            @if(isset($featured) && $featured)
                <a href="{{ route('news.show', $featured->slug) }}"
                   class="card card-side bg-base-200 shadow-md hover:shadow-xl transition-shadow mb-14 overflow-hidden group">
                    @if($featured->featured_image)
                        <figure class="w-full md:w-80 shrink-0">
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" alt=""
                                 class="w-full h-56 md:h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </figure>
                    @endif
                    <div class="card-body justify-center">
                        <div class="text-xs font-semibold text-base-content/40 uppercase">
                            {{ $featured->published_at?->translatedFormat('d \\d\\e F, Y') }}
                            @if($featured->category)
                                <span class="badge badge-ghost badge-sm ml-1">{{ $featured->category->name }}</span>
                            @endif
                        </div>
                        <h2 class="card-title text-2xl font-serif text-secondary group-hover:text-primary transition-colors">{{ $featured->title }}</h2>
                        @if($featured->excerpt)
                            <p class="text-base-content/60 line-clamp-3">{{ $featured->excerpt }}</p>
                        @endif
                        <span class="text-primary font-semibold text-sm mt-2">Leer artículo completo &rarr;</span>
                    </div>
                </a>
            @endif

            {{-- News grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($news as $article)
                    <a href="{{ route('news.show', $article->slug) }}"
                       class="card bg-base-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                        @if($article->featured_image)
                            <figure class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt=""
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            </figure>
                        @else
                            <figure class="h-48 bg-gradient-to-br from-primary/5 to-primary/15 flex items-center justify-center">
                                <span class="text-primary/20 text-6xl font-serif">{{ mb_substr($article->title, 0, 1) }}</span>
                            </figure>
                        @endif
                        <div class="card-body p-5">
                            <div class="text-xs font-semibold text-base-content/40 uppercase">
                                {{ $article->published_at?->translatedFormat('d \\d\\e F, Y') }}
                                @if($article->category)
                                    <span class="badge badge-ghost badge-xs ml-1">{{ $article->category->name }}</span>
                                @endif
                            </div>
                            <h3 class="card-title text-base font-serif text-secondary group-hover:text-primary transition-colors line-clamp-2">{{ $article->title }}</h3>
                            @if($article->excerpt)
                                <p class="text-base-content/60 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                            @endif
                            <span class="text-primary font-semibold text-sm mt-auto pt-2">Leer &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-14">
                {{ $news->links() }}
            </div>
        </div>
    </section>

@endsection
