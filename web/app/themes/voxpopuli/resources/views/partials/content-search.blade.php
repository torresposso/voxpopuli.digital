<article @php(post_class('border-b-2 border-base-300 last:border-0 pb-6 mb-6'))>
  <header>
    <h2 class="entry-title font-display font-bold text-[1.5rem]">
      <a href="{{ get_permalink() }}" class="hover:text-accent transition-colors duration-[200ms]">
        {!! $title !!}
      </a>
    </h2>

    @includeWhen(get_post_type() === 'post', 'partials.entry-meta')
  </header>

  <div class="entry-summary font-serif text-[1rem] leading-relaxed line-clamp-3">
    @php(the_excerpt())
  </div>

  @if (get_post_type() === 'post')
    <time class="font-sans text-[0.75rem] uppercase tracking-wider text-neutral" datetime="{{ get_post_time('c', true) }}">
      {{ get_the_date() }}
    </time>
  @endif
</article>
