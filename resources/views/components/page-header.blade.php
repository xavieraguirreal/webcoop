@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<section class="page-header bg-secondary text-secondary-content py-16 px-6 text-center">
    @if(count($breadcrumbs))
        <div class="breadcrumbs text-sm text-secondary-content/50 justify-center mb-4">
            <ul>
                <li><a href="{{ route('home') }}" class="hover:text-white">{{ t('Inicio') }}</a></li>
                @foreach($breadcrumbs as $label => $url)
                    @if($url)
                        <li><a href="{{ $url }}" class="hover:text-white">{{ $label }}</a></li>
                    @else
                        <li class="text-secondary-content/80">{{ $label }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
    <h1 class="text-4xl lg:text-5xl font-serif font-bold mb-3 animar">{{ $title }}</h1>
    @if($subtitle)
        <p class="text-lg opacity-80 max-w-xl mx-auto animar">{{ $subtitle }}</p>
    @endif
    <div class="w-16 h-1 bg-primary rounded-full mx-auto mt-4"></div>
</section>
