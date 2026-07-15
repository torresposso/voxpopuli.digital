@props(['post'])

@php
  $thumbnailId = get_post_thumbnail_id($post);
  $thumbnailUrl = $thumbnailId ? wp_get_attachment_image_url($thumbnailId, 'full') : null;
  $categories = get_the_category($post->ID);
  $primaryCategory = ! empty($categories) ? $categories[0] : null;
  $authorName = get_the_author_meta('display_name', $post->post_author);
  $permalink = get_permalink($post);
  $title = get_the_title($post);
@endphp

<section class="relative min-h-[70vh] flex items-end bg-base-200">
  {{-- Background image --}}
  @if ($thumbnailUrl)
    <div class="absolute inset-0">
      {!! get_the_post_thumbnail($post, 'full', ['class' => 'w-full h-full object-cover', 'loading' => 'eager']) !!}
    </div>
  @endif

  {{-- Gradient overlay para legibilidad del texto --}}
  <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>

  {{-- Contenido --}}
  <div class="relative z-10 w-full">
    <div class="mx-auto max-w-7xl px-4 pb-12 md:pb-16 lg:pb-20">
      {{-- Kicker (categoría) --}}
      @if ($primaryCategory)
        <span class="font-sans text-sm font-bold uppercase tracking-[0.2em] text-accent">
          {{ $primaryCategory->name }}
        </span>
      @endif

      {{-- Headline --}}
      <h1 class="font-serif text-[clamp(1.5rem,4vw,3rem)] leading-tight text-white mt-3 max-w-3xl font-bold">
        <a href="{{ $permalink }}" class="text-inherit no-underline hover:underline decoration-2 decoration-white/50 underline-offset-4">
          {{ $title }}
        </a>
      </h1>

      {{-- Byline --}}
      @if ($authorName)
        <p class="font-sans text-sm text-white/70 mt-4 tracking-wide">
          {{ $authorName }}
        </p>
      @endif
    </div>
  </div>

  {{-- Enlace a la historia — toda el área es clickeable --}}
  <a href="{{ $permalink }}" class="absolute inset-0 z-0" aria-label="{{ $title }}"></a>
</section>
