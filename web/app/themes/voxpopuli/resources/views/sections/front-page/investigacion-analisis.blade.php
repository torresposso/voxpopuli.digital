{{--
  ═══════════════════════════════════════════════════════════════
  Section: Investigación + Análisis
  Dos columnas — 3 artículos cada una, formato lista.
  Consistente con el rail "Últimas" (mismo sistema de thumbnails).
  ═══════════════════════════════════════════════════════════════
--}}
<section
  class="bg-base-200 lg:my-4 px-4 py-8 lg:py-16"
  aria-labelledby="heading-investigacion heading-analisis"
>
<div class="max-w-7xl mx-auto">
  <div class="grid grid-cols-1 lg:grid-cols-2">

    {{-- Columna: Investigación --}}
    {{-- Sub-grid rows: header auto, content 1fr — alinea el contenido entre columnas --}}
    <div class="lg:pr-4 grid grid-rows-[auto_1fr] gap-y-4">
      <div class="flex items-center justify-between border-b-2 border-base-content pb-4">
        <h2
          id="heading-investigacion"
          class="font-display font-extrabold text-2xl tracking-tight text-base-content"
        >
          {{ __('Periodismo de Investigación', 'voxpopuli') }}
        </h2>
        <a
          href="{{ home_url('/category/investigacion/') }}"
          class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-accent hover:text-accent/80 transition-colors duration-200 no-underline shrink-0 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
        >
          {{ __('Ver todas', 'voxpopuli') }}
          <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
        </a>
      </div>

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
                  <span class="font-sans font-bold text-[0.5625rem] uppercase tracking-[0.12em] text-base-content/50 text-center leading-tight px-1">
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
      <div class="flex items-center justify-between border-b-2 border-base-content pb-4">
        <h2
          id="heading-analisis"
          class="font-display font-extrabold text-2xl tracking-tight text-base-content"
        >
          {{ __('Análisis de la Noticia', 'voxpopuli') }}
        </h2>
        <a
          href="{{ home_url('/category/analisis/') }}"
          class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-accent hover:text-accent/80 transition-colors duration-200 no-underline shrink-0 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
        >
          {{ __('Ver todas', 'voxpopuli') }}
          <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
        </a>
      </div>

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
                  <span class="font-sans font-bold text-[0.5625rem] uppercase tracking-[0.12em] text-base-content/50 text-center leading-tight px-1">
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
