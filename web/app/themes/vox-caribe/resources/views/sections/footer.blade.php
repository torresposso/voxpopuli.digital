<footer class="bg-primary text-primary-content">
  <div class="mx-auto max-w-7xl px-4 py-12">
    {{-- Wordmark --}}
    <div class="text-center mb-8">
      <span class="text-2xl font-bold font-sans">
        <span class="text-base-100">Vox</span> <span class="text-accent">Populi</span>
      </span>
      <p class="text-primary-content/70 text-xs tracking-widest uppercase mt-1 font-sans">digital</p>
    </div>

    {{-- Footer navigation placeholders --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm font-sans">
      <div>
        <h4 class="font-bold uppercase tracking-wider text-accent mb-4">{{ __('Secciones', 'vox-caribe') }}</h4>
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'container' => false,
            'menu_class' => 'list-none space-y-2',
            'echo' => false,
            'fallback_cb' => false,
            'depth' => 1,
          ]) !!}
        @endif
      </div>
      <div>
        <h4 class="font-bold uppercase tracking-wider text-accent mb-4">{{ __('El Medio', 'vox-caribe') }}</h4>
        <ul class="list-none space-y-2">
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Nosotros', 'vox-caribe') }}</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Contacto', 'vox-caribe') }}</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Equipo', 'vox-caribe') }}</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold uppercase tracking-wider text-accent mb-4">{{ __('Síguenos', 'vox-caribe') }}</h4>
        <ul class="list-none space-y-2">
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">Twitter / X</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">Instagram</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">WhatsApp</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold uppercase tracking-wider text-accent mb-4">{{ __('Legal', 'vox-caribe') }}</h4>
        <ul class="list-none space-y-2">
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Términos', 'vox-caribe') }}</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Privacidad', 'vox-caribe') }}</a></li>
          <li><a href="#" class="text-primary-content/80 hover:text-accent no-underline">{{ __('Licencia', 'vox-caribe') }}</a></li>
        </ul>
      </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-primary-content/20 mt-8 pt-8 text-center text-xs text-primary-content/60 font-sans">
      &copy; {{ date('Y') }} VoxPopuli Digital. {{ __('Todos los derechos reservados.', 'vox-caribe') }}
    </div>
  </div>
</footer>
