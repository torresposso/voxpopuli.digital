<header class="sticky top-0 z-50 bg-base-100 border-b border-base-300">
  <div class="mx-auto max-w-7xl px-4 flex items-center justify-between h-16">
    <div class="flex items-center gap-4">
      {{-- Drawer toggle hamburger (placeholder) --}}
      <button class="lg:hidden btn btn-ghost btn-square" aria-label="{{ __('Menú', 'vox-caribe') }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>

      {{-- Wordmark --}}
      <a href="{{ home_url() }}" class="text-xl font-bold font-sans no-underline">
        <span class="text-accent">Vox</span> <span class="text-primary">Populi</span>
        <span class="text-primary text-xs font-normal tracking-widest hidden sm:inline">digital</span>
      </a>
    </div>

    {{-- Desktop navigation --}}
    <nav class="hidden lg:flex items-center gap-6 font-sans text-sm uppercase tracking-wider">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'container' => false,
          'menu_class' => 'flex gap-6 list-none',
          'echo' => false,
          'fallback_cb' => false,
        ]) !!}
      @endif
    </nav>
  </div>
</header>
