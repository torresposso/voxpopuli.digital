<div class="max-w-7xl mx-auto px-4 pb-24 animate-fade-in-up">
  <div class="max-w-2xl mx-auto text-center py-12">
    <div class="w-16 h-16 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6 text-neutral border border-base-300 shadow-inner">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 opacity-70">
        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
      </svg>
    </div>
    
    <p class="font-serif text-lg md:text-xl text-neutral/85 leading-relaxed italic mb-8">
      {{ __('No hemos encontrado crónicas o investigaciones que coincidan con tu búsqueda. Intentá con otras palabras clave o explorá nuestro archivo sugerido.', 'voxpopuli') }}
    </p>

    <form role="search" method="get" class="max-w-lg mx-auto relative group" action="{{ home_url('/') }}">
      <div class="relative">
        <input type="search" class="w-full bg-base-100 border border-base-300 rounded-lg py-4 pl-5 pr-14 text-sm text-base-content font-sans focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-300 shadow-sm" placeholder="{{ __('Buscar crónicas o investigaciones...', 'voxpopuli') }}" value="{{ get_search_query(false) }}" name="s" required />
        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-primary hover:text-secondary transition-colors duration-300 flex items-center justify-center p-1" aria-label="{{ __('Buscar', 'voxpopuli') }}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
          </svg>
        </button>
      </div>
    </form>
  </div>

  @php
  $recent_posts = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
  ]);
  @endphp

  @if ($recent_posts->have_posts())
    <div class="mt-20 border-t border-base-300 pt-16">
      <div class="text-center mb-12">
        <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary block mb-2">
          {{ __('Lecturas recomendadas', 'voxpopuli') }}
        </span>
        <h3 class="font-display text-2xl md:text-3xl font-black text-primary tracking-tight">
          {{ __('Explorá nuestro archivo reciente', 'voxpopuli') }}<span class="text-secondary ml-0.5">.:</span>
        </h3>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
        @while ($recent_posts->have_posts()) @php($recent_posts->the_post())
          @include('partials.content-search')
        @endwhile
      </div>
    </div>
    @php(wp_reset_postdata())
  @endif
</div>
