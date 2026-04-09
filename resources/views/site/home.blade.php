@extends('layouts.app')

@section('title', 'Cooperativa Liberté — Excelencia a través de la autogestión')

@section('content')

    {{-- HERO --}}
    <section class="relative min-h-[80vh] flex items-center justify-center text-center overflow-hidden"
             style="background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary) 60%, #a4d65e 100%)"
             x-data="heroSlider()" x-init="startAutoplay()">
        {{-- Overlay sutil --}}
        <div class="absolute inset-0 bg-black/20"></div>

        <div class="relative z-10 max-w-3xl px-6 text-white">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlide === index"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold leading-tight mb-5" x-html="slide.title"></h1>
                    <p class="text-lg opacity-90 mb-8 max-w-xl mx-auto" x-text="slide.description"></p>
                </div>
            </template>

            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('work-areas') }}" class="btn btn-accent btn-lg shadow-lg">
                    Conoce nuestro trabajo
                </a>
                <a href="{{ route('page', 'quienes-somos') }}" class="btn btn-outline btn-lg border-white/80 text-white hover:bg-white hover:text-secondary hover:border-white">
                    Nuestra Identidad
                </a>
            </div>

            {{-- Dots --}}
            <div class="mt-10 flex gap-3 justify-center">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="goTo(index)" class="h-3 rounded-full transition-all duration-300"
                            :class="currentSlide === index ? 'bg-accent w-8' : 'bg-white/40 hover:bg-white/70 w-3'"
                            :aria-label="'Slide '+(index+1)"
                            :aria-current="currentSlide === index ? 'true' : 'false'"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- BIENVENIDA + FEATURES --}}
    <section class="py-20 px-6 lg:px-8 bg-base-100">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-4xl font-serif font-bold text-secondary mb-4 animar">Bienvenidos a Liberté</h2>
            <div class="w-16 h-1 bg-primary rounded-full mx-auto mb-8"></div>
            <p class="text-lg text-base-content/70 max-w-2xl mx-auto mb-14 animar">
                Nuestra cooperativa es un emprendimiento 100% autogestionado dentro de la
                Unidad Penal N.° 15 de Batán. Combinamos el talento individual con la fuerza
                colectiva para producir con calidad y dignidad.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @php
                $features = [
                    ['icon' => 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z', 'title' => 'Trabajo en Equipo', 'text' => 'Más de 200 miembros unidos por el cooperativismo, construyendo futuro con sus manos.'],
                    ['icon' => 'M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z', 'title' => 'Producción Real', 'text' => 'Marroquinería, carpintería, herrería, apicultura, huerta y artesanías de calidad profesional.'],
                    ['icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z', 'title' => 'Compromiso Social', 'text' => 'Desarrollo sostenible, dignidad e inclusión integrados en nuestro modelo cooperativo.'],
                ];
            @endphp

            @foreach($features as $i => $f)
                <div class="card bg-base-200 shadow-sm hover:shadow-md transition-shadow animar" @if($i > 0) style="transition-delay: {{ $i * 0.15 }}s" @endif>
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="card-title text-secondary font-serif text-xl">{{ $f['title'] }}</h3>
                        <p class="text-base-content/60 text-sm">{{ $f['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-16 px-6 bg-secondary text-secondary-content animar">
        <div class="max-w-4xl mx-auto">
            <div class="stats stats-vertical sm:stats-horizontal w-full bg-transparent shadow-none">
                @php $statItems = [['200+', 'Miembros'], ['2014', 'Desde'], ['6', 'Áreas Productivas'], ['100%', 'Autogestionado']]; @endphp
                @foreach($statItems as $s)
                    <div class="stat place-items-center">
                        <div class="stat-value text-primary font-serif">{{ $s[0] }}</div>
                        <div class="stat-desc text-secondary-content/80 text-base font-medium mt-1">{{ $s[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AREAS DE TRABAJO --}}
    <section class="py-20 px-6 lg:px-8 bg-base-100">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-center text-4xl font-serif font-bold text-secondary mb-4 animar">Áreas de Trabajo</h2>
            <div class="w-16 h-1 bg-primary rounded-full mx-auto mb-14"></div>

            @if($workAreas->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($workAreas as $area)
                        <a href="{{ route('work-area.show', $area->slug) }}"
                           class="card bg-base-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animar overflow-hidden group">
                            @if($area->featured_image)
                                <figure class="h-48 overflow-hidden">
                                    <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                </figure>
                            @else
                                <figure class="h-48 bg-gradient-to-br from-primary/10 to-primary/20 flex items-center justify-center">
                                    <span class="text-primary/40 text-6xl font-serif">{{ mb_substr($area->name, 0, 1) }}</span>
                                </figure>
                            @endif
                            <div class="card-body items-center text-center p-6">
                                <h3 class="card-title text-secondary font-serif">{{ $area->name }}</h3>
                                @if($area->short_description)
                                    <p class="text-base-content/60 text-sm">{{ $area->short_description }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ULTIMAS NOTICIAS --}}
    <section class="py-20 px-6 lg:px-8 bg-base-200">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-center text-4xl font-serif font-bold text-secondary mb-4 animar">Últimas Noticias</h2>
            <div class="w-16 h-1 bg-primary rounded-full mx-auto mb-4"></div>
            <p class="text-center text-base-content/50 mb-14 animar">Lo que pasa en la cooperativa.</p>

            @if($news->count())
                <div class="space-y-6">
                    @foreach($news as $article)
                        <a href="{{ route('news.show', $article->slug) }}"
                           class="flex flex-col sm:flex-row gap-5 bg-base-100 rounded-box p-5 border-l-4 border-primary shadow-sm hover:shadow-md hover:border-accent transition-all duration-200 animar group">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt=""
                                     class="w-full sm:w-36 h-28 sm:h-auto rounded-lg object-cover shrink-0" loading="lazy">
                            @else
                                <div class="w-full sm:w-36 h-28 rounded-lg bg-gradient-to-br from-primary/5 to-primary/10 shrink-0"></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-base-content/40 uppercase mb-1">
                                    {{ $article->published_at?->translatedFormat('d \\d\\e F, Y') }}
                                    @if($article->category)
                                        <span class="badge badge-ghost badge-sm ml-1">{{ $article->category->name }}</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-serif font-bold text-secondary group-hover:text-primary transition-colors line-clamp-2">{{ $article->title }}</h3>
                                @if($article->excerpt)
                                    <p class="text-base-content/60 text-sm line-clamp-2 mt-1">{{ $article->excerpt }}</p>
                                @endif
                                <span class="text-primary font-semibold text-sm mt-2 inline-block">Leer artículo &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-10">
                    <a href="{{ route('news.index') }}" class="btn btn-primary btn-wide shadow-md animar">
                        Ver todas las noticias
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ALIANZAS --}}
    <section class="py-12 px-6 bg-base-100 border-t border-base-300 animar">
        <h2 class="text-center text-xs font-semibold uppercase tracking-[0.2em] text-base-content/30 mb-8">Alianzas y reconocimientos</h2>
        <div class="flex flex-wrap items-center justify-center gap-4 md:gap-8 max-w-4xl mx-auto">
            @foreach(['Procuración Penitenciaria', 'UNMdP', 'INTA', 'Fed. Arg. Cooperativas', 'Víctimas por la Paz'] as $partner)
                <span class="badge badge-outline badge-lg text-base-content/40 border-base-300 py-4 px-5">{{ $partner }}</span>
            @endforeach
        </div>
    </section>

@endsection

@push('scripts')
<script>
function heroSlider() {
    return {
        currentSlide: 0,
        interval: null,
        slides: [
            {
                title: 'Excelencia a través de la <span class="text-primary">autogestión</span>',
                description: 'Somos un equipo de profesionales unidos por el cooperativismo, brindando producción de calidad con compromiso, dignidad y responsabilidad social.'
            },
            {
                title: 'Producción de calidad <span class="text-primary">hecha con compromiso</span>',
                description: 'Marroquinería, carpintería, herrería, apicultura, huerta orgánica y artesanías. Cada producto lleva la marca de un trabajo bien hecho.'
            },
            {
                title: 'De Batán <span class="text-primary">para todo el país</span>',
                description: 'Más de 200 miembros trabajan día a día en nuestros talleres. Lo que empezó con dos personas hoy es un movimiento cooperativo real.'
            }
        ],
        startAutoplay() { this.interval = setInterval(() => { this.next() }, 5000); },
        next() { this.currentSlide = (this.currentSlide + 1) % this.slides.length; },
        goTo(index) { this.currentSlide = index; clearInterval(this.interval); this.startAutoplay(); }
    }
}
</script>
@endpush
