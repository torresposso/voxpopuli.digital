<header class="fixed top-0 left-0 right-0 z-50 bg-secondary/90 backdrop-blur-md h-16 border-b border-white/20 overflow-hidden shrink-0">
  {{-- Subtle performant noise texture overlay --}}
  <div class="absolute inset-0 opacity-[0.08] pointer-events-none mix-blend-overlay noise-overlay" aria-hidden="true"></div>

  <nav class="navbar max-w-[1440px] mx-auto px-4 md:px-8 h-full flex justify-between items-center relative z-10" aria-label="{{ __('Navegación principal', 'voxpopuli') }}">
    
    {{-- Left: Wordmark (Specifically styled for secondary orange background) --}}
    <div class="navbar-start flex items-center">
      <x-wordmark on-secondary />
    </div>

    {{-- Center/Right: Desktop Navigation Menu --}}
    <div class="hidden md:flex items-center gap-6 font-sans font-extrabold text-[10px] tracking-[0.2em] text-secondary-content">
      <a href="{{ home_url('/categoria/investigacion') }}" class="relative py-1.5 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full hover:text-primary transition-colors duration-300 uppercase">
        {{ __('Investigación', 'voxpopuli') }}
      </a>
      <a href="{{ home_url('/categoria/analisis') }}" class="relative py-1.5 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full hover:text-primary transition-colors duration-300 uppercase">
        {{ __('Análisis', 'voxpopuli') }}
      </a>
      <a href="{{ home_url('/categoria/opinion') }}" class="relative py-1.5 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full hover:text-primary transition-colors duration-300 uppercase">
        {{ __('Opinión', 'voxpopuli') }}
      </a>
      <a href="{{ home_url('/categoria/deportes') }}" class="relative py-1.5 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full hover:text-primary transition-colors duration-300 uppercase">
        {{ __('Deportes', 'voxpopuli') }}
      </a>
      <a href="{{ home_url('/categoria/ahora') }}" class="relative py-1.5 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-primary after:transition-all after:duration-300 hover:after:w-full hover:text-primary transition-colors duration-300 uppercase">
        {{ __('Ahora', 'voxpopuli') }}
      </a>

      {{-- Thin vertical divider --}}
      <span class="w-[1px] h-4 bg-white/25"></span>

      {{-- Search icon --}}
      <button class="btn btn-ghost btn-circle btn-xs hover:bg-white/10 hover:text-primary text-secondary-content hover:scale-110 active:scale-95 transition-all duration-300" aria-label="{{ __('Buscar', 'voxpopuli') }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </button>

      {{-- Language select circles --}}
      <div class="flex items-center gap-1.5 ml-2">
        <span class="w-7 h-7 rounded-full border border-primary bg-primary text-primary-content text-[8px] font-sans font-extrabold flex items-center justify-center cursor-pointer select-none hover:scale-105 active:scale-95 transition-all duration-300 shadow-sm">
          ES
        </span>
        <span class="w-7 h-7 rounded-full border border-white/30 text-white/80 hover:bg-white/15 hover:text-white text-[8px] font-sans font-extrabold flex items-center justify-center cursor-pointer select-none hover:scale-105 active:scale-95 transition-all duration-300 shadow-sm">
          EN
        </span>
      </div>
    </div>

    {{-- Right: Mobile Hamburger Menu Button --}}
    <div class="navbar-end flex md:hidden gap-2">
      <label for="main-drawer" class="btn btn-ghost btn-circle drawer-button min-w-11 min-h-11 cursor-pointer flex items-center justify-center text-secondary-content hover:bg-white/10 hover:scale-105 active:scale-95 transition-all duration-300" aria-label="{{ __('Abrir menú', 'voxpopuli') }}" aria-controls="main-drawer" aria-expanded="false" tabindex="0" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>
    </div>

  </nav>
</header>
