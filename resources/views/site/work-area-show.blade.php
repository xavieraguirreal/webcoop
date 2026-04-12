@extends('layouts.app')

@section('title', $area->name . ' — Áreas de Trabajo — Cooperativa Liberté')

@section('content')

    <x-page-header :title="$area->name" :subtitle="$area->short_description ?? null" :breadcrumbs="[t('Áreas de Trabajo') => route('work-areas'), t(\App\Models\WorkArea::GROUPS[$area->group] ?? '') => route('work-areas') . '#' . $area->group, $area->name => null]" />

    <article class="py-12 px-6 lg:px-8 bg-base-100">
        <div class="max-w-3xl mx-auto">

            <a href="{{ route('work-areas') }}" class="btn btn-ghost btn-sm mb-8 gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ t('Volver a Áreas') }}
            </a>

            @if($area->featured_image)
                <figure class="rounded-box overflow-hidden mb-8 shadow-md">
                    <img src="{{ asset('storage/' . $area->featured_image) }}" alt="{{ $area->name }}"
                         class="w-full h-auto max-h-[400px] object-cover">
                </figure>
            @endif

            <div class="prose prose-lg max-w-none
                        prose-headings:font-serif prose-headings:text-secondary prose-headings:font-bold
                        prose-a:text-primary hover:prose-a:underline
                        text-base-content/80 leading-relaxed">
                {!! $area->description !!}
            </div>

            {{-- Before/After slider (se muestra si el área tiene 2 imágenes en el futuro)
                 Por ahora el componente está listo para usar cuando se cargue contenido.
                 Uso: agregar un div con clase ba-slider y dos imágenes. --}}
        </div>
    </article>

@endsection

@push('scripts')
<script>
// Before/After slider functionality
document.querySelectorAll('.ba-slider').forEach(slider => {
    const handle = slider.querySelector('.ba-handle');
    const after = slider.querySelector('.ba-after');
    if (!handle || !after) return;

    let dragging = false;

    const move = (x) => {
        const rect = slider.getBoundingClientRect();
        let pct = ((x - rect.left) / rect.width) * 100;
        pct = Math.max(0, Math.min(100, pct));
        handle.style.left = pct + '%';
        after.style.clipPath = `inset(0 0 0 ${pct}%)`;
    };

    handle.addEventListener('mousedown', () => dragging = true);
    slider.addEventListener('mousemove', (e) => { if (dragging) move(e.clientX); });
    document.addEventListener('mouseup', () => dragging = false);

    slider.addEventListener('touchstart', (e) => { dragging = true; move(e.touches[0].clientX); }, { passive: true });
    slider.addEventListener('touchmove', (e) => { if (dragging) move(e.touches[0].clientX); }, { passive: true });
    document.addEventListener('touchend', () => dragging = false);

    // Initial position
    move(slider.getBoundingClientRect().left + slider.getBoundingClientRect().width / 2);
});
</script>
@endpush
