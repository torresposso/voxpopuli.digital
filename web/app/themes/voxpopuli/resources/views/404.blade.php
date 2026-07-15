@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    @include('partials.page-header')

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, but the page you are trying to view does not exist.', 'voxpopuli') !!}
      </x-alert>

      {!! get_search_form(false) !!}
    @endif
  </div>
@endsection
