<footer class="bg-base-content text-base-100 mt-16">
  <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <x-wordmark class="text-2xl" :dark="true" />
      <p class="font-serif text-sm text-white/60 mt-3 leading-relaxed max-w-sm">
        Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico, mirada progresista y arraigo caribeño.
      </p>
    </div>

    <div>
      <h3 class="font-sans text-[10px] font-extrabold uppercase tracking-[0.2em] text-white/40 mb-4">Secciones</h3>
      @if (has_nav_menu('primary_navigation'))
        @php(wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'menu_class' => 'space-y-2',
          'container' => false,
          'fallback_cb' => false,
        ]))
      @endif
      <a href="{{ home_url('/sitemap.xml') }}" class="block font-sans text-xs text-white/60 hover:text-secondary transition-colors mt-2">
        Sitemap
      </a>
    </div>

    <div>
      <h3 class="font-sans text-[10px] font-extrabold uppercase tracking-[0.2em] text-white/40 mb-4"> Contacto</h3>
      <p class="font-serif text-sm text-white/60 leading-relaxed">
        Vox Populi Digital<br>
        Cartagena, Colombia<br>
        <a href="mailto:contacto@voxpopuli.digital" class="text-secondary hover:text-white transition-colors">contacto@voxpopuli.digital</a>
      </p>
    </div>
  </div>

  <div class="border-t border-white/10">
    <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-2">
      <p class="font-sans text-[10px] text-white/40">
        &copy; {{ date('Y') }} Vox Populi Digital. Todos los derechos reservados.
      </p>
      <p class="font-sans text-[10px] text-white/20">
        Hecho desde el Caribe colombiano
      </p>
    </div>
  </div>

  @php(dynamic_sidebar('sidebar-footer'))
</footer>
