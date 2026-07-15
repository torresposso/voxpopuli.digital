@extends('layouts.app')

@section('content')
  <div class="mx-auto max-w-7xl px-4 py-12">
    @if (! have_posts())
      <article class="prose max-w-none">
        <p>{{ __('No se encontraron publicaciones.', 'vox-caribe') }}</p>
      </article>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @while(have_posts()) @php(the_post())
          <article @php(post_class('group'))>
            <a href="{{ get_permalink() }}" class="no-underline text-inherit">
              @if (has_post_thumbnail())
                {!! the_post_thumbnail('large', ['class' => 'w-full h-auto', 'loading' => 'lazy']) !!}
              @endif
              <h2 class="font-serif text-2xl leading-tight mt-4 text-base-content group-hover:text-accent transition-colors">
                {{ get_the_title() }}
              </h2>
              <p class="font-sans text-sm text-base-content/60 mt-2">
                {{ get_the_author() }} · {{ get_the_date() }}
              </p>
            </a>
          </article>
        @endwhile
      </div>

      <nav class="mt-12 font-sans">
        {!! the_posts_navigation() !!}
      </nav>
    @endif
  </div>
@endsection
