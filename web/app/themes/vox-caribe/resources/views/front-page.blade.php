@extends('layouts.app')

@section('content')
  {{-- Hero: Sticky post full-bleed --}}
  @isset($hero)
    <x-home-hero :post="$hero" />
  @endisset

  {{-- Story Grid: 4 cols × 2 rows with thumbnails --}}
  @if(! empty($storyGrid))
    <x-home-story-grid :posts="$storyGrid" />
  @endif

  {{-- Archive: 3 posts from same month in previous years --}}
  @if(! empty($archivePosts))
    <x-home-archive :posts="$archivePosts" />
  @endif

  {{-- Podcasts: Placeholder for future content --}}
  <x-home-podcasts :podcasts="$podcasts" />

  {{-- Latest + Popular: side by side (2/3 + 1/3) --}}
  @if(! empty($latestPosts) || ! empty($popularPosts))
    <section class="py-12 md:py-16">
      <div class="mx-auto max-w-7xl px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
          <div class="lg:col-span-2">
            @if(! empty($latestPosts))
              <x-home-latest :posts="$latestPosts" />
            @endif
          </div>
          <div>
            @if(! empty($popularPosts))
              <x-home-popular :posts="$popularPosts" />
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- Newsletter: Inline signup placeholder --}}
  <x-home-newsletter />
@endsection
