@extends('layouts.app')

@section('title', 'Contacto — Cooperativa Liberté')

@section('content')

    <x-page-header :title="t('Contacto')" :subtitle="t('contacto.subtitulo')" :breadcrumbs="[t('Contacto') => null]" />

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">

            <div class="animar">
                <h2 class="text-2xl font-serif font-bold text-gradient mb-8">{{ t('Datos de contacto') }}</h2>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-primary mb-2">{{ t('Dirección') }}</h3>
                        <p class="text-base-content/70">
                            Unidad Penal N.° 15 — Batán<br>
                            Mar del Plata, Buenos Aires<br>
                            Argentina
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-primary mb-2">{{ t('Redes sociales') }}</h3>
                        <div class="flex gap-3 mt-2">
                            <a href="#" class="btn btn-circle btn-ghost btn-sm border border-base-300" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <a href="#" class="btn btn-circle btn-ghost btn-sm border border-base-300" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-primary mb-2">{{ t('Sitios hermanos') }}</h3>
                        <ul class="space-y-1">
                            <li><a href="#" class="link link-hover text-base-content/70 hover:text-primary">Taller Solidario Liberté</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Formulario animado --}}
            <div class="card bg-base-200 shadow-md animar" x-data="animatedForm()" x-intersect.once="showFields()">
                <div class="card-body">
                    <h2 class="card-title text-xl font-serif text-gradient mb-4">{{ t('Envianos un mensaje') }}</h2>
                    <form class="form-animated space-y-4">
                        <div class="form-group form-control" :class="visibleFields >= 1 ? 'form-visible' : ''">
                            <label class="label"><span class="label-text font-medium">{{ t('Nombre') }}</span></label>
                            <input type="text" placeholder="Tu nombre" class="input input-bordered w-full" required>
                        </div>
                        <div class="form-group form-control" :class="visibleFields >= 2 ? 'form-visible' : ''" style="transition-delay: 0.15s">
                            <label class="label"><span class="label-text font-medium">{{ t('Email') }}</span></label>
                            <input type="email" placeholder="tu@email.com" class="input input-bordered w-full" required>
                        </div>
                        <div class="form-group form-control" :class="visibleFields >= 3 ? 'form-visible' : ''" style="transition-delay: 0.3s">
                            <label class="label"><span class="label-text font-medium">{{ t('Asunto') }}</span></label>
                            <input type="text" placeholder="Asunto del mensaje" class="input input-bordered w-full">
                        </div>
                        <div class="form-group form-control" :class="visibleFields >= 4 ? 'form-visible' : ''" style="transition-delay: 0.45s">
                            <label class="label"><span class="label-text font-medium">{{ t('Mensaje') }}</span></label>
                            <textarea class="textarea textarea-bordered w-full" rows="5" placeholder="Tu mensaje..." required></textarea>
                        </div>
                        <div class="form-group" :class="visibleFields >= 5 ? 'form-visible' : ''" style="transition-delay: 0.6s">
                            <button type="submit" class="btn btn-accent w-full">{{ t('Enviar mensaje') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
function animatedForm() {
    return {
        visibleFields: 0,
        showFields() {
            const show = () => {
                if (this.visibleFields < 5) {
                    this.visibleFields++;
                    setTimeout(show, 150);
                }
            };
            show();
        }
    }
}
</script>
@endpush
