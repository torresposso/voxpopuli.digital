@props([
  'post' => null,
])

@php
if (! $post) return;
$categories = get_the_category($post->ID);
$category_name = !empty($categories) ? $categories[0]->name : __('Investigación', 'voxpopuli');

// ⚡ Bolt: Use pre-computed reading time metadata instead of dynamic parsing
$reading_time = get_post_meta($post->ID, 'vp_reading_time', true);
if (!$reading_time) {
  $content = get_post_field('post_content', $post->ID);
  $word_count = str_word_count(strip_tags($content));
  $reading_time = max(1, ceil($word_count / 200));
}

// Solicitamos el tamaño 'full' (original) y blindamos contra registros huérfanos
// Aplicamos 'scale-100' de base para establecer el stacking context en carga y asegurar una transición fluida en hover
$thumbnail_html = get_the_post_thumbnail($post->ID, 'full', [
  'class' => 'object-cover w-full h-full grayscale-hover scale-100 group-hover:scale-105 duration-700 ease-out-expo',
  'loading' => 'lazy',
]);
@endphp

<article @php(post_class('card bg-base-100 border border-base-300 rounded-lg overflow-hidden transition-all duration-500 hover:shadow-xl hover:border-primary/20 group search-card-scroll-anim', $post->ID))>
  <figure class="w-full relative aspect-video overflow-hidden bg-base-200 border-b border-base-200">
    <a href="{{ get_permalink($post->ID) }}" class="absolute inset-0 w-full h-full block z-10" aria-label="{!! get_the_title($post->ID) !!}">
      @if (!empty($thumbnail_html))
        {!! $thumbnail_html !!}
      @else
        <div class="img-placeholder w-full h-full flex flex-col items-center justify-center p-4">
          <span class="font-display text-4xl text-base-300">Vox</span>
        </div>
      @endif
    </a>
    <div class="absolute inset-0 noise-overlay opacity-30"></div>
    <span class="absolute top-4 left-4 z-20 bg-secondary text-secondary-content font-sans font-extrabold text-[9px] uppercase tracking-[0.25em] px-3 py-1.5 rounded-sm shadow-sm">
      {{ $category_name }}
    </span>
  </figure>

  <div class="card-body p-6 flex-1 flex flex-col justify-between">
    <div>
      <div class="flex items-center gap-2 text-neutral font-sans text-[10px] uppercase tracking-wider font-semibold">
        <time datetime="{{ get_post_time('c', true, $post) }}">{{ get_the_date('', $post->ID) }}</time>
        <span class="text-base-300">•</span>
        <span>{{ sprintf(__('%d min de lectura', 'voxpopuli'), $reading_time) }}</span>
      </div>

      <h2 class="card-title font-display text-xl font-bold text-primary mt-3 mb-4 leading-snug group-hover:text-secondary transition-colors duration-300">
        <a href="{{ get_permalink($post->ID) }}" class="text-primary hover:text-secondary duration-300">
          {!! get_the_title($post->ID) !!}
        </a>
      </h2>

      <div class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-3">
        {!! get_the_excerpt($post->ID) !!}
      </div>
    </div>

    <div class="flex items-center justify-between mt-6 pt-4 border-t border-base-300">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-primary">
        {{ __('Por', 'voxpopuli') }} {{ get_the_author_meta('display_name', $post->post_author) }}
      </span>
      <a href="{{ get_permalink($post->ID) }}" class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-secondary-dark inline-flex items-center gap-1 group-hover:translate-x-1 duration-300 transition-transform">
        {{ __('Leer crónica', 'voxpopuli') }} →
      </a>
    </div>
  </div>
</article>

