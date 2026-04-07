@extends('layouts.app')

@section('title', 'Cooperativa de Trabajo Liberté - Excelencia a través de la autogestión')

@section('content')

    {{-- HERO --}}
    <section class="bg-primary-500 text-white min-h-[80vh] flex flex-col justify-center items-center text-center px-5 relative overflow-hidden"
             x-data="heroSlider()" x-init="startAutoplay()">
        {{-- Background gradient que cambia --}}
        <div class="absolute inset-0 transition-all duration-1000"
             :style="'background: linear-gradient(135deg, rgba(30,58,138,0.9) 0%, ' + slides[currentSlide].color + ' 100%)'"></div>

        <div class="relative z-10 max-w-[800px]">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlide === index"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <h1 class="text-[2.2rem] sm:text-[3rem] lg:text-[3.5rem] font-bold leading-tight mb-4" x-html="slide.title"></h1>
                    <p class="text-lg opacity-90 mb-8 max-w-[600px] mx-auto" x-text="slide.description"></p>
                </div>
            </template>

            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('work-areas') }}" class="bg-accent-500 text-white font-bold px-8 py-3 rounded-md shadow-lg hover:bg-accent-600 hover:-translate-y-0.5 transition-all">
                    Conoce nuestro trabajo
                </a>
                <a href="{{ route('page', 'quienes-somos') }}" class="border-2 border-white/80 text-white font-bold px-8 py-3 rounded-md bg-white/10 hover:bg-white hover:text-primary-500 transition-all">
                    Nuestra Identidad
                </a>
            </div>

            {{-- Dots --}}
            <div class="mt-10 flex gap-3 justify-center">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="goTo(index)" class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="currentSlide === index ? 'bg-accent-500 w-8' : 'bg-white/40 hover:bg-white/70'"></button>
                </template>
            </div>
        </div>
    </section>

    {{-- BIENVENIDA + FEATURES --}}
    <section class="bg-white py-16 px-[5%]">
        <h2 class="text-center text-primary-500 text-[2.2rem] font-bold mb-3 animar">Bienvenidos a Liberté</h2>
        <div class="w-[60px] h-1 bg-accent-500 mx-auto rounded-full mb-8"></div>
        <p class="max-w-[800px] mx-auto text-center text-lg mb-12 animar">
            Nuestra cooperativa es un emprendimiento 100% autogestionado dentro de la Unidad Penal N.° 15 de Batán. Combinamos el talento individual con la fuerza colectiva para producir con calidad y dignidad.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
            <div class="text-center animar">
                <div class="aspect-[4/3] rounded-lg overflow-hidden shadow-lg mb-6 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                </div>
                <h4 class="text-primary-500 text-[1.4rem] font-bold mb-2">Trabajo en Equipo</h4>
                <p class="text-gray-600">Más de 200 miembros unidos por el cooperativismo, construyendo futuro con sus manos.</p>
            </div>
            <div class="text-center animar" style="transition-delay: 0.2s">
                <div class="aspect-[4/3] rounded-lg overflow-hidden shadow-lg mb-6 bg-gradient-to-br from-accent-50 to-accent-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/></svg>
                </div>
                <h4 class="text-primary-500 text-[1.4rem] font-bold mb-2">Producción Real</h4>
                <p class="text-gray-600">Marroquinería, carpintería, herrería, apicultura, huerta y artesanías de calidad profesional.</p>
            </div>
            <div class="text-center animar" style="transition-delay: 0.4s">
                <div class="aspect-[4/3] rounded-lg overflow-hidden shadow-lg mb-6 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                </div>
                <h4 class="text-primary-500 text-[1.4rem] font-bold mb-2">Compromiso Social</h4>
                <p class="text-gray-600">Desarrollo sostenible, dignidad e inclusión integrados en nuestro modelo cooperativo.</p>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <div class="bg-primary-500 text-white py-16 px-[5%] animar">
        <div class="flex flex-wrap justify-around gap-8 max-w-[1000px] mx-auto text-center">
            <div>
                <h3 class="text-[3.5rem] font-bold text-accent-500">200+</h3>
                <p class="text-lg font-medium opacity-90">Miembros</p>
            </div>
            <div>
                <h3 class="text-[3.5rem] font-bold text-accent-500">2014</h3>
                <p class="text-lg font-medium opacity-90">Desde</p>
            </div>
            <div>
                <h3 class="text-[3.5rem] font-bold text-accent-500">6</h3>
                <p class="text-lg font-medium opacity-90">Áreas Productivas</p>
            </div>
            <div>
                <h3 class="text-[3.5rem] font-bold text-accent-500">100%</h3>
                <p class="text-lg font-medium opacity-90">Autogestionado</p>
            </div>
        </div>
    </div>

    {{-- ÁREAS DE TRABAJO --}}
    <section class="py-16 px-[5%]">
        <h2 class="text-center text-primary-500 text-[2.2rem] font-bold mb-3 animar">Áreas de Trabajo</h2>
        <div class="w-[60px] h-1 bg-accent-500 mx-auto rounded-full mb-12"></div>

        @if($workAreas->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
                @foreach($workAreas as $area)
                    <a href="{{ route('work-area.show', $area->slug) }}"
                       class="group bg-cream rounded-lg overflow-hidden border-t-[5px] border-accent-500 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 animar">
                        @if($area->featured_image)
                            <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}" class="w-full h-[200px] object-cover group-hover:scale-[1.03] transition-transform duration-500">
                        @else
                            <div class="w-full h-[200px] bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                                <span class="text-primary-400 text-5xl font-serif">{{ mb_substr($area->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="p-8 text-center">
                            <h3 class="text-primary-500 text-[1.5rem] font-bold mb-3">{{ $area->name }}</h3>
                            @if($area->short_description)
                                <p class="text-gray-600 text-sm">{{ $area->short_description }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ÚLTIMAS NOTICIAS --}}
    <section class="bg-white py-16 px-[5%]">
        <h2 class="text-center text-primary-500 text-[2.2rem] font-bold mb-3 animar">Últimas Noticias</h2>
        <div class="w-[60px] h-1 bg-accent-500 mx-auto rounded-full mb-4"></div>
        <p class="text-center text-gray-500 mb-12 animar">Mantente al tanto de lo que pasa en la cooperativa.</p>

        @if($news->count())
            <div class="max-w-[800px] mx-auto space-y-6">
                @foreach($news as $article)
                    <a href="{{ route('news.show', $article->slug) }}"
                       class="flex flex-col sm:flex-row items-stretch gap-6 bg-white p-6 border-l-[5px] border-primary-500 rounded-r-lg shadow-sm hover:translate-x-2 hover:shadow-md hover:border-accent-500 transition-all duration-200 animar">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full sm:w-[150px] h-[120px] sm:h-auto rounded-lg object-cover shrink-0">
                        @else
                            <div class="w-full sm:w-[150px] h-[120px] rounded-lg bg-gradient-to-br from-primary-50 to-primary-100 shrink-0"></div>
                        @endif
                        <div class="text-left">
                            <div class="text-xs font-semibold text-gray-400 uppercase mb-1">
                                {{ $article->published_at?->format('d \\d\\e F, Y') }}
                                @if($article->category)
                                    | {{ $article->category->name }}
                                @endif
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
            <div class="text-center mt-8">
                <a href="{{ route('news.index') }}" class="bg-accent-500 text-white font-bold px-8 py-3 rounded-md shadow-lg hover:bg-accent-600 hover:-translate-y-0.5 transition-all inline-block animar">
                    Ver todas las noticias
                </a>
            </div>
        @endif
    </section>

    {{-- ALIANZAS --}}
    <section class="py-12 px-[5%] bg-cream border-t border-gray-200 animar">
        <h2 class="text-center text-sm font-semibold uppercase tracking-widest text-gray-400 mb-8">Alianzas y reconocimientos</h2>
        <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 opacity-70">
            <span class="text-sm font-medium text-gray-500 border border-gray-300 px-6 py-3 rounded-md">Procuración Penitenciaria</span>
            <span class="text-sm font-medium text-gray-500 border border-gray-300 px-6 py-3 rounded-md">UNMdP</span>
            <span class="text-sm font-medium text-gray-500 border border-gray-300 px-6 py-3 rounded-md">INTA</span>
            <span class="text-sm font-medium text-gray-500 border border-gray-300 px-6 py-3 rounded-md">Fed. Arg. Cooperativas</span>
            <span class="text-sm font-medium text-gray-500 border border-gray-300 px-6 py-3 rounded-md">Víctimas por la Paz</span>
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
                title: 'Excelencia a través de la <span class="text-accent-400">autogestión</span>',
                description: 'Somos un equipo de profesionales unidos por el cooperativismo, brindando producción de calidad con compromiso, dignidad y responsabilidad social.',
                color: 'rgba(16, 185, 129, 0.3)'
            },
            {
                title: 'Producción de calidad <span class="text-accent-400">hecha con compromiso</span>',
                description: 'Marroquinería, carpintería, herrería, apicultura, huerta orgánica y artesanías. Cada producto lleva la marca de un trabajo bien hecho.',
                color: 'rgba(5, 150, 105, 0.3)'
            },
            {
                title: 'De Batán <span class="text-accent-400">para todo el país</span>',
                description: 'Más de 200 miembros trabajan día a día en nuestros talleres. Lo que empezó con dos personas hoy es un movimiento cooperativo real.',
                color: 'rgba(4, 120, 87, 0.3)'
            }
        ],
        startAutoplay() { this.interval = setInterval(() => { this.next() }, 5000); },
        next() { this.currentSlide = (this.currentSlide + 1) % this.slides.length; },
        goTo(index) { this.currentSlide = index; clearInterval(this.interval); this.startAutoplay(); }
    }
}
</script>
@endpush
