@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - Noticias' : 'Noticias - CooperativaLiberté')

@section('content')

    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">{{ isset($category) ? $category->name : 'Noticias' }}</h1>
        <p class="text-lg opacity-80">Últimas noticias y novedades de nuestra cooperativa</p>
    </div>

    <section class="bg-white py-16 px-[5%]">

        {{-- Filtros --}}
        @if(isset($categories) && $categories->count())
            <div class="flex flex-wrap gap-3 justify-center mb-12">
                <a href="{{ route('news.index') }}" class="px-5 py-2 rounded-md font-medium text-sm transition {{ !isset($category) ? 'bg-primary-500 text-white' : 'bg-cream text-gray-600 hover:bg-primary-100' }}">Todas</a>
                @foreach($categories as $cat)
                    <a href="{{ route('news.category', $cat->slug) }}" class="px-5 py-2 rounded-md font-medium text-sm transition {{ isset($category) && $category->id === $cat->id ? 'bg-primary-500 text-white' : 'bg-cream text-gray-600 hover:bg-primary-100' }}">
                        {{ $cat->name }} ({{ $cat->news_count }})
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Noticia destacada --}}
        @if(isset($featured) && $featured)
            <a href="{{ route('news.show', $featured->slug) }}" class="group block max-w-[800px] mx-auto mb-12 bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition border-l-[5px] border-primary-500 hover:border-accent-500">
                <div class="flex flex-col md:flex-row">
                    @if($featured->featured_image)
                        <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full md:w-[300px] h-[200px] md:h-auto object-cover">
                    @endif
                    <div class="p-8 flex flex-col justify-center">
                        <div class="text-xs font-semibold text-gray-400 uppercase mb-2">
                            {{ $featured->published_at?->format('d \\d\\e F, Y') }}
                            @if($featured->category) | {{ $featured->category->name }} @endif
                        </div>
                        <h2 class="text-primary-500 text-[1.8rem] font-bold mb-3 group-hover:text-accent-600 transition">{{ $featured->title }}</h2>
                        @if($featured->excerpt)
                            <p class="text-gray-600 line-clamp-3">{{ $featured->excerpt }}</p>
                        @endif
                        <span class="text-accent-500 font-bold text-sm mt-4 inline-block">Leer artículo completo →</span>
                    </div>
                </div>
            </a>
        @endif

        {{-- Listado de noticias --}}
        <div class="max-w-[800px] mx-auto space-y-6">
            @foreach($news as $article)
                <a href="{{ route('news.show', $article->slug) }}"
                   class="flex flex-col sm:flex-row items-stretch gap-6 bg-white p-6 border-l-[5px] border-primary-500 rounded-r-lg shadow-sm hover:translate-x-2 hover:shadow-md hover:border-accent-500 transition-all duration-200">
                    @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full sm:w-[150px] h-[120px] sm:h-auto rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-full sm:w-[150px] h-[120px] rounded-lg bg-gradient-to-br from-primary-50 to-primary-100 shrink-0"></div>
                    @endif
                    <div class="text-left">
                        <div class="text-xs font-semibold text-gray-400 uppercase mb-1">
                            {{ $article->published_at?->format('d \\d\\e F, Y') }}
                            @if($article->category) | {{ $article->category->name }} @endif
                        </div>
                        <h3 class="text-primary-500 text-[1.3rem] font-bold mb-2">{{ $article->title }}</h3>
                        @if($article->excerpt)
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $article->excerpt }}</p>
                        @endif
                        <span class="text-accent-500 font-bold text-sm mt-2 inline-block">Leer artículo completo →</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 max-w-[800px] mx-auto">
            {{ $news->links() }}
        </div>
    </section>

@endsection
