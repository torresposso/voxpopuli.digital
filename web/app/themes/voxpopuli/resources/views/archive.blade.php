@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    @include('partials.page-header')

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'voxpopuli') !!}
      </x-alert>

      {!! get_search_form(false) !!}
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @while(have_posts()) @php(the_post())
        @include('partials.content')
      @endwhile
    </div>

    {!! get_the_posts_navigation() !!}
  </div>
@endsection
