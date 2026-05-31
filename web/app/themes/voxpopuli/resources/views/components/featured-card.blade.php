@props([
  'post' => null,
])

@php
if (! $post) return;
$categories = get_the_category($post->ID);
$hasImage = has_post_thumbnail($post->ID);
$reading_time = get_post_meta($post->ID, 'vp_reading_time', true);
if (!$reading_time) {
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = max(1, ceil($word_count / 200));
}

// Miniatura semántica LCP pre-cargada con alta prioridad y ajuste absoluto para evitar colapsos visuales
// Aplicamos 'scale-100' de base para establecer el stacking context en carga y asegurar una transición fluida en hover
$thumbnail_html = $hasImage ? get_the_post_thumbnail($post->ID, 'full', [
  'class' => 'absolute inset-0 w-full h-full object-cover grayscale-hover scale-100 group-hover:scale-105 duration-700 ease-out-expo',
  'loading' => 'eager',
  'fetchpriority' => 'high',
]) : '';

// Obtenemos los campos de base de datos en crudo para bypassear cualquier filtro agresivo de truncamiento del tema.
$raw_content = !empty($post->post_content) ? $post->post_content : '';

// Para el destacado majestuoso, forzamos un extracto rico de 105 palabras que balancee el espacio visual de la tarjeta horizontal.
$custom_excerpt = wp_trim_words(strip_tags($raw_content), 105, '...');
@endphp

<article @php(post_class('card lg:card-side bg-base-100 border border-base-300 rounded-xl overflow-hidden shadow-lg mb-16 border-t-4 border-primary group search-card-scroll-anim relative', $post->ID))>
  {{-- DaisyUI Figure --}}
  <figure class="w-full lg:w-3/5 relative aspect-video lg:aspect-auto min-h-[300px] lg:min-h-[360px] overflow-hidden bg-base-200 border-b lg:border-b-0 lg:border-r border-base-300 rounded-none">
    <a href="{{ get_permalink($post) }}" class="absolute inset-0 w-full h-full block z-10" aria-label="{!! get_the_title($post) !!}">
      @if (!empty($thumbnail_html))
        {!! $thumbnail_html !!}
      @else
        <div class="w-full h-full flex flex-col items-center justify-center img-placeholder">
          <span class="font-display text-6xl text-base-300">Vox</span>
        </div>
      @endif
    </a>
    <div class="absolute inset-0 noise-overlay opacity-30 pointer-events-none"></div>
    
    @if (!empty($categories))
      <span class="absolute top-6 left-6 z-20 bg-secondary text-secondary-content font-sans font-extrabold text-[9px] uppercase tracking-[0.25em] px-4 py-2 rounded-sm shadow-md !w-fit !h-fit">
        {{ $categories[0]->name }}
      </span>
    @endif
  </figure>

  {{-- DaisyUI Card-Body --}}
  <div class="card-body w-full lg:w-2/5 p-8 md:p-10 flex flex-col justify-between border-t lg:border-t-0 border-base-300 min-h-[320px]">
    <div>
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.2em] text-secondary-dark">
        {{ __('Última Publicación', 'voxpopuli') }}
      </span>
      
      <div class="flex items-center gap-2 text-neutral font-sans text-[10px] uppercase tracking-wider font-semibold mt-3">
        <time datetime="{{ get_post_time('c', true, $post) }}">{{ get_the_date('', $post) }}</time>
        <span class="text-base-300">•</span>
        <span>{{ sprintf(__('%d min de lectura', 'voxpopuli'), $reading_time) }}</span>
      </div>

      <h2 class="card-title font-display text-2xl md:text-3xl font-black text-primary mt-4 mb-4 leading-tight group-hover:text-secondary duration-300 transition-colors">
        <a href="{{ get_permalink($post) }}" class="text-primary hover:text-secondary focus:outline-none after:absolute after:inset-0 after:z-10 focus-visible:ring-2 focus-visible:ring-secondary focus-visible:ring-offset-2 focus-visible:rounded-lg duration-300 transition-colors">
          {!! get_the_title($post) !!}<span class="text-secondary font-sans font-bold ml-1">.:</span>
        </a>
      </h2>

      <p class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-8">
        {!! $custom_excerpt !!}
      </p>
    </div>

    {{-- Footer & DaisyUI Card-Actions --}}
    <div class="flex items-center justify-between mt-8 pt-5 border-t border-base-300 relative z-20 w-full">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-primary">
        {{ __('Por', 'voxpopuli') }} {{ get_the_author_meta('display_name', $post->post_author) }}
      </span>
      <div class="card-actions justify-end">
        <a href="{{ get_permalink($post) }}" class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-secondary-dark inline-flex items-center gap-1 group-hover:translate-x-1 duration-300 transition-transform">
          {{ __('Leer crónica', 'voxpopuli') }} →
        </a>
      </div>
    </div>
  </div>
</article>



