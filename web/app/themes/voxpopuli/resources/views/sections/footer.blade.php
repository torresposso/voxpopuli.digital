{{--
  ═══════════════════════════════════════════════════════════════
  Footer — Vox Populi Digital
  Fondo Azul Caribe, texto Naranja del Caribe (Mirror Rule).
  Stacked wordmark autorizado para footer (Vox/Populi en dos líneas).
  ═══════════════════════════════════════════════════════════════
--}}
<footer class="bg-primary text-primary-content">
  {{-- Top border sutil — única concesión decorativa sobre fondo oscuro --}}
  <div class="h-px bg-base-100/10" role="presentation"></div>

  <div class="max-w-7xl mx-auto px-4 py-12 lg:py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-16">

      {{-- ═══════════════════════════════════════════════════════
         Columna 1: Marca — Stacked wordmark + tagline editorial
         ═══════════════════════════════════════════════════════ --}}
      <div>
        <a href="{{ home_url('/') }}" class="inline-block no-underline group focus-visible:outline-white focus-visible:outline-2 focus-visible:outline-offset-4 rounded-sm">
        <div class="font-sans font-extrabold text-[2.25rem] leading-[0.85] tracking-tight">
          <div class="text-base-100 group-hover:opacity-90 transition-opacity">Vox</div>
          <div class="text-accent group-hover:opacity-90 transition-opacity">Populi</div>
        </div>
        <span class="font-sans font-normal text-[0.6875rem] tracking-[0.15em] text-base-100/60 uppercase">digital</span>
        </a>
        <p class="font-serif text-sm text-base-100/70 leading-relaxed mt-5 max-w-xs">
          {{ __('Periodismo independiente desde el Caribe colombiano.', 'voxpopuli') }}
        </p>
      </div>

      {{-- ═══════════════════════════════════════════════════════
         Columna 2: Secciones — Top-level items del menú primario
         ═══════════════════════════════════════════════════════ --}}
      <div>
        <h3 class="font-sans font-bold text-[0.6875rem] uppercase tracking-[0.2em] text-accent mb-5">
          {{ __('Secciones', 'voxpopuli') }}
        </h3>
        <ul class="space-y-2.5">
          @php
            $locations = get_nav_menu_locations();
            $footerMenuId = $locations['primary_navigation'] ?? null;
            $footerMenuItems = $footerMenuId ? wp_get_nav_menu_items($footerMenuId) : [];
            $footerShown = 0;
          @endphp
          @foreach ($footerMenuItems as $item)
            @php if ((int) $item->menu_item_parent !== 0) continue; @endphp
            @if ($footerShown >= 8) @break @endif
            <li>
              <a href="{{ $item->url }}"
                 class="font-sans text-sm text-base-100/80 hover:text-accent transition-colors duration-200 no-underline">
                {{ $item->title }}
              </a>
            </li>
            @php $footerShown++; @endphp
          @endforeach
        </ul>
        @if ($footerShown === 0)
          <p class="font-serif text-sm text-base-100/60 italic">
            {{ __('Configure un menú de navegación primario.', 'voxpopuli') }}
          </p>
        @endif
      </div>

      {{-- ═══════════════════════════════════════════════════════
         Columna 3: Contacto + Redes sociales
         ═══════════════════════════════════════════════════════ --}}
      <div>
        <h3 class="font-sans font-bold text-[0.6875rem] uppercase tracking-[0.2em] text-accent mb-5">
          {{ __('Contacto', 'voxpopuli') }}
        </h3>
        <p class="font-serif text-sm text-base-100/70 leading-relaxed max-w-xs">
          {{ __('Cartagena de Indias, Colombia', 'voxpopuli') }}
        </p>

        {{-- Social icons — 44x44px touch targets per WCAG 2.5.8 --}}
        <div class="flex items-center gap-2 mt-6">
          <a href="#" class="flex items-center justify-center w-11 h-11 text-base-100/70 hover:text-accent transition-colors duration-200" aria-label="X (Twitter)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
          </a>
          <a href="#" class="flex items-center justify-center w-11 h-11 text-base-100/70 hover:text-accent transition-colors duration-200" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
              <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
            </svg>
          </a>
          <a href="#" class="flex items-center justify-center w-11 h-11 text-base-100/70 hover:text-accent transition-colors duration-200" aria-label="YouTube">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/>
              <path d="m10 15 5-3-5-3z"/>
            </svg>
          </a>
        </div>
      </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════
       Barra inferior: copyright + atribución editorial
       ═══════════════════════════════════════════════════════ --}}
    <div class="h-px bg-base-100/10 mt-12 mb-6" role="presentation"></div>

    <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
      <p class="font-sans text-[0.6875rem] text-base-100/60">
        &copy; {{ date('Y') }} Vox Populi Digital.
        {{ __('Todos los derechos reservados.', 'voxpopuli') }}
      </p>
      <p class="font-sans text-[0.6875rem] text-base-100/60">
        {{ __('Hecho desde el Caribe colombiano', 'voxpopuli') }}
      </p>
    </div>
  </div>
</footer>
