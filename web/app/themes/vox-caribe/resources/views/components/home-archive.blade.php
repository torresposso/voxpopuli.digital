@props(['posts'])

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4">
    {{-- Section header --}}
    <div class="flex items-center justify-between mb-8">
      <h2 class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-base-content">
        {{ __('Archivo', 'vox-caribe') }}
      </h2>
      <a href="{{ get_year_link(get_the_time('Y')) }}" class="font-sans text-xs uppercase tracking-wider text-accent no-underline hover:underline">
        {{ __('ver todo', 'vox-caribe') }}
      </a>
    </div>

    {{-- Archive grid: 3 posts with images --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach ($posts as $post)
        @php
          $permalink = get_permalink($post);
          $title = get_the_title($post);
          $thumbnailId = get_post_thumbnail_id($post);
          $year = get_the_time('Y', $post);
        @endphp

        <article class="group">
          <a href="{{ $permalink }}" class="block overflow-hidden" aria-hidden="true" tabindex="-1">
            @if ($thumbnailId)
              <div class="aspect-[4/3] overflow-hidden">
                {!! wp_get_attachment_image($thumbnailId, 'medium_large', false, [
                  'class' => 'w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
                  'loading' => 'lazy',
                ]) !!}
              </div>
            @endif
          </a>

          <div class="mt-4">
            <span class="font-sans text-xs text-base-content/50 uppercase tracking-wider">
              {{ $year }}
            </span>
            <h3 class="font-serif text-lg leading-snug mt-1 font-bold">
              <a href="{{ $permalink }}" class="text-base-content no-underline transition-colors duration-200 ease-out group-hover:text-accent">
                <span class="line-clamp-2">{{ $title }}</span>
              </a>
            </h3>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>
