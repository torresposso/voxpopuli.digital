@php($pag = $pagination())
<article @php(post_class('h-entry max-w-3xl mx-auto'))>
  <header class="mb-8">
    <h1 class="p-name font-display text-3xl md:text-4xl lg:text-5xl font-black text-base-content leading-tight tracking-tighter">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <div class="e-content font-serif text-base text-base-content/90 leading-relaxed prose prose-sage">
    @php(the_content())
  </div>

  @if ($pag)
    <footer>
      <nav class="page-nav" aria-label="Page">
        {!! $pag !!}
      </nav>
    </footer>
  @endif

  @php(comments_template())
</article>
