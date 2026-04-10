@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' — Cooperativa Liberté')

@section('content')

    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold">{{ $page->title }}</h1>
    </section>

    {{-- Timeline animada --}}
    <section class="py-20 px-6 lg:px-8 bg-base-100">
        <div class="max-w-4xl mx-auto relative"
             x-data="{ scrollPct: 0 }"
             x-init="window.addEventListener('scroll', () => {
                 const el = $el;
                 const rect = el.getBoundingClientRect();
                 const total = el.offsetHeight - window.innerHeight;
                 scrollPct = Math.min(100, Math.max(0, (-rect.top / total) * 100));
             }, { passive: true })">

            {{-- Línea base --}}
            <div class="timeline-line"></div>
            {{-- Línea que se llena al scrollear --}}
            <div class="timeline-line-fill" :style="'height: ' + scrollPct + '%'"></div>

            @php
                $events = [
                    ['year' => '2014', 'title' => 'Los inicios', 'text' => 'Dos personas dentro de la unidad penitenciaria deciden organizarse para generar trabajo productivo. Nace el germen de lo que sería Liberté.'],
                    ['year' => '2015', 'title' => 'Primeros talleres', 'text' => 'Se abren los primeros talleres de marroquinería y carpintería. El emprendimiento comienza a crecer orgánicamente.'],
                    ['year' => '2017', 'title' => 'Herrería y más', 'text' => 'Se suma el taller de herrería y se amplía la producción. Más miembros se incorporan al proyecto.'],
                    ['year' => '2019', 'title' => 'Reconocimiento', 'text' => 'El emprendimiento gana reconocimiento interno y externo. Se establecen las primeras alianzas institucionales.'],
                    ['year' => '2021', 'title' => 'Formalización', 'text' => 'El emprendimiento se constituye formalmente como Cooperativa de Trabajo Liberté Ltda., consolidando su estructura organizativa.'],
                    ['year' => '2022', 'title' => 'Nuevas áreas', 'text' => 'Se incorporan huerta orgánica, apicultura y artesanías. Alianzas con INTA, UNMdP y Federación Argentina de Cooperativas.'],
                    ['year' => '2024', 'title' => 'Consolidación', 'text' => 'La cooperativa supera los 200 miembros activos con seis áreas productivas funcionando. Un referente de transformación real.'],
                ];
            @endphp

            @foreach($events as $i => $event)
                <div class="timeline-item animar" data-delay="{{ $i * 150 }}">
                    <div class="timeline-dot" x-bind:class="scrollPct > {{ ($i / count($events)) * 100 }} ? 'active' : ''"></div>
                    <div class="card bg-base-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="card-body p-5">
                            <span class="text-primary font-bold text-2xl font-serif">{{ $event['year'] }}</span>
                            <h3 class="font-serif font-bold text-secondary text-lg">{{ $event['title'] }}</h3>
                            <p class="text-base-content/60 text-sm">{{ $event['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Contenido adicional del body si existe --}}
    @if(strip_tags($page->body))
        <section class="py-16 px-6 lg:px-8 bg-base-200">
            <div class="max-w-3xl mx-auto prose prose-lg
                        prose-headings:font-serif prose-headings:text-secondary
                        prose-a:text-primary text-base-content/80 leading-relaxed">
                {!! $page->body !!}
            </div>
        </section>
    @endif

@endsection
