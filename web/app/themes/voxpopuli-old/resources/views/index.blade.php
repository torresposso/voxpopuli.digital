@extends('layouts.app')

@section('content')
  <x-hero />

  <div class="divide-y divide-base-300">
    @foreach ($sections as $section)
      @if (! empty($section['posts']))
        <x-section-block
          :slug="$section['slug']"
          :name="$section['name']"
          :description="$section['desc']"
          :icon="$section['icon']"
          :posts="$section['posts']"
          :alternate="$loop->iteration % 2 === 0"
        />
      @endif
    @endforeach
  </div>

  <div class="bg-base-100 py-12 lg:py-16">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <a href="{{ home_url('/category/destacadas/') }}"
         class="btn btn-primary rounded-full px-8 font-sans text-xs font-extrabold tracking-wider">
        Ver más artículos
      </a>
    </div>
  </div>
@endsection
