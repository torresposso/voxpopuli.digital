@props(['posts'])

<div>
  {{-- Section header --}}
  <div class="flex items-center justify-between mb-8">
    <h2 class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-base-content">
      {{ __('Popular', 'vox-caribe') }}
    </h2>
  </div>

  {{-- Ranking: 5 posts, numbered, headline only --}}
  <ol class="list-none space-y-0 divide-y divide-base-300">
    @foreach ($posts as $index => $post)
      @php
        $permalink = get_permalink($post);
        $title = get_the_title($post);
        $number = $index + 1;
      @endphp

      <li class="flex gap-4 py-3 first:pt-0 last:pb-0">
        <span class="font-serif text-2xl font-bold leading-none text-base-content/20 tabular-nums shrink-0 w-8">
          {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}
        </span>
        <h3 class="font-serif text-base leading-snug font-bold pt-1">
          <a href="{{ $permalink }}" class="text-base-content no-underline hover:text-accent transition-colors duration-200">
            {{ $title }}
          </a>
        </h3>
      </li>
    @endforeach
  </ol>
</div>
