@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - CooperativaLiberté')
@section('meta_description', $page->meta_description ?: '')

@section('content')

    {{-- Page Header --}}
    <div class="bg-dark text-white py-16 px-[5%] text-center border-b-[5px] border-accent-500">
        <h1 class="text-[2.5rem] font-bold mb-2">{{ $page->title }}</h1>
    </div>

    <section class="py-16 px-[5%]">
        <div class="max-w-[1000px] mx-auto flex flex-col lg:flex-row items-start gap-12">
            <div class="flex-1 text-lg leading-relaxed prose prose-lg max-w-none prose-headings:text-primary-500 prose-headings:font-bold prose-a:text-accent-500 prose-strong:text-primary-500">
                {!! $page->body !!}
            </div>
            @if($page->featured_image)
                <div class="lg:w-[400px] shrink-0 rounded-lg overflow-hidden shadow-xl">
                    <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto">
                </div>
            @else
                <div class="hidden lg:block lg:w-[400px] shrink-0 rounded-lg overflow-hidden shadow-xl">
                    <div class="w-full h-[350px] bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                        <span class="text-primary-300 text-8xl font-serif">L</span>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
