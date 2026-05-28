<header
    class="fixed top-0 left-0 right-0 z-50 bg-base-100/90 backdrop-blur-md h-16 border-b border-base-300 overflow-hidden shrink-0">
    {{-- Subtle performant noise texture overlay --}}
    <div class="absolute inset-0 opacity-[0.08] pointer-events-none mix-blend-overlay noise-overlay" aria-hidden="true">
    </div>

    <nav class="navbar max-w-[1440px] mx-auto h-full flex justify-between items-center relative z-10"
        aria-label="{{ __('Navegación principal', 'voxpopuli') }}">

        {{-- Left: Wordmark --}}
        <div class="navbar-start flex items-center">
            <x-wordmark />
        </div>

        {{-- Right: Quick Links & Hamburger Menu Button (Desktop & Mobile) --}}
        <div class="navbar-end flex items-center gap-3 sm:gap-4">
            {{-- Quick Links (Oculto en móvil, visible desde sm+ en desktop) --}}
            <x-quick-links class="hidden sm:flex mr-1 sm:mr-2" />

            {{-- Hamburger Circular Button --}}
            <label for="main-drawer"
                class="btn btn-ghost btn-circle drawer-button min-w-11 min-h-11 cursor-pointer flex items-center justify-center border border-primary/10 hover:border-primary/30 hover:bg-base-200 hover:scale-105 active:scale-95 transition-all duration-300 group/burger"
                aria-label="{{ __('Abrir menú', 'voxpopuli') }}" aria-controls="main-drawer" aria-expanded="false"
                tabindex="0" role="button">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-primary group-hover/burger:text-secondary transition-colors duration-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 9h16" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 15h16" />
                </svg>
            </label>
        </div>
    </nav>
</header>
