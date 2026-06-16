@extends('layouts.app')

@section('content')
  {{-- Cabecera Editorial --}}
  <div class="border-b border-base-300 pb-12 mb-12 bg-base-200/40">
    <div class="max-w-7xl mx-auto px-4 pt-12 md:pt-16">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary animate-fade-in-up">
        {{ __('Sección Editorial', 'voxpopuli') }}
      </span>
      <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-black text-primary tracking-tight mt-2 leading-none animate-fade-in-up">
        {{ $title }}<span class="text-secondary ml-1 font-display">.:</span>
      </h1>
      
      @if (!empty($description))
        <div class="font-serif text-base md:text-lg text-neutral mt-4 italic max-w-3xl leading-relaxed animate-fade-in-up" style="animation-delay: 100ms;">
          {!! $description !!}
        </div>
      @endif

      <p class="font-sans text-[10px] font-semibold text-neutral/70 mt-6 uppercase tracking-wider flex items-center gap-2 animate-fade-in-up" style="animation-delay: 200ms;">
        <span class="inline-block w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        @if ($post_count > 0)
          {{ sprintf(_n('Se ha indexado %d crónica o investigación en este archivo.', 'Se han indexado %d crónicas e investigaciones en este archivo.', $post_count, 'voxpopuli'), $post_count) }}
        @else
          {{ __('No hay crónicas en este archivo por el momento.', 'voxpopuli') }}
        @endif
      </p>
    </div>
  </div>

  @if (have_posts())
    <div class="max-w-7xl mx-auto px-4 pb-24">
      @php
        global $wp_query;
        // Obtenemos el primer post para destacarlo majestuosamente
        $first_post = !empty($wp_query->posts) ? $wp_query->posts[0] : null;
      @endphp

      {{-- Articulo Destacado de la Categoría --}}
      @if ($first_post && !is_paged())
        <x-featured-card :post="$first_post" />
      @endif

      {{-- Grilla Editorial para el resto de artículos --}}
      @php
        $post_index = 0;
      @endphp
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12 mt-12">
        @while(have_posts())
          @php
            the_post();
            $post_index++;
            // Nos saltamos el primer post solo en la primera página porque ya lo destacamos arriba
            if ($post_index === 1 && !is_paged()) {
              continue;
            }
          @endphp
          
          <x-post-card :post="get_post()" />
        @endwhile
      </div>

      {{-- Paginación --}}
      <div class="mt-20 pt-10 border-t border-base-300 flex justify-center">
        <div class="join border border-base-300 rounded-lg overflow-hidden bg-base-100 divide-x divide-base-300 shadow-sm">
          {!! paginate_links([
            'prev_text' => '←',
            'next_text' => '→',
            'type' => 'plain',
          ]) !!}
        </div>
      </div>
    </div>
  @else
    {{-- Estado Vacío --}}
    <div class="max-w-4xl mx-auto px-4 py-20 text-center animate-fade-in-up">
      <div class="card bg-base-100 border border-base-300 p-10 md:p-16 rounded-xl shadow-md">
        <div class="font-display text-7xl text-neutral/30 mb-6">.:</div>
        <h2 class="font-display text-2xl md:text-3xl font-black text-primary tracking-tight">
          {{ __('Archivo en construcción', 'voxpopuli') }}
        </h2>
        <p class="font-serif text-sm md:text-base text-neutral mt-4 max-w-xl mx-auto leading-relaxed">
          {{ __('Actualmente estamos recopilando y digitalizando crónicas y reportajes históricos para esta sección. Pronto estará disponible en nuestro archivo vivo.', 'voxpopuli') }}
        </p>
        <div class="mt-8 flex justify-center gap-4">
          <a href="{{ home_url('/') }}" class="btn btn-primary rounded-full px-8 font-sans text-xs font-extrabold tracking-wider">
            {{ __('Volver al inicio', 'voxpopuli') }}
          </a>
        </div>
      </div>
    </div>
  @endif

  {{-- Fallback de animación por scroll para navegadores sin soporte nativo (IntersectionObserver) --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (!CSS.supports('(animation-timeline: view()) and (animation-range: entry)')) {
        const observer = new IntersectionObserver(
          (entries) => {
            for (const entry of entries) {
              const ratio = entry.intersectionRatio;
              // Recreamos el efecto de revelado (fade-in + translate + scale) de forma fluida
              entry.target.style.opacity = (0.3 + ratio * 0.7).toFixed(2);
              entry.target.style.transform = `translate3d(0, ${((1 - ratio) * 30).toFixed(1)}px, 0) scale(${(0.96 + ratio * 0.04).toFixed(3)})`;
              entry.target.style.transition = 'opacity 0.1s ease-out, transform 0.1s ease-out';
            }
          },
          {
            threshold: Array.from({ length: 101 }, (_, i) => i / 100),
          }
        );

        document.querySelectorAll('.search-card-scroll-anim').forEach((el) => {
          observer.observe(el);
        });
      }
    });
  </script>
@endsection

