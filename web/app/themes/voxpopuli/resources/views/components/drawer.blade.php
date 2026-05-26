<aside {{ $attributes->merge(['class' => 'drawer-side z-50']) }} aria-label="{{ __('Menú de navegación lateral', 'voxpopuli') }}">
  <label for="main-drawer" aria-label="{{ __('Cerrar menú', 'voxpopuli') }}" class="drawer-overlay"></label>
  <div class="bg-base-200 min-h-full w-full max-w-full lg:w-80 lg:max-w-none flex flex-col justify-between border-r border-base-300 shadow-xl relative">
    
    {{-- Header del Drawer --}}
    <header class="navbar bg-base-100 border-b border-base-300 px-6 min-h-16 drop-shadow-md">
    <div class="navbar-start">
      <x-wordmark />
    </div>
      <div class="navbar-end">
        <label for="main-drawer" class="btn btn-ghost btn-circle hover:bg-base-300/60 text-base-content/70 cursor-pointer flex items-center justify-center" aria-label="{{ __('Cerrar menú', 'voxpopuli') }}" tabindex="0" role="button">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </label>
      </div>
    </header>

    {{-- Buscador del Drawer --}}
    <search class="px-6 py-4 border-b border-base-300/40 bg-base-100/30 block">
      <form role="search" method="get" class="relative flex items-center w-full" action="{{ home_url('/') }}">
        <label class="sr-only" for="drawer-search">{{ _x('Buscar:', 'label', 'voxpopuli') }}</label>
        <input
          id="drawer-search"
          type="search"
          class="input input-bordered w-full bg-base-100 placeholder:text-base-content/40 focus:border-secondary font-sans text-sm pl-10 pr-4"
          placeholder="{{ esc_attr_x('Buscar artículos…', 'placeholder', 'voxpopuli') }}"
          value="{{ get_search_query() }}"
          name="s">
        <span class="absolute left-3 text-base-content/40 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </span>
      </form>
    </search>

    {{-- Menú de Navegación Vertical --}}
    <nav class="flex-1 overflow-y-auto py-6" aria-label="{{ __('Navegación principal (móvil)', 'voxpopuli') }}">
      @if (has_nav_menu('primary_navigation'))
        @php
          $menu = wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'menu_class' => 'menu menu-vertical px-4 gap-2 text-base-content font-sans text-base font-bold',
            'echo' => false,
          ]);
        @endphp
        {!! $menu !!}
      @endif
    </nav>

    {{-- Footer del Drawer --}}
    <footer class="p-6 border-t border-base-300/60 bg-base-100/50 space-y-4">
      <small class="block font-sans text-[9px] uppercase tracking-widest text-base-content/70 font-extrabold">Esa es nuestra voz · Vox Populi</small>
      <p class="font-serif text-[11px] text-base-content/70 leading-relaxed">
        Periodismo independiente desde el Caribe colombiano. Investigación, análisis técnico del poder y memoria viva.
      </p>
    </footer>

  </div>
</aside>
