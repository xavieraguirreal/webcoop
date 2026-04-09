@extends('layouts.app')

@section('title', 'Cooperativa Liberté — Excelencia a través de la autogestión')

@section('content')

    {{-- HERO — carousel profesional completo --}}
    <section class="relative min-h-[75vh] flex items-end overflow-hidden"
             x-data="heroSlider()" x-init="start()"
             @mouseenter="pause()" @mouseleave="resume()"
             @keydown.left.window="goTo((currentSlide - 1 + slides.length) % slides.length)"
             @keydown.right.window="goTo((currentSlide + 1) % slides.length)"
             @touchstart.passive="touchStartX = $event.touches[0].clientX"
             @touchend="handleSwipe($event)">

        {{-- Preload images --}}
        <template x-for="slide in slides" :key="'pre-'+slide.image">
            <link rel="preload" as="image" :href="slide.image">
        </template>

        {{-- Background images — wipe/cortina + Ken Burns + parallax --}}
        <template x-for="(slide, index) in slides" :key="'bg-'+index">
            <div class="absolute inset-0"
                 :style="'clip-path: inset(0 ' + (currentSlide === index || prevSlide === index ? '0' : '100%') + ' 0 0); transition: clip-path ' + (currentSlide === index && prevSlide !== -1 ? '1s' : '0s') + ' cubic-bezier(0.77,0,0.175,1); z-index: ' + (currentSlide === index ? 2 : prevSlide === index ? 1 : 0) + ';'">
                <div class="absolute inset-0 bg-cover bg-center will-change-transform"
                     :style="'background-image: url(' + slide.image + '); transform: scale(' + (zooming === index ? slide.zoomTo : (prevZoomed === index ? slide.zoomTo : slide.zoomFrom)) + ') translate(' + parallaxX + 'px, ' + parallaxY + 'px); transition: transform ' + (zooming === index ? '7s' : '0s') + ' ease-in-out;'"
                     @mousemove.window="updateParallax($event)"></div>
            </div>
        </template>

        {{-- Vignette --}}
        <div class="absolute inset-0 z-[3] pointer-events-none" style="box-shadow: inset 0 0 150px rgba(0,0,0,0.4);"></div>

        {{-- Overlay gradiente sutil --}}
        <div class="absolute inset-0 z-[3] bg-gradient-to-t from-black/50 via-black/10 to-black/15 pointer-events-none"></div>

        {{-- Flechas de navegación --}}
        <button @click="goTo((currentSlide - 1 + slides.length) % slides.length)"
                class="absolute left-4 lg:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/30 transition-all hover:scale-110 flex items-center justify-center text-white"
                aria-label="Slide anterior">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="goTo((currentSlide + 1) % slides.length)"
                class="absolute right-4 lg:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/30 transition-all hover:scale-110 flex items-center justify-center text-white"
                aria-label="Slide siguiente">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Contador + Play/Pause (esquina superior derecha) --}}
        <div class="absolute top-6 right-6 lg:right-10 z-20 flex items-center gap-3">
            <span class="text-white/70 text-sm font-mono tracking-wider">
                <span class="text-white font-bold" x-text="String(currentSlide + 1).padStart(2, '0')"></span>
                <span class="mx-1">/</span>
                <span x-text="String(slides.length).padStart(2, '0')"></span>
            </span>
            <button @click="togglePlay()" class="w-9 h-9 rounded-full bg-white/15 backdrop-blur-sm hover:bg-white/30 transition flex items-center justify-center text-white"
                    :aria-label="playing ? 'Pausar' : 'Reproducir'">
                <svg x-show="playing" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                <svg x-show="!playing" x-cloak class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="relative z-10 w-full max-w-4xl px-8 lg:px-16 pb-20 pt-32">

            {{-- Subtítulo --}}
            <div class="mb-4">
                <p class="inline-block hero-text-box hero-text-box--accent text-lg sm:text-xl font-semibold text-white"
                   x-text="slides[currentSlide].subtitle"
                   :style="textState === 'in'
                       ? 'transform: translateX(0) rotate(0deg) scale(1); opacity: 1; transition: transform 0.8s cubic-bezier(0.16,1,0.3,1), opacity 0.6s ease;'
                       : textState === 'exit'
                       ? 'transform: ' + slides[currentSlide].exitTo + '; opacity: 0; transition: transform 0.6s ease, opacity 0.5s ease;'
                       : 'transform: ' + slides[currentSlide].enterFrom + '; opacity: 0; transition: none;'
                   "></p>
            </div>

            {{-- Título --}}
            <div class="mb-8">
                <h1 class="inline-block hero-text-box hero-text-box--dark text-3xl sm:text-4xl lg:text-5xl font-serif font-bold text-white"
                    x-text="slides[currentSlide].title"
                    :style="textState === 'in'
                        ? 'transform: translateX(0) rotate(0deg) scale(1); opacity: 1; transition: transform 0.8s cubic-bezier(0.16,1,0.3,1) 0.25s, opacity 0.6s ease 0.25s;'
                        : textState === 'exit'
                        ? 'transform: ' + slides[currentSlide].exitTo2 + '; opacity: 0; transition: transform 0.6s ease 0.1s, opacity 0.5s ease 0.1s;'
                        : 'transform: ' + slides[currentSlide].enterFrom2 + '; opacity: 0; transition: none;'
                    "></h1>
            </div>

            {{-- Botones --}}
            <div class="flex gap-4 flex-wrap">
                <a href="{{ route('work-areas') }}" class="btn btn-accent shadow-lg">Conoce nuestro trabajo</a>
                <a href="{{ route('page', 'quienes-somos') }}" class="btn glass text-white hover:bg-white/20">Nuestra Identidad</a>
            </div>

            {{-- Dots con barra de progreso --}}
            <div class="mt-10 flex items-center gap-3">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="goTo(index)" class="relative h-1.5 rounded-full overflow-hidden transition-all duration-300"
                            :class="currentSlide === index ? 'w-12 bg-white/30' : 'w-3 bg-white/30 hover:bg-white/50'"
                            :aria-label="'Slide '+(index+1)">
                        {{-- Progress fill inside active dot --}}
                        <div class="absolute inset-0 rounded-full bg-accent transition-none"
                             :style="currentSlide === index ? 'width: ' + progress + '%; transition: none;' : 'width: 0%;'"></div>
                    </button>
                </template>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 text-white/60 flex flex-col items-center gap-2 animate-bounce">
            <span class="text-xs uppercase tracking-[0.2em] font-medium">Descubrí más</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
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
                <div class="card bg-base-200 shadow-sm hover:shadow-md transition-shadow animar-scale">
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

    {{-- STATS con contador animado --}}
    <section class="py-16 px-6 bg-secondary text-secondary-content animar"
             x-data="{ shown: false }"
             x-intersect.once="shown = true">
        <div class="max-w-4xl mx-auto">
            <div class="stats stats-vertical sm:stats-horizontal w-full bg-transparent shadow-none">
                @php
                    $statItems = [
                        ['value' => 200, 'suffix' => '+', 'label' => 'Miembros'],
                        ['value' => 2014, 'suffix' => '', 'label' => 'Desde'],
                        ['value' => 6, 'suffix' => '', 'label' => 'Áreas Productivas'],
                        ['value' => 100, 'suffix' => '%', 'label' => 'Autogestionado'],
                    ];
                @endphp
                @foreach($statItems as $s)
                    <div class="stat place-items-center"
                         x-data="{ count: 0, target: {{ $s['value'] }} }"
                         x-effect="if (shown && count < target) {
                             let step = Math.max(1, Math.ceil(target / 40));
                             setTimeout(() => count = Math.min(count + step, target), 30);
                         }">
                        <div class="stat-value text-white font-serif text-5xl">
                            <span x-text="count"></span>{{ $s['suffix'] }}
                        </div>
                        <div class="stat-desc text-white/70 text-base font-medium mt-1">{{ $s['label'] }}</div>
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
                           class="flex flex-col sm:flex-row gap-5 bg-base-100 rounded-box p-5 border-l-4 border-primary shadow-sm hover:shadow-md hover:border-accent transition-all duration-200 animar card-glow group">
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
        zooming: 0,
        prevZoomed: -1,       // slide anterior que debe mantener su zoom mientras se desvanece
        textState: 'hidden',
        interval: null,
        slides: [
            {
                subtitle: 'Cooperativa Liberté',
                title: 'Excelencia a Través de la Autogestión',
                image: '/images/hero/hero-1.jpg',
                zoomFrom: 1, zoomTo: 1.1,        // zoom IN
                enterFrom:  'translateX(-250px) rotate(-6deg) scale(0.85)',
                exitTo:     'translateX(200px) rotate(5deg) scale(0.85)',
                enterFrom2: 'translateX(-300px) rotate(-8deg) scale(0.85)',
                exitTo2:    'translateX(250px) rotate(6deg) scale(0.85)',
            },
            {
                subtitle: 'Producción Profesional',
                title: 'Hecha con Compromiso y Dignidad',
                image: '/images/hero/hero-2.jpg',
                zoomFrom: 1.1, zoomTo: 1,        // zoom OUT
                enterFrom:  'translateY(-150px) rotate(3deg) scale(0.9)',
                exitTo:     'translateY(100px) rotate(-2deg) scale(0.9)',
                enterFrom2: 'translateY(-200px) rotate(4deg) scale(0.9)',
                exitTo2:    'translateY(120px) rotate(-3deg) scale(0.9)',
            },
            {
                subtitle: 'De Batán para Todo el País',
                title: 'Más de 200 Miembros en 6 Áreas Productivas',
                image: '/images/hero/hero-3.jpg',
                zoomFrom: 1, zoomTo: 1.12,        // zoom IN (un poco más)
                enterFrom:  'translateX(0) rotate(0deg) scale(0.3)',
                exitTo:     'translateX(0) rotate(0deg) scale(1.5)',
                enterFrom2: 'translateX(0) rotate(0deg) scale(0.2)',
                exitTo2:    'translateX(0) rotate(0deg) scale(1.8)',
            }
        ],
        start() {
            requestAnimationFrame(() => {
                this.zooming = 0;
                setTimeout(() => { this.textState = 'in'; }, 300);
            });
            this.interval = setInterval(() => this.next(), 7000);
        },
        next() {
            this.changeTo((this.currentSlide + 1) % this.slides.length);
        },
        goTo(index) {
            if (index === this.currentSlide) return;
            clearInterval(this.interval);
            this.changeTo(index);
            this.interval = setInterval(() => this.next(), 7000);
        },
        changeTo(index) {
            this.textState = 'exit';
            setTimeout(() => {
                // La imagen vieja mantiene su zoom mientras se desvanece
                this.prevZoomed = this.currentSlide;
                this.zooming = -1;
                this.currentSlide = index;
                this.textState = 'hidden';
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        this.zooming = index;
                        this.textState = 'in';
                        // Después de que el crossfade terminó (1s), limpiar
                        setTimeout(() => { this.prevZoomed = -1; }, 1200);
                    });
                });
            }, 600);
        }
    }
}
</script>
@endpush
