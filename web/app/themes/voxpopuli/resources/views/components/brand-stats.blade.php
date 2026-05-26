@props([
  'stats' => [
    [
      'number' => '15K+',
      'label' => 'Artículos Publicados',
      'description' => 'Un registro continuo de los hechos que han marcado la historia reciente de la región Caribe.'
    ],
    [
      'number' => '50K+',
      'label' => 'Lectores Mensuales',
      'description' => 'Ciudadanía general, profesionales y tomadores de decisiones que confían en nuestro rigor.'
    ],
    [
      'number' => '8+ Años',
      'label' => 'Memoria e Historia',
      'description' => 'Construyendo el archivo vivo y la veeduría crítica del poder en la costa norte colombiana.'
    ],
    [
      'number' => '120+',
      'label' => 'Grandes Investigaciones',
      'description' => 'Reportajes a fondo sobre contratación y derechos humanos que los grandes medios omiten.'
    ]
  ]
])

<section class="relative bg-base-200 py-16 lg:py-24 overflow-hidden border-b border-base-300" id="impacto">
  {{-- Textura sutil --}}
  <div class="absolute inset-0 noise-overlay opacity-[0.02] z-0 pointer-events-none"></div>

  <div class="max-w-6xl mx-auto px-4 relative z-10 space-y-12">
    
    {{-- Encabezado de Sección --}}
    <div class="space-y-3 animate-fade-in-up">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary">Nuestras Métricas</span>
      <h2 class="font-display text-3xl lg:text-4xl font-extrabold text-primary tracking-tight">Alcance e Impacto</h2>
      <p class="font-serif text-sm text-base-content/70 max-w-xl leading-relaxed">
        El trabajo y la rigurosidad metodológica de Vox Populi Digital reflejados en números transparentes y proyección regional.
      </p>
    </div>

    {{-- Grid de Estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach ($stats as $s)
        <div class="animate-fade-in-up" style="animation-delay: {{ $loop->iteration * 100 }}ms">
          <div class="bg-base-100 border border-base-300 rounded-xl p-6 lg:p-8 flex flex-col justify-between h-full relative overflow-hidden group shadow-sm hover:bg-base-200/20 transition-all duration-300">
            
            <div class="absolute top-0 left-0 right-0 h-1 bg-transparent group-hover:bg-secondary transition-all duration-300"></div>

            <div class="space-y-4">
              <span class="block font-display text-4xl lg:text-5xl font-black text-primary tracking-tighter leading-none select-none">
                {{ $s['number'] }}
              </span>
              
              <div class="space-y-2">
                <h3 class="font-sans text-xs font-extrabold uppercase tracking-widest text-secondary">
                  {{ $s['label'] }}<span class="text-primary font-sans font-bold ml-0.5">.:</span>
                </h3>
                <p class="font-serif text-xs text-base-content/70 leading-relaxed">
                  {{ $s['description'] }}
                </p>
              </div>
            </div>

          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
