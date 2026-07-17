<span class="font-sans font-semibold text-[0.75rem] uppercase tracking-wider">
  <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
    {{ get_the_date() }}
  </time>
  <span aria-hidden="true">·</span>
  <span>{{ __('By', 'voxpopuli') }}</span>
  <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card text-primary hover:text-accent transition-colors">
    {{ get_the_author() }}
  </a>
</span>
