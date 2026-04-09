@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' — Cooperativa Liberté')
@section('meta_description', $page->meta_description ?: '')

@section('content')

    {{-- Page header --}}
    <section class="bg-secondary text-secondary-content py-16 px-6 text-center">
        <h1 class="text-4xl lg:text-5xl font-serif font-bold">{{ $page->title }}</h1>
    </section>

    <section class="py-16 px-6 lg:px-8 bg-base-100">
        <div class="max-w-4xl mx-auto flex flex-col lg:flex-row items-start gap-12">
            {{-- Content --}}
            <div class="flex-1 prose prose-lg max-w-none
                        prose-headings:font-serif prose-headings:text-secondary prose-headings:font-bold
                        prose-a:text-primary hover:prose-a:underline
                        prose-strong:text-secondary
                        prose-blockquote:border-primary
                        text-base-content/80 leading-relaxed">
                {!! $page->body !!}
            </div>

            {{-- Side image --}}
            @if($page->featured_image)
                <div class="lg:w-96 shrink-0">
                    <figure class="rounded-box overflow-hidden shadow-xl">
                        <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto" loading="lazy">
                    </figure>
                </div>
            @endif
        </div>
    </section>

@endsection
