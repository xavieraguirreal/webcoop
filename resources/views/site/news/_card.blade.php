<a href="{{ route('news.show', $article->slug) }}"
   class="card card-accent-hover bg-base-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
    @if($article->featured_image)
        <figure class="h-48 overflow-hidden hover-magnify">
            <img src="{{ asset('storage/' . $article->featured_image) }}" alt=""
                 class="w-full h-full object-cover" loading="lazy">
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
