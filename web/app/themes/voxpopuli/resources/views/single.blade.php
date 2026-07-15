@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
    @endwhile
  </div>
@endsection
