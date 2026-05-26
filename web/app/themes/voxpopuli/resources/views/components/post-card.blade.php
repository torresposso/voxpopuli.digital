@props([
  'post' => null,
])

@php
  if (! $post) return;
  $categories = get_the_category($post->ID);
  $hasImage = has_post_thumbnail($post->ID);
  $imageUrl = $hasImage ? get_the_post_thumbnail_url($post->ID, 'medium_large') : '';
  $thumbnailId = $hasImage ? get_post_thumbnail_id($post->ID) : null;
  $altText = $thumbnailId ? (get_post_meta($thumbnailId, '_wp_attachment_image_alt', true) ?: get_the_title($post->ID)) : get_the_title($post->ID);
@endphp

<article @php(get_post_class('relative bg-base-100 rounded-xl overflow-hidden border border-base-300 shadow-md group flex flex-col', $post->ID))>
  @if ($hasImage)
    <div class="block relative h-44 w-full overflow-hidden">
      <div
        class="w-full h-full bg-cover bg-center grayscale-hover group-hover:scale-105 transition-transform duration-700"
        style="background-image: url('{{ $imageUrl }}');"
        role="img"
        aria-label="{{ $altText }}"
      ></div>
    </div>
  @endif

  <div class="p-5 flex-1 flex flex-col justify-between">
    <div>
      @if (! empty($categories))
        <div class="flex items-center gap-2 mb-3">
          <span class="badge bg-secondary text-secondary-content border-none font-sans text-[10px] uppercase tracking-widest font-extrabold px-2.5 py-1.5 shadow-sm">{{ $categories[0]->name }}</span>
          <span class="font-sans text-[10px] text-base-content/70 font-semibold">{{ get_the_date('', $post->ID) }}</span>
        </div>
      @else
        <div class="mb-3">
          <span class="font-sans text-[10px] text-base-content/70 font-semibold">{{ get_the_date('', $post->ID) }}</span>
        </div>
      @endif

      <h3 class="font-display text-lg font-bold text-base-content leading-snug mb-2 tracking-tight group-hover:text-primary transition-colors duration-300">
        <a href="{{ get_permalink($post->ID) }}" class="focus:outline-none after:absolute after:inset-0 after:z-10 focus-visible:ring-2 focus-visible:ring-secondary focus-visible:ring-offset-2 focus-visible:rounded-lg">
          {!! get_the_title($post->ID) !!}<span class="text-secondary font-sans font-bold ml-1" aria-hidden="true">.:</span>
        </a>
      </h3>

      <p class="font-serif text-sm text-base-content/70 line-clamp-3 leading-relaxed">{!! get_the_excerpt($post->ID) !!}</p>
    </div>

    <div class="pt-3 border-t border-base-200 mt-4 flex items-center justify-between">
      <span class="font-sans text-[10px] font-bold text-primary tracking-widest uppercase group-hover:text-secondary transition-colors duration-300" aria-hidden="true">
        {{ __('Leer más', 'voxpopuli') }} →
      </span>
    </div>
  </div>
</article>
