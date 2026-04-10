<!DOCTYPE html>
<html lang="es-AR" data-theme="liberte">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cooperativa Liberté — Cooperativa de Trabajo')</title>
    <meta name="description" content="@yield('meta_description', 'Cooperativa de Trabajo Liberté Ltda. Transformamos realidades a través del trabajo digno y cooperativo.')">

    {{-- Fonts self-hosted en /fonts/ — sin dependencia de Google --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin href="/fonts/fraunces-latin.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin href="/fonts/intertight-latin.woff2">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-base-100 text-base-content font-sans antialiased leading-relaxed min-h-screen flex flex-col">

    {{-- Loading screen --}}
    <div class="loading-screen" id="loadingScreen">
        <img src="{{ asset('images/logo-icon.png') }}" alt="" class="w-20 h-20">
        <div class="loading-bar"><div class="loading-bar-fill"></div></div>
    </div>

    {{-- Scroll progress bar --}}
    <div class="scroll-progress" id="scrollProgress"></div>

    {{-- Skip link accesible --}}
    <a href="#contenido-principal" class="skip-link">Saltar al contenido</a>

    {{-- HEADER --}}
    <header class="navbar sticky top-0 z-50 px-4 lg:px-8 transition-all duration-300"
            x-data="{ open: false, scrolled: false }"
            x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
            :class="scrolled ? 'bg-secondary text-secondary-content shadow-lg' : 'bg-secondary/90 text-secondary-content shadow-md'">
        <div class="navbar-start">
            <a href="{{ route('home') }}" class="flex items-center" aria-label="Inicio - Cooperativa Liberté">
                <img src="{{ asset('images/logo-liberte.png') }}" alt="Cooperativa Liberté" class="h-10 sm:h-12 w-auto">
            </a>
        </div>

        {{-- Nav desktop --}}
        <div class="navbar-end hidden lg:flex">
            <nav aria-label="Navegación principal">
                <ul class="menu menu-horizontal gap-1 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                    <li><a href="{{ route('page', 'quienes-somos') }}" class="{{ request()->is('nosotros/*') ? 'active' : '' }}">Nosotros</a></li>
                    <li><a href="{{ route('work-areas') }}" class="{{ request()->routeIs('work-areas*', 'work-area*') ? 'active' : '' }}">Áreas de Trabajo</a></li>
                    <li><a href="{{ route('news.index') }}" class="{{ request()->routeIs('news*') ? 'active' : '' }}">Noticias</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contacto</a></li>
                </ul>
            </nav>
        </div>

        {{-- Hamburger mobile --}}
        <div class="navbar-end lg:hidden">
            <button @click="open = !open" class="btn btn-ghost btn-square" aria-label="Menú" :aria-expanded="open.toString()">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile nav --}}
        <div x-show="open" x-cloak x-transition.opacity class="absolute top-full left-0 right-0 bg-secondary border-t border-secondary-content/10 shadow-lg lg:hidden z-40">
            <nav aria-label="Navegación móvil">
                <ul class="menu menu-vertical p-4 text-secondary-content text-base gap-1">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('page', 'quienes-somos') }}">Nosotros</a></li>
                    <li><a href="{{ route('work-areas') }}">Áreas de Trabajo</a></li>
                    <li><a href="{{ route('news.index') }}">Noticias</a></li>
                    <li><a href="{{ route('contact') }}">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    {{-- CONTENT --}}
    <main id="contenido-principal" class="flex-1 reveal-footer-spacer bg-base-100">
        @yield('content')
    </main>

    {{-- FOOTER — reveal: se asoma por debajo del contenido --}}
    <footer class="bg-neutral text-neutral-content reveal-footer">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                {{-- Marca --}}
                <div class="md:col-span-2">
                    <span class="text-xl font-bold font-serif text-white">Liberté</span>
                    <p class="mt-4 text-sm leading-relaxed opacity-80">
                        Cooperativa de Trabajo Liberté Ltda. Un emprendimiento 100% autogestionado
                        dentro de la Unidad Penal N.° 15 de Batán. Transformamos realidades
                        a través del trabajo digno y el compromiso colectivo.
                    </p>
                    <div class="mt-6 flex gap-3">
                        <a href="#" class="btn btn-circle btn-ghost btn-sm opacity-70 hover:opacity-100" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="btn btn-circle btn-ghost btn-sm opacity-70 hover:opacity-100" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Nav footer --}}
                <div>
                    <h3 class="text-primary font-semibold mb-4 text-sm uppercase tracking-wider">Navegación</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="opacity-70 hover:opacity-100 hover:text-primary transition">Inicio</a></li>
                        <li><a href="{{ route('page', 'quienes-somos') }}" class="opacity-70 hover:opacity-100 hover:text-primary transition">Nosotros</a></li>
                        <li><a href="{{ route('work-areas') }}" class="opacity-70 hover:opacity-100 hover:text-primary transition">Áreas de trabajo</a></li>
                        <li><a href="{{ route('news.index') }}" class="opacity-70 hover:opacity-100 hover:text-primary transition">Noticias</a></li>
                        <li><a href="{{ route('contact') }}" class="opacity-70 hover:opacity-100 hover:text-primary transition">Contacto</a></li>
                    </ul>
                </div>

                {{-- Contacto --}}
                <div>
                    <h3 class="text-primary font-semibold mb-4 text-sm uppercase tracking-wider">Contacto</h3>
                    <ul class="space-y-2 text-sm opacity-70">
                        <li>Unidad Penal N.° 15 — Batán</li>
                        <li>Mar del Plata, Buenos Aires</li>
                        <li>Argentina</li>
                    </ul>
                    <h3 class="text-primary font-semibold mb-3 mt-6 text-sm uppercase tracking-wider">Sitios hermanos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="opacity-70 hover:opacity-100 hover:text-primary transition">Taller Solidario Liberté</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-neutral-content/10 text-center text-sm opacity-50">
                &copy; {{ date('Y') }} Cooperativa de Trabajo Liberté Ltda. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    {{-- Lightbox --}}
    <div x-data="{ open: false, src: '' }" @lightbox.window="open = true; src = $event.detail"
         x-show="open" x-cloak x-transition.opacity
         @click="open = false" @keydown.escape.window="open = false"
         class="lightbox-overlay">
        <img :src="src" alt="">
        <button class="absolute top-6 right-6 text-white/70 hover:text-white text-3xl" @click="open = false">&times;</button>
    </div>

    {{-- Back to top con % --}}
    <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
            class="back-to-top btn btn-circle btn-primary shadow-lg relative"
            id="backToTop"
            aria-label="Volver arriba">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
        {{-- Circular progress ring --}}
        <svg class="absolute inset-0 -rotate-90 w-full h-full" viewBox="0 0 48 48">
            <circle cx="24" cy="24" r="22" fill="none" stroke="white" stroke-width="2" opacity="0.2"/>
            <circle cx="24" cy="24" r="22" fill="none" stroke="white" stroke-width="2.5"
                    stroke-dasharray="138.23"
                    id="backToTopRing"
                    stroke-linecap="round"/>
        </svg>
    </button>

    {{-- Global effects script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollProgress = document.getElementById('scrollProgress');
        const backToTop = document.getElementById('backToTop');
        const backToTopRing = document.getElementById('backToTopRing');
        const circumference = 2 * Math.PI * 22;

        if (backToTopRing) {
            backToTopRing.style.strokeDasharray = circumference;
            backToTopRing.style.strokeDashoffset = circumference;
        }

        // === Scroll handler (progress bar + back-to-top + ring) ===
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

            if (scrollProgress) scrollProgress.style.width = pct + '%';

            if (backToTop) backToTop.classList.toggle('visible', scrollTop > 400);

            if (backToTopRing) {
                backToTopRing.style.strokeDashoffset = circumference - (circumference * pct / 100);
            }
        }, { passive: true });

        // === Loading screen dismiss ===
        const loader = document.getElementById('loadingScreen');
        if (loader) {
            window.addEventListener('load', () => {
                setTimeout(() => loader.classList.add('loaded'), 800);
            });
            // Fallback: si tarda más de 3s, cerrar igual
            setTimeout(() => loader.classList.add('loaded'), 3000);
        }

        // === Scroll-in animations con stagger ===
        const animObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.dataset.delay || 0;
                    setTimeout(() => entry.target.classList.add('visible'), delay);
                    animObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.animar, .animar-left, .animar-scale').forEach((el) => {
            if (!el.dataset.delay && el.parentElement) {
                const siblings = Array.from(el.parentElement.children).filter(
                    c => c.classList.contains('animar') || c.classList.contains('animar-scale')
                );
                const idx = siblings.indexOf(el);
                if (idx > 0) el.dataset.delay = idx * 120;
            }
            animObserver.observe(el);
        });

        // === Image reveal on scroll ===
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('revealed'), 100);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.image-reveal').forEach(el => revealObserver.observe(el));

        // === Highlight on scroll ===
        const highlightObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('highlighted');
                    highlightObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        document.querySelectorAll('.highlight-text').forEach(el => highlightObserver.observe(el));

        // === Tilt 3D on cards ===
        document.querySelectorAll('.tilt-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -15;
                const rotateY = ((x - centerX) / centerX) * 15;
                card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg)';
            });
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
