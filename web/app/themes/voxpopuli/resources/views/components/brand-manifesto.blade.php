<section class="relative bg-base-200 py-16 lg:py-24 overflow-hidden border-b border-base-300" aria-labelledby="manifesto-heading">
  {{-- Ruido ambiental --}}
  <div class="absolute inset-0 noise-overlay opacity-[0.02] z-0 pointer-events-none"></div>

  <div class="max-w-4xl mx-auto px-4 relative z-10 text-center space-y-10 animate-fade-in-up">
    
    <div class="space-y-2">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.3em] text-secondary">{{ __('Manifiesto Editorial', 'voxpopuli') }}</span>
      <h2 id="manifesto-heading" class="font-display text-3xl md:text-4xl font-extrabold text-primary tracking-tight">{{ __('Nuestras Creencias', 'voxpopuli') }}</h2>
      <div class="flex justify-center items-center gap-1.5 pt-1" aria-hidden="true">
        <span class="w-8 h-[1px] bg-base-300"></span>
        <span class="text-secondary font-sans text-xs font-bold">.:</span>
        <span class="w-8 h-[1px] bg-base-300"></span>
      </div>
    </div>

    {{-- Bloque del Manifiesto con tipografía de revista impresa --}}
    <blockquote class="font-display text-xl md:text-2xl font-medium italic text-base-content/90 leading-relaxed max-w-3xl mx-auto space-y-6">
      <p class="relative">
        “{{ __('Creemos en un periodismo que no le teme al poder.', 'voxpopuli') }}”
      </p>
      <p>
        “{{ __('Creemos que el Caribe colombiano no es una periferia; es un centro de producción de pensamiento crítico.', 'voxpopuli') }}”
      </p>
      <p>
        “{{ __('Creemos que los hechos deben ser registrados, que el poder debe ser analizado con rigor técnico, y que la memoria es un acto de justicia.', 'voxpopuli') }}”
      </p>
      <p class="font-sans font-extrabold not-italic text-sm uppercase tracking-widest text-primary pt-4">
        — {{ __('No somos neutrales frente a la desigualdad. No somos objetivos frente a la injusticia.', 'voxpopuli') }} —
      </p>
      <cite class="block text-2xl md:text-3xl font-black text-primary not-italic tracking-tighter pt-2">
        {{ __('Somos rigurosos, independientes y caribeños', 'voxpopuli') }}<span class="text-secondary font-sans font-bold ml-0.5" aria-hidden="true">.:</span>
      </cite>
    </blockquote>

    <div class="pt-6">
      <div class="inline-flex items-center gap-2 px-4 py-2 bg-base-100 rounded-full border border-base-300 shadow-sm select-none">
        <span class="font-sans text-[10px] font-extrabold uppercase tracking-widest text-base-content/70">{{ __('Esa es nuestra voz', 'voxpopuli') }}</span>
        <span class="text-secondary font-sans text-[10px] font-extrabold" aria-hidden="true">·</span>
        <span class="font-display text-xs font-black text-primary">Vox Populi</span>
      </div>
    </div>

  </div>
</section>
