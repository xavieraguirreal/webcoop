@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' — Noticias' : 'Noticias — Cooperativa Liberté')

@section('content')

    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold mb-3">{{ isset($category) ? $category->name : 'Noticias' }}</h1>
        <p class="text-lg opacity-80 max-w-xl mx-auto">Últimas noticias y novedades de nuestra cooperativa</p>
    </section>

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="max-w-5xl mx-auto">

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

            @if(isset($featured) && $featured)
                <a href="{{ route('news.show', $featured->slug) }}"
                   class="card card-side bg-base-200 shadow-md hover:shadow-xl transition-shadow mb-14 overflow-hidden group">
                    @if($featured->featured_image)
                        <figure class="w-full md:w-80 shrink-0 hover-magnify">
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" alt=""
                                 class="w-full h-56 md:h-full object-cover" loading="lazy">
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

            {{-- News grid con infinite scroll --}}
            <div id="newsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($news as $article)
                    @include('site.news._card', ['article' => $article])
                @endforeach
            </div>

            {{-- Loading spinner --}}
            <div id="newsLoader" class="text-center py-10 hidden">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>

            {{-- No more items --}}
            <div id="newsEnd" class="text-center py-10 hidden">
                <p class="text-base-content/40">No hay más noticias</p>
            </div>

            {{-- Hidden pagination data for JS --}}
            @if($news->hasMorePages())
                <div id="nextPageUrl" data-url="{{ $news->nextPageUrl() }}" class="hidden"></div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('newsGrid');
    const loader = document.getElementById('newsLoader');
    const endMsg = document.getElementById('newsEnd');
    let nextUrlEl = document.getElementById('nextPageUrl');
    let loading = false;

    if (!nextUrlEl) {
        if (endMsg) endMsg.classList.remove('hidden');
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !loading) {
            loadMore();
        }
    }, { rootMargin: '200px' });

    observer.observe(loader);
    loader.classList.remove('hidden');

    async function loadMore() {
        if (!nextUrlEl) return;
        loading = true;

        try {
            const resp = await fetch(nextUrlEl.dataset.url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await resp.text();

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extraer las cards nuevas
            const newCards = doc.querySelectorAll('#newsGrid > *');
            newCards.forEach(card => {
                card.classList.add('animar');
                grid.appendChild(card);
                // Trigger animation
                requestAnimationFrame(() => card.classList.add('visible'));
            });

            // Actualizar URL de siguiente página
            const newNextUrl = doc.getElementById('nextPageUrl');
            if (newNextUrl) {
                nextUrlEl.dataset.url = newNextUrl.dataset.url;
            } else {
                nextUrlEl = null;
                loader.classList.add('hidden');
                endMsg.classList.remove('hidden');
                observer.disconnect();
            }
        } catch (e) {
            console.error('Error loading more news:', e);
        }

        loading = false;
    }
});
</script>
@endpush
