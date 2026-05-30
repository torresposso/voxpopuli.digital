@extends('layouts.app')

@section('content')
  <div class="border-b border-base-300 pb-10 mb-12 bg-base-200/50">
    <div class="max-w-7xl mx-auto px-4 pt-12 md:pt-16">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary">
        {{ __('Archivo & Búsqueda', 'voxpopuli') }}
      </span>
      <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-black text-primary tracking-tight mt-2 leading-none">
        «{{ get_search_query() }}»<span class="text-secondary ml-1 font-display">.:</span>
      </h1>
      <p class="font-serif text-sm md:text-base text-neutral mt-4 italic">
        @if (have_posts())
          {{ sprintf(_n('Se encontró %d crónica o investigación archivada.', 'Se encontraron %d crónicas e investigaciones archivadas.', $GLOBALS['wp_query']->found_posts, 'voxpopuli'), $GLOBALS['wp_query']->found_posts) }}
        @else
          {{ __('No hemos encontrado coincidencias en nuestro archivo histórico.', 'voxpopuli') }}
        @endif
      </p>

      <div class="flex flex-wrap gap-2 items-center mt-6">
        <span class="font-sans text-[10px] font-extrabold uppercase tracking-wider text-neutral mr-2">
          {{ __('Temas sugeridos:', 'voxpopuli') }}
        </span>
        <a href="{{ home_url('/?s=Política') }}" class="badge badge-outline border-base-300 text-neutral hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-sans text-[10px] font-extrabold uppercase tracking-wider px-3 py-2.5 rounded-sm">
          #Política
        </a>
        <a href="{{ home_url('/?s=Caribe') }}" class="badge badge-outline border-base-300 text-neutral hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-sans text-[10px] font-extrabold uppercase tracking-wider px-3 py-2.5 rounded-sm">
          #Caribe
        </a>
        <a href="{{ home_url('/?s=Investigación') }}" class="badge badge-outline border-base-300 text-neutral hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-sans text-[10px] font-extrabold uppercase tracking-wider px-3 py-2.5 rounded-sm">
          #Investigación
        </a>
        <a href="{{ home_url('/?s=Memoria') }}" class="badge badge-outline border-base-300 text-neutral hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 font-sans text-[10px] font-extrabold uppercase tracking-wider px-3 py-2.5 rounded-sm">
          #Memoria
        </a>
      </div>
    </div>
  </div>

  @if (have_posts())
    <div class="max-w-7xl mx-auto px-4 pb-20">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12 animate-fade-in-up">
        @while(have_posts()) @php(the_post())
          @include('partials.content-search')
        @endwhile
      </div>

      <div class="mt-20 pt-10 border-t border-base-300 flex justify-center">
        <div class="join border border-base-300 rounded-lg overflow-hidden bg-base-100 divide-x divide-base-300 shadow-sm">
          {!! paginate_links([
            'prev_text' => '←',
            'next_text' => '→',
            'type' => 'plain',
          ]) !!}
        </div>
      </div>
    </div>
  @else
    @include('partials.content-search-empty')
  @endif
@endsection
