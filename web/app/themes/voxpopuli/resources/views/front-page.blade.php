@extends('layouts.app')

@push('head')
    @if ($hero && $hero->image)
        <link rel="preload" as="image" href="{{ $hero->image }}" fetchpriority="high">
    @endif
    <meta name="description" content="{{ __('Periodismo independiente desde el Caribe colombiano. Investigación, análisis y opinión con rigor técnico, mirada progresista y arraigo caribeño.', 'voxpopuli') }}">
    <meta property="og:title" content="{{ __('Vox Populi Digital', 'voxpopuli') }}">
    <meta property="og:description" content="{{ __('Periodismo independiente desde el Caribe colombiano. Investigación, análisis y opinión con rigor técnico, mirada progresista y arraigo caribeño.', 'voxpopuli') }}">
    <meta property="og:type" content="website">
    @if ($hero && $hero->image)
        <meta property="og:image" content="{{ $hero->image }}">
    @endif
@endpush

@section('content')
        <a href="{{ $hero->url }}"
            class="hero min-h-[clamp(480px,74vh,720px)] overflow-hidden bg-cover bg-center bg-base-200"
            @if ($hero->image) style="background-image:url('{{ $hero->image }}')" @endif>
            <div class="hero-overlay bg-linear-to-t from-black/85 via-black/35 to-black/25"></div>
            <div class="hero-content text-neutral-content text-left w-full justify-start items-end pb-14 px-6 lg:px-16">
                <div class="max-w-[760px]">
                    <div class="flex items-center gap-2.5">
                        <span class="w-[5px] h-[17px] bg-accent block"></span>
                        <span
                            class="font-sans font-bold text-xs tracking-[0.14em] uppercase text-accent">{{ $hero->category }}</span>
                    </div>

                    <h1 class="font-display font-extrabold text-white leading-[1.02] tracking-tight mb-4.5"
                        style="font-size:clamp(2.375rem,5.4vw,4.25rem)">
                        {{ $hero->title }}
                    </h1>

                    @if ($hero->excerpt)
                        <p class="font-serif text-white/88 leading-relaxed mb-5.5 max-w-[600px]"
                            style="font-size:clamp(1.0625rem,1.5vw,1.25rem)">
                            {{ $hero->excerpt }}
                        </p>
                    @endif

                    <div class="font-sans font-semibold text-xs tracking-[0.06em] uppercase text-white/80">
                        {{ __('Por', 'voxpopuli') }} {{ $hero->author }} · {{ $hero->date }}
                    </div>
                </div>
            </div>
        </a>


    <div class="py-8 lg:py-16">
        @if ($featured || !empty($rail))
            <section class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-12 lg:gap-24 items-start">
                    @if ($featured)
                        <a href="{{ $featured->url }}" class="block no-underline text-base-content">
                            <div
                                class="w-full overflow-hidden rounded-lg bg-base-200 border border-base-300 relative mb-5">
                                @if ($featured->image)
                                    <img src="{{ $featured->image }}" alt="{{ $featured->alt }}" loading="lazy" decoding="async"
                                        class="w-full h-full aspect-video object-cover" />
                                @else
                                    <div class="w-full h-full img-placeholder flex items-center justify-center p-8">
                                        <span
                                            class="text-neutral font-sans text-sm">{{ __('Sin imagen documental', 'voxpopuli') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-[5px] h-[17px] bg-accent block"></span>
                                <span
                                    class="font-sans font-bold text-xs tracking-[0.14em] uppercase text-primary">{{ $featured->category }}</span>
                            </div>

                            <h2 class="font-display font-bold text-base-content leading-[1.08] tracking-tight mb-3.5"
                                style="font-size:clamp(1.75rem,3vw,2.375rem)">
                                {{ $featured->title }}
                            </h2>

                            @if ($featured->excerpt)
                                <p class="font-serif text-base-content/85 leading-relaxed mb-3.5 max-w-[620px]">
                                    {{ $featured->excerpt }}
                                </p>
                            @endif

                            <div class="font-sans font-semibold text-xs tracking-[0.05em] uppercase text-neutral">
                                {{ __('Por', 'voxpopuli') }} {{ $featured->author }} · {{ $featured->date }}
                            </div>
                        </a>
                    @endif

                    @if (!empty($rail))
                        <div aria-labelledby="heading-rail">
                            <div class="flex items-center gap-2.5 mb-1 pb-3.5 border-b-2 border-base-content">
                                <h2
                                    id="heading-rail"
                                    class="font-display font-extrabold text-sm tracking-[0.14em] uppercase text-base-content">
                                    {{ __('Últimas', 'voxpopuli') }}
                                </h2>
                            </div>

                            @foreach ($rail as $post)
                                <a href="{{ $post->url }}"
                                    class="flex gap-3.5 items-start py-6 border-b border-base-300 no-underline text-base-content hover:text-accent transition-colors duration-200">
                                    <div class="shrink-0 w-1/4 overflow-hidden rounded bg-base-200 relative">
                                        @if ($post->image)
                                            <img src="{{ $post->image }}" alt="{{ $post->alt }}" loading="lazy" decoding="async"
                                                class="w-full h-full aspect-square lg:aspect-video object-cover" />
                                        @endif
                                    </div>
                                    <div>
                                        <div
                                            class="font-sans font-bold text-[0.6875rem] tracking-[0.14em] uppercase text-primary mb-1.5">
                                            {{ $post->category }}
                                        </div>
                                        <div
                                            class="font-sans font-semibold text-base lg:text-lg leading-tight text-base-content">
                                            {{ $post->title }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </div>
        @if (!empty($investigacion) || !empty($analisis))
            @include('sections.front-page.investigacion-analisis', ['investigacion' => $investigacion, 'analisis' => $analisis])
        @endif

        @if (!empty($opinion))
            @include('sections.front-page.opinion', ['posts' => $opinion])
        @endif

        @if (!empty($deportes))
            @include('sections.front-page.deportes', ['deportes' => $deportes])
        @endif

        @if (!empty($barranquilla) || !empty($cartagena) || !empty($santaMarta))
            @include('sections.front-page.territorios', [
                'barranquilla' => $barranquilla,
                'cartagena' => $cartagena,
                'santaMarta' => $santaMarta,
            ])
        @endif

        @if (!empty($editorPick))
            @include('sections.front-page.editor-pick', ['post' => $editorPick])
        @endif

        @if (!empty($esenciales))
            @include('sections.front-page.esenciales', ['posts' => $esenciales])
        @endif

        {{-- @if (!empty($multimedia))
            @include('sections.front-page.multimedia', ['posts' => $multimedia])
        @endif

        @include('sections.front-page.boletin') --}}

        @endsection
