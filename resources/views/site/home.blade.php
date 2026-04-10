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

    {{-- Wave divider hero → bienvenida --}}
    <svg class="wave-divider" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="var(--color-base-100)">
        <path d="M0,40 C360,80 720,0 1440,40 L1440,60 L0,60 Z"/>
    </svg>

    {{-- BIENVENIDA + FEATURES --}}
    <section class="py-20 px-6 lg:px-8 -mt-1 bg-base-100"
        <div class="max-w-5xl mx-auto">
            <h2 class="text-4xl font-serif font-bold text-gradient mb-4 animar text-center">Bienvenidos a Liberté</h2>
            <div class="w-16 h-1 bg-primary rounded-full mx-auto mb-8"></div>
            <p class="text-lg text-base-content/70 max-w-2xl mx-auto mb-14 animar text-center">
                Nuestra cooperativa es un emprendimiento <span class="highlight-text">100% autogestionado</span> dentro de la
                Unidad Penal N.° 15 de Batán. Combinamos el <span class="highlight-text">talento individual</span> con la <span class="highlight-text">fuerza
                colectiva</span> para producir con calidad y dignidad.
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
                <div class="group card bg-base-200 border-t-4 border-primary shadow-sm hover:shadow-xl hover:bg-secondary transition-all duration-300 animar-scale tilt-card">
                    <div class="card-body items-center text-center">
                        <div class="w-20 h-20 rounded-full bg-primary/10 group-hover:bg-white/20 flex items-center justify-center mb-3 transition-colors duration-300">
                            <svg class="w-10 h-10 text-primary group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="card-title text-secondary group-hover:text-white font-serif text-xl transition-colors duration-300">{{ $f['title'] }}</h3>
                        <p class="text-base-content/60 group-hover:text-white/80 text-sm transition-colors duration-300">{{ $f['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Wave divider features → stats --}}
    <svg class="wave-divider" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="var(--color-secondary)">
        <path d="M0,20 C480,60 960,0 1440,30 L1440,60 L0,60 Z"/>
    </svg>

    {{-- STATS con contador animado --}}
    <section class="py-16 px-6 bg-secondary text-secondary-content -mt-1 animar"
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
                         x-data="flipCounter({{ $s['value'] }}, '{{ $s['suffix'] }}')"
                         x-effect="if (shown) startFlip()">
                        <div class="stat-value text-white font-serif text-5xl flex items-center justify-center">
                            <template x-for="(d, i) in displayDigits" :key="i">
                                <span class="flip-digit">
                                    <span class="flip-digit-inner" :style="'transform: translateY(-' + (d * 1.2) + 'em)'">
                                        <template x-for="n in 10">
                                            <span class="flip-digit-char" x-text="n - 1"></span>
                                        </template>
                                    </span>
                                </span>
                            </template>
                            <span x-text="suffix"></span>
                        </div>
                        <div class="stat-desc text-white/70 text-base font-medium mt-1">{{ $s['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Wave divider stats → áreas --}}
    <svg class="wave-divider" viewBox="0 0 1440 60" preserveAspectRatio="none" fill="var(--color-base-100)">
        <path d="M0,30 C360,60 1080,0 1440,20 L1440,60 L0,60 Z"/>
    </svg>

    {{-- AREAS DE TRABAJO --}}
    <section class="py-20 px-6 lg:px-8 bg-base-100 -mt-1">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-center text-4xl font-serif font-bold text-gradient mb-4 animar">Áreas de Trabajo</h2>
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
            <h2 class="text-center text-4xl font-serif font-bold text-gradient mb-4 animar">Últimas Noticias</h2>
            <div class="w-16 h-1 bg-primary rounded-full mx-auto mb-4"></div>
            <p class="text-center text-base-content/50 mb-14 animar">Lo que pasa en la cooperativa.</p>

            @if($news->count())
                {{-- Stacked cards: cada card se desplaza un poco, efecto apilado --}}
                <div class="relative" style="perspective: 1000px;">
                    @foreach($news as $i => $article)
                        <a href="{{ route('news.show', $article->slug) }}"
                           class="stacked-card block bg-base-100 rounded-box p-5 border-l-4 border-primary shadow-md hover:shadow-xl hover:border-accent transition-all duration-300 card-glow group mb-4"
                           style="transform: rotate({{ $i % 2 === 0 ? '-0.5' : '0.5' }}deg);">
                            <div class="flex flex-col sm:flex-row gap-5">
                                @if($article->featured_image)
                                    <div class="w-full sm:w-40 h-32 sm:h-auto rounded-lg shrink-0 hover-magnify overflow-hidden">
                                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt=""
                                             class="w-full h-full object-cover rounded-lg" loading="lazy">
                                    </div>
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

    {{-- PARALLAX section — frase inspiradora --}}
    <section class="parallax-bg relative py-28 px-6 text-center text-white"
             style="background-image: url('{{ asset('images/hero/hero-1.jpg') }}');">
        <div class="absolute inset-0 bg-secondary/75"></div>
        <div class="relative z-10 max-w-3xl mx-auto animar"
             x-data="typingEffect('Trabajar por la recuperación de los derechos y la dignidad en las cárceles')"
             x-intersect.once="startTyping()">
            <svg class="w-10 h-10 text-primary mx-auto mb-6 opacity-60" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
            <blockquote class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold leading-snug mb-6">
                <span x-text="displayText"></span><span class="typing-cursor" x-show="typing"></span>
            </blockquote>
            <p class="text-white/70 text-lg" x-show="!typing" x-transition>Misión de la Cooperativa Liberté</p>
        </div>
    </section>

    {{-- ALIANZAS — marquee --}}
    <section class="py-10 bg-base-100 border-t border-base-300 overflow-hidden">
        <h2 class="text-center text-xs font-semibold uppercase tracking-[0.2em] text-base-content/30 mb-6">Alianzas y reconocimientos</h2>
        <div class="relative">
            {{-- Fade edges --}}
            <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-base-100 to-transparent z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-base-100 to-transparent z-10 pointer-events-none"></div>

            <div class="marquee-track">
                @php $partners = ['Procuración Penitenciaria de la Nación', 'Universidad Nacional de Mar del Plata', 'INTA', 'Federación Argentina de Cooperativas de Crédito', 'Víctimas por la Paz', 'Procuración Penitenciaria de la Nación', 'Universidad Nacional de Mar del Plata', 'INTA', 'Federación Argentina de Cooperativas de Crédito', 'Víctimas por la Paz']; @endphp
                @foreach($partners as $partner)
                    <span class="inline-flex items-center gap-2 px-8 py-3 text-base-content/40 font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4 text-primary/40" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 0 0 0-5.304 3 3 0 0 0-3.75-3.751 3 3 0 0 0-5.305 0 3 3 0 0 0-3.751 3.75 3 3 0 0 0 0 5.305 3 3 0 0 0 3.75 3.751 3 3 0 0 0 5.305 0 3 3 0 0 0 3.751-3.75Zm-2.546-4.46a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                        {{ $partner }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
function flipCounter(target, suffix) {
    return {
        target,
        suffix,
        displayDigits: Array.from(String(target), () => 0),
        started: false,
        startFlip() {
            if (this.started) return;
            this.started = true;
            const targetDigits = String(this.target).split('').map(Number);
            targetDigits.forEach((digit, i) => {
                setTimeout(() => {
                    this.displayDigits[i] = digit;
                    this.displayDigits = [...this.displayDigits]; // force reactivity
                }, 300 + (i * 200));
            });
        }
    }
}

function typingEffect(fullText) {
    return {
        fullText,
        displayText: '',
        typing: false,
        startTyping() {
            if (this.typing || this.displayText.length > 0) return;
            this.typing = true;
            let i = 0;
            const type = () => {
                if (i <= this.fullText.length) {
                    this.displayText = this.fullText.slice(0, i);
                    i++;
                    setTimeout(type, 40 + Math.random() * 30);
                } else {
                    this.typing = false;
                }
            };
            type();
        }
    }
}

function heroSlider() {
    const SLIDE_DURATION = 7000; // ms por slide
    const PROGRESS_INTERVAL = 30; // ms entre updates del progress

    return {
        currentSlide: 0,
        prevSlide: -1,
        zooming: 0,
        prevZoomed: -1,
        textState: 'hidden',
        playing: true,
        progress: 0,
        parallaxX: 0,
        parallaxY: 0,
        touchStartX: 0,
        interval: null,
        progressTimer: null,

        slides: [
            {
                subtitle: 'Cooperativa Liberté',
                title: 'Excelencia a Través de la Autogestión',
                image: '/images/hero/hero-1.jpg',
                zoomFrom: 1, zoomTo: 1.1,
                enterFrom:  'translateX(-250px) rotate(-6deg) scale(0.85)',
                exitTo:     'translateX(200px) rotate(5deg) scale(0.85)',
                enterFrom2: 'translateX(-300px) rotate(-8deg) scale(0.85)',
                exitTo2:    'translateX(250px) rotate(6deg) scale(0.85)',
            },
            {
                subtitle: 'Producción Profesional',
                title: 'Hecha con Compromiso y Dignidad',
                image: '/images/hero/hero-2.jpg',
                zoomFrom: 1.1, zoomTo: 1,
                enterFrom:  'translateY(-150px) rotate(3deg) scale(0.9)',
                exitTo:     'translateY(100px) rotate(-2deg) scale(0.9)',
                enterFrom2: 'translateY(-200px) rotate(4deg) scale(0.9)',
                exitTo2:    'translateY(120px) rotate(-3deg) scale(0.9)',
            },
            {
                subtitle: 'De Batán para Todo el País',
                title: 'Más de 200 Miembros en 6 Áreas Productivas',
                image: '/images/hero/hero-3.jpg',
                zoomFrom: 1, zoomTo: 1.12,
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
            this.startTimer();
        },

        startTimer() {
            this.progress = 0;
            clearInterval(this.progressTimer);
            clearTimeout(this.interval);

            this.progressTimer = setInterval(() => {
                if (!this.playing) return;
                this.progress += (PROGRESS_INTERVAL / SLIDE_DURATION) * 100;
                if (this.progress >= 100) {
                    this.progress = 100;
                    clearInterval(this.progressTimer);
                    this.next();
                }
            }, PROGRESS_INTERVAL);
        },

        next() {
            this.changeTo((this.currentSlide + 1) % this.slides.length);
        },

        goTo(index) {
            if (index === this.currentSlide) return;
            this.changeTo(index);
        },

        changeTo(index) {
            clearInterval(this.progressTimer);
            this.progress = 0;

            // 1. Texto sale
            this.textState = 'exit';

            setTimeout(() => {
                // 2. Wipe: nueva imagen se revela sobre la vieja
                this.prevSlide = this.currentSlide;
                this.prevZoomed = this.currentSlide;
                this.zooming = -1;
                this.currentSlide = index;
                this.textState = 'hidden';

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        // 3. Zoom + texto entra
                        this.zooming = index;
                        this.textState = 'in';

                        // 4. Después de la transición wipe, limpiar
                        setTimeout(() => {
                            this.prevSlide = -1;
                            this.prevZoomed = -1;
                        }, 1200);

                        // 5. Reiniciar timer
                        this.startTimer();
                    });
                });
            }, 600);
        },

        togglePlay() {
            this.playing = !this.playing;
            if (this.playing) {
                this.startTimer();
            } else {
                clearInterval(this.progressTimer);
            }
        },

        pause() {
            if (this.playing) {
                clearInterval(this.progressTimer);
            }
        },

        resume() {
            if (this.playing) {
                this.startTimer();
            }
        },

        handleSwipe(e) {
            const diff = this.touchStartX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.goTo((this.currentSlide + 1) % this.slides.length);
                } else {
                    this.goTo((this.currentSlide - 1 + this.slides.length) % this.slides.length);
                }
            }
        },

        updateParallax(e) {
            const hero = this.$el;
            const rect = hero.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            // Movimiento sutil: máximo 15px en cada dirección
            this.parallaxX = -((e.clientX - centerX) / rect.width) * 15;
            this.parallaxY = -((e.clientY - centerY) / rect.height) * 10;
        }
    }
}
</script>
@endpush
