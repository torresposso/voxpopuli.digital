@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <x-alert type="warning">
    {!! __('Sorry, but the page you are trying to view does not exist.', 'voxpopuli') !!}
  </x-alert>

  {!! get_search_form(false) !!}
@endsection
