{{--
  ═══════════════════════════════════════════════════════════════
  Section: Selección del Editor
  Artículo destacado curado — ocupación de ancho completo.
  ═══════════════════════════════════════════════════════════════
--}}

<section class="space-y-4 my-16">
<div class="max-w-7xl mx-auto px-4">

  {{-- Badge --}}
  <x-badge tracking="tracking-[0.2em]">
    {{ __('Selección del Editor', 'voxpopuli') }}
  </x-badge>

  {{-- Card destacada (full-width) --}}
  <div class="card card-border bg-base-200 border-2 border-base-300 rounded-box sm:card-side overflow-hidden">

    {{-- Línea decorativa accent — reemplaza la imagen en card-side
         Mobile:  barra horizontal de 6px arriba
         Desktop: barra vertical de 6px a la izquierda --}}
    <div class="w-full sm:w-[6px] h-[6px] sm:h-auto bg-accent shrink-0" aria-hidden="true"></div>

    <div class="card-body p-[1.5rem] gap-[1.25rem]">

      {{-- Título grande --}}
      <h2 class="font-display font-extrabold text-base-content leading-[1.08] tracking-tighter"
          style="font-size:clamp(1.75rem, 3vw, 2.375rem)">
        <a href="{{ $post->url }}"
           class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms] no-underline">
          {{ $post->title }}
        </a>
      </h2>

      {{-- Excerpt completo --}}
      @if ($post->excerpt)
        <p class="font-serif text-base-content/80 leading-relaxed max-w-prose">
          {{ $post->excerpt }}
        </p>
      @endif

      {{-- Metadatos: autor · fecha --}}
      <div class="font-sans font-semibold text-[0.75rem] uppercase tracking-wider text-neutral">
        {{ __('Por', 'voxpopuli') }}
        <span class="text-accent">{{ $post->author }}</span>
        · {{ $post->date }}
      </div>

      {{-- CTA --}}
      <div class="pt-1">
        <a href="{{ $post->url }}"
           class="btn btn-outline btn-primary font-sans font-bold text-[0.75rem] uppercase tracking-[0.1em] rounded-[2rem]">
          {{ __('Leer investigación', 'voxpopuli') }}
          <span aria-hidden="true">→</span>
        </a>
      </div>

    </div>
  </div>
</div>
</section>
