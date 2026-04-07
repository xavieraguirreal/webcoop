@extends('layouts.app')

@section('title', 'Áreas de trabajo - CooperativaLiberté')

@section('content')

    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">Áreas de Trabajo</h1>
        <p class="text-lg opacity-80">Producción profesional adaptada a la excelencia cooperativa</p>
    </div>

    <section class="py-16 px-[5%]">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
            @foreach($areas as $area)
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
    </section>

@endsection
