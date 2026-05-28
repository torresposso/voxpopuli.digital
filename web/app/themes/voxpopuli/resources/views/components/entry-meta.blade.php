@props([
  'category' => null,
  'readingTime' => null,
  'authorName' => get_the_author(),
  'authorUrl' => get_author_posts_url(get_the_author_meta('ID')),
  'date' => get_the_date(),
  'publishedAt' => get_post_time('c', true),
])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center font-sans text-xs md:text-sm font-semibold text-muted/95 border-y border-base-300/80 py-4 gap-y-2']) }}>
  <div class="flex items-center">
    <time class="dt-published" datetime="{{ $publishedAt }}">
      {{ $date }}
    </time>
  </div>

  <span class="text-secondary font-display font-bold mx-3 select-none" aria-hidden="true">.:</span>

  <div class="flex items-center">
    <span class="mr-1 opacity-75">{{ __('Por', 'voxpopuli') }}</span>
    <a href="{{ $authorUrl }}" 
       class="p-author h-card text-current hover:text-secondary font-extrabold underline decoration-current/30 hover:decoration-secondary transition-all duration-300">
      {{ $authorName }}
    </a>
  </div>

  @if ($readingTime)
    <span class="text-secondary font-display font-bold mx-3 select-none" aria-hidden="true">.:</span>

    <div class="flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ $readingTime }}</span>
    </div>
  @endif
</div>
