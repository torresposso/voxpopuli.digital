@props([
  'sections' => [
    [
      'slug' => 'destacadas',
      'name' => 'Destacadas',
      'icon' => '⭐',
      'tone' => 'Informativo, urgente',
      'description' => 'Portada principal del medio, consolidando el contenido curatorial de mayor impacto y relevancia social del momento.'
    ],
    [
      'slug' => 'analisis',
      'name' => 'Análisis',
      'icon' => '📊',
      'tone' => 'Técnico, reflexivo, argumentativo',
      'description' => 'Lectura interpretativa profunda de la coyuntura política, económica y social, enriquecida con datos fríos y metodologías estructuradas.'
    ],
    [
      'slug' => 'investigacion',
      'name' => 'Investigación',
      'icon' => '🔍',
      'tone' => 'Riguroso, narrativo, denso',
      'description' => 'Reportajes en profundidad y fiscalización de largo aliento basados en pruebas documentales rigurosas y trabajo de campo directo.'
    ],
    [
      'slug' => 'opinion',
      'name' => 'Opinión',
      'icon' => '✍️',
      'tone' => 'Subjetivo, incisivo, provocador',
      'description' => 'Espacio para firmas de opinión independientes que promueven el debate pluralista, con posturas editoriales directas e intelectuales.'
    ],
    [
      'slug' => 'deportes',
      'name' => 'Deportes',
      'icon' => '🏃',
      'tone' => 'Crítico, investigativo, identitario',
      'description' => 'Análisis sociopolítico y fiscalización de recursos de ligas y escenarios deportivos locales, abordando el deporte como eje cultural.'
    ],
    [
      'slug' => 'ahora',
      'name' => 'Ahora',
      'icon' => '⚡',
      'tone' => 'Directo, breve, factual',
      'description' => 'Flujo permanente de actualizaciones cortas, flashes informativos de última hora y reportes rápidos para consumo inmediato en móviles.'
    ],
  ]
])

<section class="relative bg-base-200 py-16 lg:py-24 overflow-hidden border-b border-base-300" id="secciones" aria-labelledby="secciones-heading">
  {{-- Textura sutil --}}
  <div class="absolute inset-0 noise-overlay opacity-[0.02] z-0 pointer-events-none"></div>

  <div class="max-w-6xl mx-auto px-4 relative z-10 space-y-12">
    
    {{-- Encabezado de Sección --}}
    <div class="space-y-3 animate-fade-in-up">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary">{{ __('Estructura Editorial', 'voxpopuli') }}</span>
      <h2 id="secciones-heading" class="font-display text-3xl lg:text-4xl font-extrabold text-primary tracking-tight">{{ __('Secciones Editoriales', 'voxpopuli') }}</h2>
      <p class="font-serif text-sm text-base-content/70 max-w-xl leading-relaxed">
        {{ __('Nuestra cobertura periodística está organizada en ejes intencionales para ofrecer tanto actualidad rápida como investigación profunda.', 'voxpopuli') }}
      </p>
    </div>

    {{-- Grid de Secciones con Container Queries --}}
    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
      @foreach ($sections as $sec)
        <li class="brand-container-section animate-fade-in-up list-none" style="animation-delay: {{ $loop->iteration * 100 }}ms">
          <div class="bg-base-100 border border-base-300 rounded-xl p-6 hover:bg-base-200/30 transition-all duration-300 group shadow-sm flex flex-col justify-between h-full relative overflow-hidden">
            
            {{-- Detalle visual en hover --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-transparent group-hover:bg-primary transition-all duration-300"></div>

            <div class="space-y-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl" aria-hidden="true">{{ $sec['icon'] }}</span>
                <h3 class="font-display text-lg font-black text-primary group-hover:text-secondary transition-colors duration-300">
                  {{ $sec['name'] }}<span class="text-secondary font-sans font-bold ml-0.5" aria-hidden="true">.:</span>
                </h3>
              </div>

              <p class="font-serif text-sm text-base-content/80 leading-relaxed">
                {{ $sec['description'] }}
              </p>
            </div>

            <div class="pt-6 mt-6 border-t border-base-300/60 flex items-center justify-between">
              <div>
                <span class="block font-sans text-[9px] uppercase tracking-wider text-base-content/70 font-bold">{{ __('Tono Editorial', 'voxpopuli') }}</span>
                <span class="font-serif text-xs text-secondary font-semibold italic">{{ $sec['tone'] }}</span>
              </div>
              
              <a
                href="{{ home_url('/category/' . $sec['slug'] . '/') }}"
                class="w-8 h-8 rounded-full bg-base-200 group-hover:bg-primary text-primary group-hover:text-primary-content flex items-center justify-center transition-all duration-300 shrink-0 shadow-sm"
                aria-label="{{ sprintf(__('Ver categoría %s', 'voxpopuli'), $sec['name']) }}"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>

          </div>
        </li>
      @endforeach
    </ul>

  </div>
</section>
