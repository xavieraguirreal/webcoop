<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CooperativaLiberté - Cooperativa de Trabajo')</title>
    <meta name="description" content="@yield('meta_description', 'Cooperativa de Trabajo Liberté Ltda. Transformamos realidades a través del trabajo.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-cream text-dark font-sans antialiased leading-relaxed" x-data="{ mobileMenu: false }">

    {{-- HEADER --}}
    <header class="bg-primary-500 text-white sticky top-0 z-50 shadow-[0_2px_10px_rgba(0,0,0,0.2)]">
        <div class="max-w-7xl mx-auto px-[5%] flex items-center justify-between h-[70px]">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-[1.5rem] font-bold tracking-wide flex items-center gap-2">
                Cooperativa <span class="text-accent-500">Liberté</span>
            </a>

            {{-- Nav desktop --}}
            <nav class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="font-medium transition hover:text-accent-500 {{ request()->routeIs('home') ? 'text-accent-500' : '' }}">Inicio</a>
                <a href="{{ route('page', 'quienes-somos') }}" class="font-medium transition hover:text-accent-500">Nosotros</a>
                <a href="{{ route('work-areas') }}" class="font-medium transition hover:text-accent-500 {{ request()->routeIs('work-areas*', 'work-area*') ? 'text-accent-500' : '' }}">Áreas de Trabajo</a>
                <a href="{{ route('news.index') }}" class="font-medium transition hover:text-accent-500 {{ request()->routeIs('news*') ? 'text-accent-500' : '' }}">Noticias</a>
                <a href="{{ route('stories.index') }}" class="font-medium transition hover:text-accent-500 {{ request()->routeIs('stories*') ? 'text-accent-500' : '' }}">Relatos</a>
                <a href="{{ route('contact') }}" class="font-medium transition hover:text-accent-500 {{ request()->routeIs('contact') ? 'text-accent-500' : '' }}">Contacto</a>
            </nav>

            {{-- Mobile menu button --}}
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden text-white">
                <svg x-show="!mobileMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenu" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile nav --}}
        <div x-show="mobileMenu" x-cloak x-transition class="lg:hidden bg-primary-600 border-t border-primary-400">
            <div class="px-[5%] py-4 flex flex-wrap gap-4">
                <a href="{{ route('home') }}" class="font-medium hover:text-accent-500">Inicio</a>
                <a href="{{ route('page', 'quienes-somos') }}" class="font-medium hover:text-accent-500">Nosotros</a>
                <a href="{{ route('work-areas') }}" class="font-medium hover:text-accent-500">Áreas de Trabajo</a>
                <a href="{{ route('news.index') }}" class="font-medium hover:text-accent-500">Noticias</a>
                <a href="{{ route('stories.index') }}" class="font-medium hover:text-accent-500">Relatos</a>
                <a href="{{ route('contact') }}" class="font-medium hover:text-accent-500">Contacto</a>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark text-gray-300 mt-0">
        <div class="max-w-7xl mx-auto px-[5%] py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <span class="text-xl font-bold text-white">Cooperativa <span class="text-accent-500">Liberté</span></span>
                    <p class="mt-4 text-sm leading-relaxed">Cooperativa de Trabajo Liberté Ltda. Un emprendimiento 100% autogestionado. Transformamos realidades a través del trabajo digno y el compromiso colectivo.</p>
                    <div class="mt-6 flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-accent-500 transition" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-accent-500 transition" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-accent-500 font-semibold mb-4">Navegación</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a></li>
                        <li><a href="{{ route('page', 'quienes-somos') }}" class="hover:text-white transition">Nosotros</a></li>
                        <li><a href="{{ route('work-areas') }}" class="hover:text-white transition">Áreas de trabajo</a></li>
                        <li><a href="{{ route('news.index') }}" class="hover:text-white transition">Noticias</a></li>
                        <li><a href="{{ route('stories.index') }}" class="hover:text-white transition">Relatos</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contacto</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-accent-500 font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-sm">
                        <li>Unidad Penal N.&deg; 15 - Batán</li>
                        <li>Mar del Plata, Buenos Aires</li>
                        <li>Argentina</li>
                    </ul>
                    <h3 class="text-accent-500 font-semibold mb-3 mt-6">Sitios hermanos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Universidad Liberté</a></li>
                        <li><a href="#" class="hover:text-white transition">Taller Solidario Liberté</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-gray-700 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Cooperativa de Trabajo Liberté. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    {{-- Scroll animation observer --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

        document.querySelectorAll('.animar').forEach(el => observer.observe(el));
    });
    </script>

    @stack('scripts')
</body>
</html>
