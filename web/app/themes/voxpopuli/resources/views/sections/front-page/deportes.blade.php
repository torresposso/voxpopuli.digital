{{--
  ═══════════════════════════════════════════════════════════════
  Section: Deportes
  Grid de 3 artículos — texto primero, energía visual sin imágenes.
  ═══════════════════════════════════════════════════════════════
--}}
<section class="bg-base-100 px-4 py-8 lg:py-16" aria-labelledby="heading-deportes">
<div class="mx-auto max-w-7xl">
  {{-- Encabezado --}}
  <header class="flex items-center justify-between border-b-2 border-base-300 pb-4 mb-8">
    <div class="flex items-center gap-3">
      <span class="w-[5px] h-[22px] bg-accent block rounded-full" aria-hidden="true"></span>
      <h2
        id="heading-deportes"
        class="font-display font-black text-2xl lg:text-3xl tracking-tight text-base-content"
      >
        {{ __('Deportes', 'voxpopuli') }}
      </h2>
    </div>
    <a
      href="{{ home_url('/category/deportes/') }}"
      class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-accent hover:text-accent/80 transition-colors duration-200 no-underline shrink-0 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
    >
      {{ __('Ver todas', 'voxpopuli') }}
      <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
    </a>
  </header>

  {{-- Grid 3 columnas --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($deportes as $post)
      <article class="card card-border bg-base-100 rounded-box border-base-200 hover:border-accent/30 transition-colors duration-300 overflow-hidden">
        <a href="{{ $post->url }}" class="no-underline text-base-content">
          {{-- Thumbnail 16:9 --}}
          <figure class="aspect-video overflow-hidden bg-base-300">
            @if ($post->image)
              <img src="{{ $post->image }}" alt="{{ $post->alt }}" loading="lazy" decoding="async" class="w-full h-full object-cover" />
            @else
              <div class="w-full h-full flex items-center justify-center">
                <span class="font-sans font-bold text-xs uppercase tracking-[0.12em] text-base-content/40 text-center leading-tight px-1">
                  {{ __('Sin imagen documental', 'voxpopuli') }}
                </span>
              </div>
            @endif
          </figure>

          <div class="p-5">
          {{-- Badge del deporte --}}
          <span class="badge bg-accent text-white font-sans font-bold text-[0.6875rem] tracking-[0.14em] uppercase mb-4">
            {{ $post->category }}
          </span>

          {{-- Título --}}
          <h3 class="font-display font-bold text-lg lg:text-xl leading-tight tracking-tight mb-3">
            {{ $post->title }}
          </h3>

          {{-- Excerpt --}}
          @if ($post->excerpt)
            <p class="font-serif text-sm text-base-content/70 leading-relaxed line-clamp-2 mb-4">
              {{ $post->excerpt }}
            </p>
          @endif

          </div>
        </a>
      </article>
    @empty
      <p class="font-serif text-base-content/60 col-span-full text-center py-8">
        {{ __('No hay artículos de deportes disponibles.', 'voxpopuli') }}
      </p>
    @endforelse
  </div>
</div>
</section>
