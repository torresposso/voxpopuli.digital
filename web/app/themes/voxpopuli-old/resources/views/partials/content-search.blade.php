<article @php(post_class('mb-6 pb-6 border-b border-base-300 last:border-0'))>
  <header>
    <h2 class="entry-title">
      <a href="{{ get_permalink() }}" class="font-display text-lg font-bold text-base-content">
        {!! $title !!}
      </a>
    </h2>

    @includeWhen(get_post_type() === 'post', 'partials.entry-meta')
  </header>

  <div class="entry-summary font-serif text-sm text-base-content/70">
    @php(the_excerpt())
  </div>

  <div class="text-secondary font-sans text-[10px]">
    {{ get_the_date() }}
  </div>
</article>
