@php
$categories = get_the_category();
$category_name = !empty($categories) ? $categories[0]->name : __('Investigación', 'voxpopuli');
$post_id = get_the_ID();
$reading_time = get_post_meta($post_id, 'vp_reading_time', true);
if (!$reading_time) {
  $content = get_post_field('post_content', $post_id);
  $word_count = str_word_count(strip_tags($content));
  $reading_time = max(1, ceil($word_count / 200));
}

// Solicitamos el tamaño 'full' (original) y blindamos contra registros huérfanos
$thumbnail_html = get_the_post_thumbnail(get_the_ID(), 'full', [
  'class' => 'object-cover w-full h-full grayscale-hover group-hover:scale-105 duration-700 ease-out-expo',
  'loading' => (isset($loop) && $loop->first) ? 'eager' : 'lazy',
  'fetchpriority' => (isset($loop) && $loop->first) ? 'high' : null,
]);
@endphp

<article @php(post_class('card bg-base-100 border border-base-300 rounded-lg overflow-hidden transition-all duration-500 hover:shadow-xl hover:border-primary/20 group search-card-scroll-anim'))>
  <figure class="w-full relative aspect-video overflow-hidden bg-base-200 border-b border-base-200">
    @if (!empty($thumbnail_html))
      {!! $thumbnail_html !!}
    @else
      <div class="img-placeholder w-full h-full flex flex-col items-center justify-center p-4">
        <span class="font-display text-4xl text-base-300">Vox</span>
      </div>
    @endif
    <div class="absolute inset-0 noise-overlay opacity-30"></div>
    <span class="absolute top-4 left-4 z-10 bg-secondary text-secondary-content font-sans font-extrabold text-[9px] uppercase tracking-[0.25em] px-3 py-1.5 rounded-sm shadow-sm">
      {{ $category_name }}
    </span>
  </figure>

  <div class="card-body p-6 flex-1 flex flex-col justify-between">
    <div>
      <div class="flex items-center gap-2 text-neutral font-sans text-[10px] uppercase tracking-wider font-semibold">
        <time datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
        <span class="text-base-300">•</span>
        <span>{{ sprintf(__('%d min de lectura', 'voxpopuli'), $reading_time) }}</span>
      </div>

      <h2 class="card-title font-display text-xl font-bold text-primary mt-3 mb-4 leading-snug group-hover:text-secondary transition-colors duration-300">
        <a href="{{ get_permalink() }}" class="text-primary hover:text-secondary duration-300">
          {{ $title }}
        </a>
      </h2>

      <div class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-3">
        @php(the_excerpt())
      </div>
    </div>

    <div class="flex items-center justify-between mt-6 pt-4 border-t border-base-300">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-primary">
        {{ __('Por', 'voxpopuli') }} {{ get_the_author() }}
      </span>
      <a href="{{ get_permalink() }}" class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-secondary inline-flex items-center gap-1 group-hover:translate-x-1 duration-300 transition-transform">
        {{ __('Leer crónica', 'voxpopuli') }} →
      </a>
    </div>
  </div>
</article>
