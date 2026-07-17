{{--
  3-Bar Header — Vox Populi Digital
  Inspired by actacosmica.com, using daisyUI navbar components.

  Bar 1 (top):    Date + utility links (secondary_navigation)
  Bar 2 (middle): Hamburger + wordmark + search
  Bar 3 (bottom): Category navigation (primary_navigation)
--}}

<header class="w-full">

    {{-- ═══════════════════════════════════════════════════════════
       BAR 1 — Top utility bar: date + links + social
       ═══════════════════════════════════════════════════════════ --}}
    <nav class="navbar bg-base-100 min-h-10 max-w-7xl mx-auto px-4">
        {{-- Left: date --}}
        <div class="navbar-start">
            <span class="text-neutral font-sans font-bold text-[0.65rem] uppercase tracking-wider">
                {{ now()->translatedFormat('l') }} {{ now()->format('j') }} {{ now()->translatedFormat('F') }}
                {{ now()->format('Y') }}
            </span>
        </div>

        {{-- Right: utility links + social icons --}}
        <div class="navbar-end gap-3">
            @if (has_nav_menu('secondary_navigation'))
                <ul
                    class="menu menu-horizontal px-1 gap-2 text-neutral font-sans font-bold text-[0.6875rem] uppercase tracking-wider">
                    {!! wp_nav_menu([
                        'theme_location' => 'secondary_navigation',
                        'container' => false,
                        'items_wrap' => '<li class="hidden sm:block">%3$s</li>',
                        'echo' => false,
                        'depth' => 1,
                    ]) !!}
                </ul>
            @else
                {{-- Fallback: static utility links --}}
                <span
                    class="hidden sm:inline text-neutral font-sans font-bold text-[0.6875rem] uppercase tracking-wider">
                    <a href="#" class="inline-flex items-center py-[13px] hover:text-accent transition-colors">Newsletter</a>
                    <span class="mx-1 opacity-30">·</span>
                    <a href="#" class="inline-flex items-center py-[13px] hover:text-accent transition-colors">Nosotros</a>
                    <span class="mx-1 opacity-30">·</span>
                    <a href="#" class="inline-flex items-center py-[13px] hover:text-accent transition-colors">Membresía</a>
                </span>
            @endif

            {{-- Social icons — 44x44px touch targets per WCAG 2.5.8 --}}
            <div class="flex items-center gap-1">
                {{-- X/Twitter --}}
                <a href="#" class="flex items-center justify-center min-w-[44px] min-h-[44px] text-base-content hover:text-accent transition-colors" aria-label="X">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                {{-- Instagram --}}
                <a href="#" class="flex items-center justify-center min-w-[44px] min-h-[44px] text-base-content hover:text-accent transition-colors" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                    </svg>
                </a>
                {{-- YouTube --}}
                <a href="#" class="flex items-center justify-center min-w-[44px] min-h-[44px] text-base-content hover:text-accent transition-colors" aria-label="YouTube">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
                        <path d="m10 15 5-3-5-3z" />
                    </svg>
                </a>
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════
       BAR 2 — Brand bar: hamburger + wordmark + search
       ═══════════════════════════════════════════════════════════ --}}

    <div class="border-2 border-base-300">
        <div class="navbar bg-base-100 min-h-14 max-w-7xl mx-auto">
            {{-- Left: hamburger (mobile) --}}
            <div class="navbar-start">
                <label for="main-navigation-drawer"
                    class="btn btn-square btn-ghost drawer-button lg:hidden cursor-pointer min-h-[44px] min-w-[44px]"
                    aria-label="{{ __('Abrir menú de navegación', 'voxpopuli') }}" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block h-6 w-6 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </label>
                <a href="{{ home_url('/') }}" class="!no-underline focus-visible:outline-primary inline-flex items-center min-h-[44px]">
                    <span class="font-sans font-extrabold text-[1.5rem] tracking-normal">
                        <span class="text-accent">Vox</span><span class="text-primary">Populi</span>
                    </span>
                </a>
            </div>

            {{-- Right: search icon --}}
            <div class="navbar-end">
                <button class="btn btn-square btn-ghost min-h-[44px] min-w-[44px]" aria-label="{{ __('Buscar', 'voxpopuli') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block h-5 w-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════
       BAR 3 — Category navigation (primary)
       ═══════════════════════════════════════════════════════════ --}}
    @if (has_nav_menu('primary_navigation'))
        <nav class="hidden lg:flex navbar" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
            <div class="navbar-center max-w-7xl mx-auto w-full">
                <ul
                    class="menu menu-horizontal text-[0.8125rem] font-sans font-bold uppercase tracking-wider gap-1 p-0">
                    {!! wp_nav_menu([
                        'theme_location' => 'primary_navigation',
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'echo' => false,
                        'depth' => 1,
                    ]) !!}
                </ul>
            </div>
        </nav>
    @endif

</header>
