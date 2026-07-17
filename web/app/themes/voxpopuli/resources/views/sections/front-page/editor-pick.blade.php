{{--
  ═══════════════════════════════════════════════════════════════
  Section: Selección del Editor
  Artículo destacado curado — ocupación de ancho completo.
  ═══════════════════════════════════════════════════════════════
--}}

<section class="space-y-4 py-8 lg:py-16">
<div class="max-w-7xl mx-auto px-4">

  <header class="flex items-center justify-between border-b-2 border-primary pb-2 mb-6">
    <div class="flex items-center gap-3">
      <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
      <h2 class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight">
        {{ __('Selección del Editor', 'voxpopuli') }}
      </h2>
    </div>
  </header>

  {{-- Card destacada (full-width) --}}
  <div class="card bg-base-200 border-2 border-accent/30 rounded-box sm:card-side overflow-hidden">

    <div class="card-body p-[1.5rem] gap-[1.25rem]">

      {{-- Título grande --}}
      <h2 class="font-display font-extrabold text-base-content leading-[1.08] tracking-tight"
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
        <span class="text-primary">{{ $post->author }}</span>
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
