{{--
  ═══════════════════════════════════════════════════════════════
  Section: Periodismo de Investigación
  Grid de 3 artículos de investigación — con miniatura documental.
  ═══════════════════════════════════════════════════════════════
--}}
<section class="bg-base-300 p-4">
<div class="mx-auto max-w-7xl">
  {{-- Encabezado de sección --}}
  <div class="flex items-end justify-between border-b-2 border-base-300">
    <h2 class="font-display font-extrabold text-[0.8rem] md:text-[1.2rem] tracking-tight text-base-content">
      {{ __('Periodismo de Investigación', 'voxpopuli') }}
    </h2>
    <a href="{{ home_url('/category/investigacion/') }}"
       class="font-sans font-bold text-[0.75rem] uppercase tracking-[0.2em] text-accent hover:text-accent/80 transition-colors duration-200 no-underline shrink-0 focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm">
      {{ __('Ver todas', 'voxpopuli') }}
      <span aria-hidden="true"
            class="inline-block transition-transform duration-300 ease-out group-hover/flecha:translate-x-1">→</span>
    </a>
  </div>

  {{-- Grid de tarjetas con entrada escalonada --}}
  <style>
    @media (prefers-reduced-motion: no-preference) {
      .investigacion-card {
        opacity: 0;
        animation: investigacion-fade-up 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
      }
      .investigacion-card:nth-child(2) { animation-delay: 120ms; }
      .investigacion-card:nth-child(3) { animation-delay: 240ms; }
    }
    @keyframes investigacion-fade-up {
      from { opacity: 0; transform: translateY(1rem); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @forelse($posts as $post)
      <article class="card card-border bg-base-200 border-2 border-base-300 rounded-box hover:shadow-[0_4px_20px_oklch(0%_0_0_/_8%)] transition-shadow duration-200 group/card">

        {{-- Miniatura documental (16:9) con placeholder cuando no hay imagen --}}
        <figure class="aspect-video overflow-hidden rounded-t-box bg-base-300 relative">
          @if ($post->image)
            <img src="{{ $post->image }}"
                 alt="{{ $post->alt }}"
                 loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover/card:scale-105" />
          @else
            <div class="w-full h-full flex items-center justify-center">
              <span class="font-sans font-bold text-[0.625rem] uppercase tracking-[0.2em] text-neutral/30">
                {{ __('Sin imagen documental', 'voxpopuli') }}
              </span>
            </div>
          @endif
        </figure>

        <div class="card-body px-6 -mt-8">
          {{-- Título --}}
          <h3 class="card-title font-display font-bold text-base lg:text-lg leading-tight tracking-tight">
            <a href="{{ $post->url }}"
               class="hover:text-accent focus-visible:outline-primary transition-colors duration-200 no-underline">
              {{ $post->title }}
            </a>
          </h3>

        </div>
      </article>
    @empty
      <p class="font-serif text-base-content/60 col-span-full text-center py-8">
        {{ __('No hay investigaciones disponibles.', 'voxpopuli') }}
      </p>
    @endforelse
  </div>
</div>
</section>
