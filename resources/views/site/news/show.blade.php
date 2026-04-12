@extends('layouts.app')

@section('title', ($article->meta_title ?: $article->title) . ' — Cooperativa Liberté')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')

    <article class="py-12 px-6 lg:px-8 bg-base-100">
        <div class="max-w-3xl mx-auto">

            {{-- Back link --}}
            <a href="{{ route('news.index') }}" class="btn btn-ghost btn-sm mb-8 gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ t('Volver a Noticias') }}
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

            {{-- Share --}}
            <div class="divider my-12"></div>
            <div class="flex items-center justify-center gap-3">
                <span class="text-sm text-base-content/40 font-medium mr-2">{{ t('Compartir') }}:</span>
                <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . request()->url()) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-circle btn-ghost btn-sm border border-base-300 hover:border-primary hover:text-primary"
                   aria-label="WhatsApp">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-circle btn-ghost btn-sm border border-base-300 hover:border-primary hover:text-primary"
                   aria-label="Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener"
                   class="btn btn-circle btn-ghost btn-sm border border-base-300 hover:border-primary hover:text-primary"
                   aria-label="X">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <button onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('svg').classList.add('text-primary')"
                        class="btn btn-circle btn-ghost btn-sm border border-base-300 hover:border-primary hover:text-primary"
                        aria-label="Copiar link">
                    <svg class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </button>
            </div>
        </div>
    </article>

    {{-- Related --}}
    @if($related->count())
        <section class="pb-16 px-6 lg:px-8 bg-base-200">
            <div class="max-w-5xl mx-auto pt-14">
                <h2 class="text-2xl font-serif font-bold text-secondary mb-8 text-center">{{ t('Noticias relacionadas') }}</h2>
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
