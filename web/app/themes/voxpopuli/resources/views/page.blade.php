@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    @while(have_posts()) @php(the_post())
      @include('partials.page-header')
      @includeFirst(['partials.content-page', 'partials.content'])
    @endwhile
  </div>
@endsection
