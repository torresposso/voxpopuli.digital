<footer class="bg-primary text-primary-content px-4 py-8">
  <div class="max-w-7xl mx-auto flex flex-col items-center gap-6">
    {{-- Wordmark --}}
    <a href="{{ home_url('/') }}" class="!no-underline">
      <span class="font-sans font-extrabold text-[1.5rem] tracking-normal">
        <span class="text-white">Vox</span>&#8202;<span class="text-accent">Populi</span>
      </span>
    </a>

    {{-- Secondary navigation --}}
    @if (has_nav_menu('secondary_navigation'))
      <nav aria-label="{{ wp_get_nav_menu_name('secondary_navigation') }}">
        <ul class="menu menu-horizontal gap-2 text-sm opacity-80 p-0">
          {!! wp_nav_menu([
            'theme_location' => 'secondary_navigation',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'echo'           => false,
            'depth'          => 1,
          ]) !!}
        </ul>
      </nav>
    @endif

    {{-- Widgets --}}
    @php(dynamic_sidebar('sidebar-footer'))

    {{-- Copyright --}}
    <p class="text-xs opacity-80">&copy; {{ date('Y') }} Vox Populi Digital. {{ __('Todos los derechos reservados.', 'voxpopuli') }}</p>
  </div>
</footer>
