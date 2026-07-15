{{--
  ═══════════════════════════════════════════════════════════════
  Section: Análisis
  Grid de 8 artículos — sin cards, solo imagen documental + título.
  ═══════════════════════════════════════════════════════════════
--}}
<section class="bg-base-300 p-4">
<div class="mx-auto max-w-7xl">
  {{-- Encabezado de sección --}}
  <div class="flex items-end justify-between border-b-2 border-base-300">
    <h2 class="font-display font-extrabold text-[0.8rem] md:text-[1.2rem] tracking-tight text-base-content">
      {{ __('Análisis', 'voxpopuli') }}
    </h2>
    <a href="{{ home_url('/category/analisis/') }}"
       class="font-sans font-bold text-[0.75rem] uppercase tracking-[0.2em] text-accent hover:text-accent/80 transition-colors duration-200 no-underline shrink-0 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm">
      {{ __('Ver todas', 'voxpopuli') }}
      <span aria-hidden="true"
            class="inline-block transition-transform duration-300 ease-out group-hover/flecha:translate-x-1">→</span>
    </a>
  </div>

  {{-- Grid con entrada escalonada --}}
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 pt-6">
    @forelse($posts as $post)
      <article>

        {{-- Miniatura documental (4:3) --}}
        <figure class="aspect-video overflow-hidden rounded-box bg-base-300 mb-4">
          @if ($post->image)
            <img src="{{ $post->image }}"
                 alt="{{ $post->alt }}"
                 loading="lazy"
                 class="w-full h-full object-cover" />
          @else
            <div class="w-full h-full flex items-center justify-center">
              <span class="font-sans font-bold text-xs uppercase tracking-[0.2em] text-neutral/40">
                {{ __('Sin imagen documental', 'voxpopuli') }}
              </span>
            </div>
          @endif
        </figure>

        {{-- Título --}}
        <h3 class="font-display font-bold text-base lg:text-lg leading-tight tracking-tight">
          <a href="{{ $post->url }}"
             class="hover:text-accent focus-visible:outline-primary transition-colors duration-200 no-underline">
            {{ $post->title }}
          </a>
        </h3>

      </article>
    @empty
      <p class="font-serif text-base-content/60 col-span-full text-center py-8">
        {{ __('No hay análisis disponibles.', 'voxpopuli') }}
      </p>
    @endforelse
  </div>
</div>
</section>
