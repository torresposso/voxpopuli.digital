{{--
  ═══════════════════════════════════════════════════════════════
  Section: Opinión
  Grid de 4 artículos — con imagen documental, título y byline.
  ═══════════════════════════════════════════════════════════════
--}}
<section class="bg-base-100 py-8 lg:py-16">
<div class="mx-auto max-w-7xl px-4">
  {{-- Encabezado de sección --}}
  <header class="flex items-center justify-between border-b-2 border-primary pb-2 mb-4">
    <div class="flex items-center gap-3">
      <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
      <h2 class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight">
        {{ __('Opinión', 'voxpopuli') }}
      </h2>
    </div>
    <a href="{{ home_url('/category/opinion/') }}"
       class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors duration-200 no-underline shrink-0 min-h-[44px] inline-flex items-center focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm">
      {{ __('Ver todas', 'voxpopuli') }}
      <span aria-hidden="true"
            class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
    </a>
  </header>

  {{-- Grid con entrada escalonada --}}
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 pt-6">
    @forelse($posts as $post)
      <article>

        {{-- Imagen documental (16:9) --}}
        <figure class="aspect-video overflow-hidden rounded-box bg-base-300 mb-4">
          @if ($post->image)
            <img src="{{ $post->image }}"
                 alt="{{ $post->alt }}"
                 loading="lazy" decoding="async"
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

        {{-- Byline --}}
        <div class="font-sans font-semibold text-[0.75rem] uppercase tracking-wider text-neutral mt-2">
          {{ __('Por', 'voxpopuli') }}
          <span class="text-primary">{{ $post->author }}</span>
        </div>

      </article>
    @empty
      <p class="font-serif text-base-content/60 col-span-full text-center py-8">
        {{ __('No hay artículos de opinión disponibles.', 'voxpopuli') }}
      </p>
    @endforelse
  </div>
</div>
</section>
