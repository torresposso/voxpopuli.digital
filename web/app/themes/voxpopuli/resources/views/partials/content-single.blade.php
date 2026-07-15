<article @php(post_class('h-entry'))>
  <header>
    <h1 class="p-name font-display font-extrabold text-[clamp(2rem,5vw,2.75rem)] tracking-tighter">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <x-prose as="div" class="e-content">
    @php(the_content())
  </x-prose>

  @if ($pagination())
    <footer class="border-t-2 border-base-300 pt-8">
      <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
      </nav>
    </footer>
  @endif

  @php(comments_template())
</article>
