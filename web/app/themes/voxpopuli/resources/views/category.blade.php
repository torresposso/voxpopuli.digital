@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    <div class="page-header border-b-2 border-base-300 pb-4">
      <h1 class="font-display font-extrabold text-[clamp(2rem,5vw,2.75rem)] tracking-tighter">{{ $categoryName }}</h1>
      @if ($categoryDescription)
        <p class="font-serif text-lg leading-relaxed text-neutral mt-2 max-w-prose">{{ $categoryDescription }}</p>
      @endif
    </div>

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'voxpopuli') !!}
      </x-alert>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      @while(have_posts()) @php(the_post())
        @include('partials.content')
      @endwhile
    </div>

    {!! get_the_posts_navigation() !!}
  </div>
@endsection
