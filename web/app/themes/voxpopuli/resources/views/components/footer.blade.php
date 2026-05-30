<footer class="bg-base-content text-base-100 mt-16 border-t border-base-100/10">
    {{-- 
    Footer Component — Vox Populi Digital
    Audited with Impeccable and Modern Web Guidance
    Accessibility: WCAG AAA Contrast, Keyboard Navigation, Semantic Landmarks
  --}}
    <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Column 1: Editorial Pitch --}}
        <div class="space-y-4">
            <x-wordmark class="text-2xl" :dark="true" />
            <p class="font-serif text-sm text-base-100/70 leading-relaxed max-w-sm">
                Periodismo independiente de investigación, análisis y opinión desde el Caribe colombiano. Rigor técnico,
                mirada progresista y arraigo caribeño.
            </p>
        </div>

        {{-- Column 2: Navigation --}}
        <div>
            <h3 class="font-sans text-xs font-extrabold uppercase tracking-[0.2em] text-secondary mb-4">
                Secciones
            </h3>
            @if (has_nav_menu('primary_navigation'))
                <nav aria-label="Navegación del pie de página" class="footer-nav">
                    @php(
    wp_nav_menu([
        'theme_location' => 'primary_navigation',
        'menu_class' => 'space-y-3',
        'container' => false,
        'fallback_cb' => false
    ])
)
                </nav>
            @endif
        </div>

        {{-- Column 3: Contact details --}}
        <div class="space-y-4">
            <h3 class="font-sans text-xs font-extrabold uppercase tracking-[0.2em] text-secondary">
                Contacto
            </h3>
            <p class="font-serif text-sm text-base-100/70 leading-relaxed">
                Vox Populi Digital<br>
                Cartagena, Colombia<br>
                <a href="mailto:contacto@voxpopuli.digital"
                    class="text-secondary hover:text-base-100 transition-colors duration-300 font-semibold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-secondary block md:inline-block mt-2 md:mt-0 py-2 md:py-0"
                    aria-label="Enviar correo a contacto@voxpopuli.digital">
                    contacto@voxpopuli.digital
                </a>
            </p>
        </div>
    </div>

    {{-- Sub-footer: Copyright and regional proud --}}
    <div class="border-t border-base-100/10">
        <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="font-sans text-xs text-base-100/60">
                &copy; {{ date('Y') }} Vox Populi Digital. Todos los derechos reservados.
            </p>
            <p class="font-sans text-xs text-base-100/50 flex items-center gap-1.5 select-none">
                <span aria-hidden="true">🌴</span> Hecho desde el Caribe colombiano
            </p>
        </div>
    </div>
</footer>
