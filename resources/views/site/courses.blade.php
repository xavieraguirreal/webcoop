@extends('layouts.app')

@section('title', 'Formación - CooperativaLiberté')

@section('content')

    <section class="bg-primary-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-serif text-4xl">Formación</h1>
            <p class="mt-4 text-primary-200">Oferta educativa y de formación profesional de la cooperativa.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($courses->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-sm overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                            @if($course->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $course->featured_image) }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="aspect-video bg-green-50 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="font-serif text-xl text-primary-500">{{ $course->name }}</h3>
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @if($course->duration)
                                        <span class="text-xs bg-primary-50 text-primary-500 px-3 py-1 rounded-sm">{{ $course->duration }}</span>
                                    @endif
                                    @if($course->modality)
                                        <span class="text-xs bg-green-50 text-green-600 px-3 py-1 rounded-sm">{{ ucfirst($course->modality) }}</span>
                                    @endif
                                    @if($course->has_certificate)
                                        <span class="text-xs bg-gold-50 text-gold-600 px-3 py-1 rounded-sm">Con certificado</span>
                                    @endif
                                </div>
                                @if($course->description)
                                    <div class="mt-4 text-sm text-gray-500 line-clamp-3">{!! strip_tags($course->description) !!}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400">Los cursos se cargarán próximamente.</p>
            @endif
        </div>
    </section>

@endsection
