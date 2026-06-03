@once
  @push('styles')
    <style>
      /* Drawer premium nav menu */
      .drawer-nav-menu ul {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        counter-reset: menu-counter;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }
      .drawer-nav-menu li {
        position: relative;
        counter-increment: menu-counter;
        overflow: hidden;
        list-style: none !important;
        opacity: 0;
        animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      }
      .drawer-nav-menu li a {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.875rem 1.25rem !important;
        font-family: var(--font-sans) !important;
        font-size: 0.95rem !important;
        font-weight: 800 !important;
        color: var(--color-base-content) !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border-radius: var(--radius-box) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        border: 1px solid transparent !important;
      }
      .drawer-nav-menu li a::before {
        content: "0" counter(menu-counter) ".";
        font-family: var(--font-display) !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        color: var(--color-secondary) !important;
        opacity: 0.7;
        margin-right: 0.5rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }
      .drawer-nav-menu li a::after {
        content: "→";
        font-family: var(--font-sans) !important;
        font-size: 1.2rem !important;
        font-weight: 400 !important;
        color: var(--color-primary) !important;
        opacity: 0;
        transform: translateX(-12px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .drawer-nav-menu li a:hover {
        background-color: oklch(from var(--color-primary) 96.5% 0.015 281.85) !important;
        color: var(--color-primary) !important;
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
        border-color: oklch(from var(--color-primary) 90% 0.02 281.85) !important;
        box-shadow: 0 4px 12px oklch(from var(--color-primary) 0.05 0.01 h / 0.03);
      }
      .drawer-nav-menu li a:hover::before {
        opacity: 1;
        transform: scale(1.15) rotate(-3deg);
        color: var(--color-primary) !important;
      }
      .drawer-nav-menu li a:hover::after {
        opacity: 1;
        transform: translateX(0);
      }
      
      /* Staggered entry animation */
      .drawer-nav-menu li:nth-child(1) { animation-delay: 80ms; }
      .drawer-nav-menu li:nth-child(2) { animation-delay: 140ms; }
      .drawer-nav-menu li:nth-child(3) { animation-delay: 200ms; }
      .drawer-nav-menu li:nth-child(4) { animation-delay: 260ms; }
      .drawer-nav-menu li:nth-child(5) { animation-delay: 320ms; }
      .drawer-nav-menu li:nth-child(6) { animation-delay: 380ms; }

      /* Premium drawer featured post card */
      .drawer-featured-card {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid var(--color-base-300);
      }
      .drawer-featured-card:hover {
        transform: translateY(-2px);
        border-color: oklch(from var(--color-primary) 85% 0.02 h);
        box-shadow: 0 12px 30px oklch(from var(--color-primary) 10% 0.02 281.85 / 0.06);
      }
      .drawer-featured-card img {
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .drawer-featured-card:hover img {
        transform: scale(1.04);
        filter: grayscale(0%);
      }

      /* Premium newsletter input */
      .drawer-newsletter-input {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .drawer-newsletter-input:focus {
        outline: none !important;
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 3px oklch(from var(--color-primary) 0.85 0.02 h / 0.15) !important;
      }
    </style>
  @endpush
@endonce

<aside {{ $attributes->merge(['class' => 'drawer-side z-50']) }} aria-label="{{ __('Menú de navegación lateral', 'voxpopuli') }}">
  <label for="main-drawer" aria-label="{{ __('Cerrar menú', 'voxpopuli') }}" class="drawer-overlay"></label>
  <div class="bg-base-200 min-h-full w-full max-w-full sm:w-[460px] sm:max-w-none flex flex-col justify-between border-r border-base-300 shadow-2xl relative overflow-hidden">
    
    {{-- Subtle performant noise texture overlay inside drawer --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none mix-blend-overlay noise-overlay" aria-hidden="true"></div>

    <div class="flex flex-col flex-1 relative z-10 overflow-y-auto">
      
      {{-- Top Meta Info Banner: Caribbean Date & Location --}}
      <div class="px-6 py-2 bg-primary text-primary-content font-sans text-[8px] uppercase tracking-[0.25em] flex justify-between items-center select-none shrink-0 border-b border-primary/20">
        <span class="flex items-center gap-1.5 font-extrabold">
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
          {{ __('Caribe Colombiano', 'voxpopuli') }}
        </span>
        <span class="opacity-90 font-medium">{{ wp_date('l, j \d\e F, Y') }}</span>
      </div>

      {{-- Header del Drawer --}}
      <header class="navbar bg-base-100/90 backdrop-blur-md border-b border-base-300 px-6 min-h-18 flex items-center justify-between shrink-0">
        <div class="navbar-start">
          <x-wordmark />
        </div>
        <div class="navbar-end">
          <label for="main-drawer" class="btn btn-ghost btn-circle hover:bg-primary/10 hover:text-primary hover:scale-105 active:scale-95 text-base-content/70 cursor-pointer flex items-center justify-center transition-all duration-300" aria-label="{{ __('Cerrar menú', 'voxpopuli') }}" tabindex="0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </label>
        </div>
      </header>

      {{-- Buscador del Drawer --}}
      <search class="px-6 py-5 border-b border-base-300/40 bg-base-100/30 block shrink-0">
        <form role="search" method="get" class="relative flex items-center w-full" action="{{ home_url('/') }}">
          <label class="sr-only" for="drawer-search">{{ _x('Buscar:', 'label', 'voxpopuli') }}</label>
          <input
            id="drawer-search"
            type="search"
            class="input input-bordered w-full bg-base-100 placeholder:text-base-content/30 focus:border-primary/50 focus:ring-2 focus:ring-primary/10 font-sans text-sm pl-10 pr-4 transition-all duration-300 rounded-lg"
            placeholder="{{ esc_attr_x('Buscar artículos…', 'placeholder', 'voxpopuli') }}"
            value="{{ get_search_query(false) }}"
            name="s">
          <span class="absolute left-3 text-base-content/35 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </span>
        </form>
      </search>

      {{-- Accesos Rápidos (Solo visibles en móviles, integrados desde la cabecera original) --}}
      <x-quick-links class="flex sm:hidden px-6 py-4 border-b border-base-300/40 bg-base-100/10 shrink-0 justify-around" />

      {{-- Menú de Navegación Vertical --}}
      <nav class="py-6 drawer-nav-menu shrink-0" aria-label="{{ __('Navegación principal (móvil)', 'voxpopuli') }}">
        @if (has_nav_menu('primary_navigation'))
          @php
            $menu = wp_nav_menu([
              'theme_location' => 'primary_navigation',
              'menu_class' => 'px-6',
              'echo' => false,
            ]);
          @endphp
          {!! $menu !!}
        @endif
      </nav>

      {{-- Elegant Divider with Logo/Stylized Icon --}}
      <div class="px-6 py-2 flex items-center gap-4 shrink-0">
        <div class="h-[1px] flex-1 bg-base-300"></div>
        <span class="font-serif text-[10px] italic tracking-widest text-base-content/30 font-bold">V . P</span>
        <div class="h-[1px] flex-1 bg-base-300"></div>
      </div>

      {{-- Premium Dynamic Featured Post Section --}}
      @php
        $drawer_posts = get_posts([
          'numberposts' => 1,
          'post_status' => 'publish',
          'no_found_rows' => true
        ]);
      @endphp
      @if(!empty($drawer_posts))
        @php
          $feat_post = $drawer_posts[0];
          $feat_title = get_the_title($feat_post);
          $feat_link = get_permalink($feat_post);
          $feat_date = get_the_date('', $feat_post);
          $feat_cats = get_the_category($feat_post);
          $feat_cat_name = !empty($feat_cats) ? $feat_cats[0]->name : __('Destacado', 'voxpopuli');
          $feat_thumb = get_the_post_thumbnail_url($feat_post, 'medium');
        @endphp
        <div class="px-6 py-6 bg-base-100/30 border-b border-base-300/40 shrink-0">
          <h3 class="font-sans text-[9px] uppercase tracking-[0.2em] text-primary font-extrabold mb-4 flex items-center gap-2">
            <span>{{ __('Historia Destacada', 'voxpopuli') }}</span>
            <span class="inline-block w-4 h-[1px] bg-primary/30"></span>
          </h3>
          
          <a href="{{ $feat_link }}" class="drawer-featured-card flex gap-4 p-3 bg-base-100 rounded-xl overflow-hidden hover:no-underline group">
            @if($feat_thumb)
              <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-base-200 relative">
                <img src="{{ $feat_thumb }}" alt="{{ esc_attr($feat_title) }}" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-500">
              </div>
            @else
              <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 bg-primary/5 flex items-center justify-center font-serif text-primary/30 text-xl font-bold border border-primary/15">
                VP
              </div>
            @endif
            <div class="flex flex-col justify-center min-w-0">
              <span class="font-sans text-[8px] font-extrabold uppercase tracking-widest text-secondary mb-1">
                {{ $feat_cat_name }}
              </span>
              <h4 class="font-display text-sm font-bold text-base-content leading-snug group-hover:text-primary transition-colors duration-300 line-clamp-2">
                {{ $feat_title }}
              </h4>
              <span class="font-sans text-[9px] text-base-content/40 mt-1.5 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $feat_date }}
              </span>
            </div>
          </a>
        </div>
      @endif

      {{-- Premium Minimalist Newsletter Signup --}}
      <div class="px-6 py-6 border-b border-base-300/40 shrink-0 bg-primary/[0.01]">
        <div class="space-y-1 mb-4">
          <h3 class="font-sans text-[9px] uppercase tracking-[0.2em] text-primary font-extrabold">
            {{ __('Boletín Independiente', 'voxpopuli') }}
          </h3>
          <p class="font-serif text-[10px] text-base-content/60 italic leading-relaxed">
            {{ __('Recibe nuestras crónicas y análisis técnicos directamente en tu correo.', 'voxpopuli') }}
          </p>
        </div>
        
        <form class="flex gap-2">
          <input 
            type="email" 
            name="email"
            id="drawer-email"
            placeholder="{{ esc_attr__('Tu correo electrónico…', 'voxpopuli') }}" 
            class="drawer-newsletter-input input input-bordered flex-1 bg-base-100 font-sans text-xs focus:border-primary px-3 rounded-lg h-9" 
            required>
          <button 
            type="submit" 
            class="btn btn-primary min-h-9 h-9 px-4 rounded-lg font-sans text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300"
            aria-label="{{ __('Suscribirse', 'voxpopuli') }}">
            <span>{{ __('Unirse', 'voxpopuli') }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </form>
      </div>

    </div>

    {{-- Footer del Drawer --}}
    <footer class="p-6 border-t border-base-300/60 bg-base-100/60 backdrop-blur-sm space-y-4 relative z-10 shrink-0">
      <div class="flex items-center gap-2">
        <span class="h-1.5 w-1.5 rounded-full bg-secondary animate-pulse"></span>
        <small class="block font-sans text-[9px] uppercase tracking-[0.2em] text-primary font-extrabold">Vox Populi Digital · Periodismo de Datos</small>
      </div>
      <p class="font-serif text-[11px] text-base-content/70 leading-relaxed">
        Investigamos de manera independiente desde el Caribe colombiano. Análisis técnico del poder, narración rigurosa de las realidades regionales y memoria viva de nuestro territorio.
      </p>
      
      {{-- Redes Sociales en el Drawer --}}
      <div class="flex items-center justify-between pt-2 border-t border-base-300/30">
        <div class="flex items-center gap-3 text-base-content/50">
          <a href="#" class="hover:text-primary transition-colors duration-300 hover:scale-110" aria-label="X (Twitter)">
            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="#" class="hover:text-primary transition-colors duration-300 hover:scale-110" aria-label="Instagram">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
          </a>
          <a href="#" class="hover:text-primary transition-colors duration-300 hover:scale-110" aria-label="YouTube">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
          </a>
        </div>
        <span class="font-sans text-[8px] uppercase tracking-wider text-base-content/40">&copy; {{ date('Y') }} VP Digital</span>
      </div>
    </footer>

  </div>
</aside>
