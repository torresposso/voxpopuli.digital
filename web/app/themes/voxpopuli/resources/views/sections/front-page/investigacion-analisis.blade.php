{{--
  ═══════════════════════════════════════════════════════════════
  Section: Investigación + Análisis
  Dos columnas — 3 artículos cada una, formato lista.
  Consistente con el rail "Últimas" (mismo sistema de thumbnails).
  ═══════════════════════════════════════════════════════════════
--}}
<section
  class="bg-base-200 py-8 lg:py-16"
  aria-labelledby="heading-investigacion heading-analisis"
>
<div class="max-w-7xl mx-auto px-4">
  <div class="grid grid-cols-1 lg:grid-cols-2">

    {{-- Columna: Investigación --}}
    {{-- Sub-grid rows: header auto, content 1fr — alinea el contenido entre columnas --}}
    <div class="lg:pr-4 grid grid-rows-[auto_1fr] gap-y-4">
      <header class="flex items-center justify-between border-b-2 border-primary pb-2 mb-4">
        <div class="flex items-center gap-3">
          <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
          <h2
            id="heading-investigacion"
            class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight"
          >
            {{ __('Periodismo de Investigación', 'voxpopuli') }}
          </h2>
        </div>
        <a
          href="{{ home_url('/category/investigacion/') }}"
          class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors duration-200 no-underline shrink-0 min-h-[44px] inline-flex items-center focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
        >
          {{ __('Ver todas', 'voxpopuli') }}
          <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
        </a>
      </header>

      <div class="flex flex-col">
        @forelse($investigacion as $post)
                  @if ($loop->iteration > 3) @break @endif
                  <a
                    href="{{ $post->url }}"
                    class="flex gap-3.5 items-start py-3 border-b border-base-300/50 last:border-b-0 no-underline text-base-content hover:text-accent transition-colors duration-200 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
                  >
                    <figure class="shrink-0 w-1/3 overflow-hidden rounded bg-base-200">
                      @if ($post->image)
                        <img src="{{ $post->image }}"
                             alt="{{ $post->alt }}"
                             loading="lazy" decoding="async"
                             class="w-full h-full aspect-square lg:aspect-video object-cover" />
                      @else
                        <div class="w-full h-full aspect-video flex items-center justify-center bg-base-200">
                          <span class="font-sans font-bold text-[0.5625rem] uppercase tracking-[0.12em] text-base-content/70 text-center leading-tight px-1">
                            {{ __('Sin imagen', 'voxpopuli') }}
                          </span>
                        </div>
                      @endif
                    </figure>
                    <div class="min-w-0">
                      <span class="font-sans font-bold text-[0.6875rem] tracking-[0.14em] uppercase text-primary mb-1 block">
                        {{ $post->category }}
                      </span>
                      <h3 class="font-display font-bold text-md lg:text-2xl line-clamp-3 lg:text-xl leading-snug tracking-tight">
                        {{ $post->title }}
                      </h3>
                    </div>
                  </a>
                @empty
                  <p class="font-serif text-base-content/60 text-center py-8">
                    {{ __('No hay investigaciones disponibles.', 'voxpopuli') }}
                  </p>
                @endforelse
      </div>
    </div>

    {{-- Columna: Análisis --}}
    <div class="lg:pl-4 lg:border-l-2 lg:border-base-content grid grid-rows-[auto_1fr] gap-y-4">
      <header class="flex items-center justify-between border-b-2 border-primary pb-2 mb-4">
        <div class="flex items-center gap-3">
          <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
          <h2
            id="heading-analisis"
            class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight"
          >
            {{ __('Análisis de la Noticia', 'voxpopuli') }}
          </h2>
        </div>
        <a
          href="{{ home_url('/category/analisis/') }}"
          class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors duration-200 no-underline shrink-0 min-h-[44px] inline-flex items-center focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
        >
          {{ __('Ver todas', 'voxpopuli') }}
          <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
        </a>
      </header>

      <div class="flex flex-col">
        @forelse($analisis as $post)
                  @if ($loop->iteration > 3) @break @endif
                  <a
                    href="{{ $post->url }}"
                    class="flex gap-3.5 items-start py-3 border-b border-base-300/50 last:border-b-0 no-underline text-base-content hover:text-accent transition-colors duration-200 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
                  >
                    <figure class="shrink-0 w-1/3 overflow-hidden rounded bg-base-200">
                      @if ($post->image)
                        <img src="{{ $post->image }}"
                             alt="{{ $post->alt }}"
                             loading="lazy" decoding="async"
                             class="w-full h-full aspect-square lg:aspect-video object-cover" />
                      @else
                        <div class="w-full h-full aspect-video flex items-center justify-center bg-base-200">
                          <span class="font-sans font-bold text-[0.5625rem] uppercase tracking-[0.12em] text-base-content/70 text-center leading-tight px-1">
                            {{ __('Sin imagen', 'voxpopuli') }}
                          </span>
                        </div>
                      @endif
                    </figure>
                    <div class="min-w-0">
                      <span class="font-sans font-bold text-[0.6875rem] tracking-[0.14em] uppercase text-primary mb-1 block">
                        {{ $post->category }}
                      </span>
                      <h3 class="font-display font-bold text-md lg:text-2xl leading-snug tracking-tight">
                        {{ $post->title }}
                      </h3>
                    </div>
                  </a>
                @empty
                  <p class="font-serif text-base-content/60 text-center py-8">
                    {{ __('No hay análisis disponibles.', 'voxpopuli') }}
                  </p>
                @endforelse
      </div>
    </div>

  </div>
</div>
</section>
