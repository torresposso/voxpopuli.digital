@props([
  'personas' => [
    [
      'name' => 'Carlos',
      'age' => 42,
      'location' => 'Cartagena',
      'role' => 'El Líder de Opinión',
      'avatar' => '👨‍💼',
      'profile' => 'Profesional de alta incidencia que consume noticias temprano en su teléfono móvil. Comparte activamente hilos de análisis en X (Twitter) y coordina debates políticos en grupos de WhatsApp de alta influencia regional.',
      'needs' => [
        'Rigor absoluto y cruces de bases de datos.',
        'Análisis técnico de contratos públicos.',
        'Herramientas ágiles para compartir en redes.'
      ]
    ],
    [
      'name' => 'María',
      'age' => 29,
      'location' => 'El Carmen de Bolívar',
      'role' => 'La Activista Territorial',
      'avatar' => '👩‍🌾',
      'profile' => 'Trabaja en colectivos de paz y ONGs territoriales en los Montes de María. Consume noticias casi exclusivamente desde su celular, a menudo bajo planes de datos limitados. Para ella, WhatsApp es el canal primario de acceso.',
      'needs' => [
        'Visibilidad de testimonios y denuncias de DDHH.',
        'Resúmenes rápidos tipo TL;DR al inicio de las notas.',
        'Páginas web optimizadas de muy bajo consumo de datos.'
      ]
    ],
    [
      'name' => 'Jorge',
      'age' => 55,
      'location' => 'Barranquilla',
      'role' => 'El Académico Investigador',
      'avatar' => '👨‍🏫',
      'profile' => 'Docente universitario e historiador del Caribe. Accede comúnmente desde computadores de escritorio. Ve el periodismo como un archivo de registro permanente y utiliza nuestro sitio como biblioteca de consulta.',
      'needs' => [
        'Indexación impecable y URLs estables permanentemente.',
        'Trascripción íntegra de fallos y documentos oficiales.',
        'Enlaces directos a repositorios y bases de datos públicas.'
      ]
    ]
  ]
])

<section class="relative bg-base-100 py-16 lg:py-24 overflow-hidden border-b border-base-300" id="audiencias">
  {{-- Textura sutil --}}
  <div class="absolute inset-0 noise-overlay opacity-[0.02] z-0 pointer-events-none"></div>

  <div class="max-w-6xl mx-auto px-4 relative z-10 space-y-12">
    
    {{-- Encabezado de Sección --}}
    <div class="space-y-3 animate-fade-in-up">
      <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.25em] text-secondary">Nuestra Audiencia</span>
      <h2 class="font-display text-3xl lg:text-4xl font-extrabold text-primary tracking-tight">Arquetipos de Lector</h2>
      <p class="font-serif text-sm text-base-content/70 max-w-xl leading-relaxed">
        Diseñamos y optimizamos el producto periodístico en función de perfiles reales de nuestra región para garantizar impacto territorial y rigor.
      </p>
    </div>

    {{-- Grid de Arquetipos --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      @foreach ($personas as $p)
        <div class="animate-fade-in-up" style="animation-delay: {{ $loop->iteration * 100 }}ms">
          <div class="persona-card bg-base-100 border border-base-300 rounded-2xl p-6 lg:p-8 flex flex-col justify-between shadow-sm relative overflow-hidden h-full">
            
            {{-- Línea de acento dinámico --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-base-300 group-hover:bg-primary transition-all duration-300"></div>

            <div class="space-y-6">
              
              {{-- Avatar y Encabezado del Perfil --}}
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-base-200 border border-base-300 flex items-center justify-center text-3xl shadow-sm shrink-0 select-none">
                  {{ $p['avatar'] }}
                </div>
                <div>
                  <h3 class="font-display text-xl font-black text-primary">
                    {{ $p['name'] }}<span class="text-secondary font-sans font-bold ml-0.5">.:</span>
                  </h3>
                  <span class="block font-sans text-[10px] font-extrabold uppercase tracking-wider text-secondary">
                    {{ $p['role'] }}
                  </span>
                  <span class="block font-serif text-xs text-base-content/70 italic">
                    {{ $p['age'] }} años · {{ $p['location'] }}
                  </span>
                </div>
              </div>

              {{-- Resumen del Perfil --}}
              <p class="font-serif text-sm text-base-content/85 leading-relaxed">
                {{ $p['profile'] }}
              </p>

            </div>

            {{-- Accordion CSS-only para Necesidades con :has() trigger --}}
            <div class="mt-6 pt-6 border-t border-base-300/60">
              <details class="group select-none">
                
                <summary class="flex justify-between items-center cursor-pointer outline-none font-sans text-[10px] font-extrabold uppercase tracking-widest text-primary hover:text-secondary transition-colors duration-300">
                  <span>Necesidades de Producto</span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4.5 w-4.5 text-primary group-hover:text-secondary transition-transform duration-300 group-open:rotate-180"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2.5"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </summary>

                <ul class="mt-4 space-y-2.5 pl-2 animate-fade-in-up">
                  @foreach ($p['needs'] as $need)
                    <li class="flex items-start gap-2.5 font-serif text-xs text-base-content/70 leading-relaxed">
                      <span class="text-secondary font-bold text-sm shrink-0 select-none">·</span>
                      <span>{{ $need }}</span>
                    </li>
                  @endforeach
                </ul>

              </details>
            </div>

          </div>
        </div>
      @endforeach
    </div>

  </div>
</section>
