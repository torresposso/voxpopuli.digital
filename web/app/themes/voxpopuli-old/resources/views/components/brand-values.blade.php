@props([
  'values' => [
    [
      'title' => 'Independencia',
      'icon' => '🛡️',
      'excerpt' => 'Sin ataduras a partidos, gobiernos o grupos económicos.',
      'description' => 'La línea editorial responde solo a los hechos y al interés público. Nuestra lupa fiscalizadora es políticamente ciega: aplicamos el mismo rigor analítico y vehemencia a las gestiones e ineficiencias de origen progresista que a las tradicionales.'
    ],
    [
      'title' => 'Justicia',
      'icon' => '⚖️',
      'excerpt' => 'Investigación como herramienta de contrapoder.',
      'description' => 'Esclarecimiento de la verdad frente a la impunidad. En el producto, co-creamos valor con la comunidad mediante la habilitación de un buzón cifrado y seguro para la filtración de datos y denuncias ciudadanas.'
    ],
    [
      'title' => 'Caribeidad',
      'icon' => '🌴',
      'excerpt' => 'Orgullo de origen. La región Caribe como locus de enunciación.',
      'description' => 'Asumimos la costeñidad desde su diversidad rural y urbana, descentralizando intencionadamente nuestra cobertura de las capitales hacia las provincias periféricas (Montes de María, Sur de Bolívar, La Mojana).'
    ],
    [
      'title' => 'Rigor',
      'icon' => '🔬',
      'excerpt' => 'Análisis técnico basado en datos y evidencia.',
      'description' => 'Construimos investigaciones soportadas en rigurosas auditorías documentales, cruces de bases de datos públicas y contraste estricto de fuentes vivas para blindar la verdad de cada hecho.'
    ],
    [
      'title' => 'Memoria',
      'icon' => '📜',
      'excerpt' => 'El periodismo no es efímero: cada pieza es un registro.',
      'description' => 'Se operativiza resurgiendo dinámicamente contenidos históricos de contexto (evergreen) vinculados con la coyuntura del presente, y construyendo un archivo vivo, limpio y altamente indexable.'
    ],
  ]
])

<section class="relative bg-base-100 py-16 lg:py-24 overflow-hidden border-b border-base-300" id="valores" aria-labelledby="valores-heading">
  {{-- Textura sutil --}}
  <div class="absolute inset-0 noise-overlay opacity-[0.02] z-0 pointer-events-none"></div>

  <div class="max-w-6xl mx-auto px-4 relative z-10 space-y-12">
    
    {{-- Encabezado de Sección --}}
    <div class="space-y-3 animate-fade-in-up">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary">{{ __('Nuestros Pilares', 'voxpopuli') }}</span>
      <h2 id="valores-heading" class="font-display text-3xl lg:text-4xl font-extrabold text-primary tracking-tight">{{ __('Valores Fundamentales', 'voxpopuli') }}</h2>
      <p class="font-serif text-sm text-base-content/70 max-w-xl leading-relaxed">
        {{ __('Los principios inquebrantables que guían cada investigación, análisis y opinión producida por nuestra redacción.', 'voxpopuli') }}
      </p>
    </div>

    {{-- Grid de Acordeones CSS-only --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
      @foreach ($values as $val)
        <div class="animate-fade-in-up" style="animation-delay: {{ $loop->iteration * 100 }}ms">
          <details class="brand-accordion border border-base-300 rounded-xl p-5 bg-base-100 hover:bg-base-200/20 transition-all duration-300 group shadow-sm overflow-hidden">
            
            {{-- Resumen visible por defecto --}}
            <summary class="flex justify-between items-start gap-4">
              <div class="space-y-2">
                <div class="flex items-center gap-3">
                  <span class="text-2xl" aria-hidden="true">{{ $val['icon'] }}</span>
                  <h3 class="font-display text-lg font-black text-primary group-hover:text-secondary transition-colors duration-300">
                    {{ $val['title'] }}<span class="text-secondary font-sans font-bold ml-0.5" aria-hidden="true">.:</span>
                  </h3>
                </div>
                <p class="font-serif text-xs text-base-content/70 leading-relaxed group-open:hidden">
                  {{ $val['excerpt'] }}
                </p>
              </div>

              {{-- Icono de estado rotativo en CSS --}}
              <div class="w-7 h-7 rounded-full bg-base-200 group-hover:bg-primary/5 flex items-center justify-center transition-all duration-300 shrink-0">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 text-primary transition-transform duration-300 group-open:rotate-45"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="3"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
              </div>
            </summary>

            {{-- Contenido desplegable --}}
            <div class="mt-4 pt-4 border-t border-base-300/60 animate-fade-in-up">
              <p class="font-serif text-sm text-base-content/85 leading-relaxed">
                {{ $val['description'] }}
              </p>
            </div>

          </details>
        </div>
      @endforeach
    </div>

  </div>
</section>
