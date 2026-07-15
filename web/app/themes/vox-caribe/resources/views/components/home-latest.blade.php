@props(['posts'])

<div>
  {{-- Section header --}}
  <div class="flex items-center justify-between mb-8">
    <h2 class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-base-content">
      {{ __('Últimas', 'vox-caribe') }}
    </h2>
    <a href="{{ get_permalink(get_option('page_for_posts')) }}" class="font-sans text-xs uppercase tracking-wider text-accent no-underline hover:underline">
      {{ __('ver todo', 'vox-caribe') }}
    </a>
  </div>

  {{-- Timeline: 4 posts, headline only --}}
  <div class="divide-y divide-base-300">
    @foreach ($posts as $post)
      @php
        $permalink = get_permalink($post);
        $title = get_the_title($post);
      @endphp

      <article class="py-4 first:pt-0 last:pb-0">
        <h3 class="font-serif text-lg leading-snug font-bold">
          <a href="{{ $permalink }}" class="text-base-content no-underline hover:text-accent transition-colors duration-200">
            {{ $title }}
          </a>
        </h3>
      </article>
    @endforeach
  </div>
</div>
