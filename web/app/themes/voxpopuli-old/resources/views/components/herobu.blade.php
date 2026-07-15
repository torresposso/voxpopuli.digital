@if (!empty($featured_posts) && count($featured_posts) >= 3)
  @php
    $main_post = $featured_posts[0];
  @endphp

  <section 
    class="w-full pt-4 md:pt-0 md:h-[calc(100dvh-4rem)] md:overflow-hidden bg-base-100 animate-fade-in-up border-b border-base-300"
    aria-label="{{ __('Artículos principales destacados', 'voxpopuli') }}"
  >
    {{-- Main grid container --}}
    <div class="max-w-[1440px] mx-auto w-full px-4 md:px-8 md:h-full">
      <div class="grid grid-cols-1 md:grid-cols-4 bg-base-100 md:h-full">
        
        {{-- COL 1: ARTÍCULO PRINCIPAL DESTACADO (50% de ancho en desktop con fondo primario) --}}
        <article class="col-span-1 md:col-span-2 flex flex-col md:h-full p-6 md:p-8 border-b md:border-b-0 md:border-r border-base-300 group overflow-hidden bg-primary text-primary-content relative">
          {{-- Subtle performant noise texture overlay --}}
          <div class="absolute inset-0 opacity-50 pointer-events-none mix-blend-overlay noise-overlay" aria-hidden="true"></div>

          {{-- Image Partition (Top, standard 3/5 height on desktop, border transition for premium accent using secondary color) --}}
          <div class="relative w-full h-56 md:h-3/5 overflow-hidden bg-base-200 border border-primary-content/20 group-hover:border-secondary transition-colors duration-500 rounded-none shrink-0">
            @if (!empty($main_post->image))
              <img 
                alt="{{ $main_post->alt ?? $main_post->title }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out" 
                src="{{ $main_post->image }}"
                loading="eager"
                fetchpriority="high"
                decoding="async"
              />
              {{-- Clean Brand-Primary Tint Overlay (Soft 15% opacity mix-blend-overlay keeping original color vibrant) --}}
              <div class="absolute inset-0 bg-primary opacity-15 mix-blend-overlay group-hover:opacity-0 transition duration-500 ease-out pointer-events-none"></div>
              {{-- Fractal noise overlay for texture --}}
              <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay"></div>
            @else
              <div class="w-full h-full bg-base-200 flex items-center justify-center">
                <span class="badge badge-ghost font-sans font-extrabold uppercase text-xs">{{ __('No hay imagen', 'voxpopuli') }}</span>
              </div>
            @endif
          </div>
          
          {{-- Text Partition (Bottom, remaining 2/5 height on desktop) --}}
          <div class="w-full md:h-2/5 flex flex-col justify-between pt-5">
            <div class="flex flex-col gap-2">
              {{-- Category Kicker --}}
              <div class="flex items-center gap-2">
                <span class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary">
                  <span aria-hidden="true" class="opacity-50">//</span> {{ $main_post->category }} <span aria-hidden="true" class="text-secondary font-black ml-1">.:</span>
                </span>
                <span class="badge badge-secondary font-sans font-extrabold uppercase tracking-wider text-[10px] text-secondary-content h-5 rounded-none">
                  {{ __('Destacado', 'voxpopuli') }}
                </span>
              </div>
              
              {{-- Title --}}
              <h2 class="font-display text-3xl md:text-3xl lg:text-4xl xl:text-5xl text-primary-content font-black leading-[1.05] tracking-tight group-hover:text-secondary transition-colors duration-300">
                <a href="{{ $main_post->url }}" class="hover:text-secondary text-primary-content transition-colors duration-300">
                  {{ $main_post->title }}
                </a>
              </h2>

              {{-- Excerpt --}}
              @if (!empty($main_post->excerpt))
                <p class="text-primary-content/85 text-xs md:text-sm font-serif line-clamp-3 leading-relaxed">
                  {{ $main_post->excerpt }}
                </p>
              @endif
            </div>

            {{-- Meta info (Author and Date) --}}
            <div class="flex items-center gap-2 font-sans text-xs text-primary-content/75 font-semibold uppercase tracking-wider mt-4 border-t border-primary-content/15 pt-3">
              <span>{{ sprintf(__('Por %s', 'voxpopuli'), $main_post->author) }}</span>
              <span aria-hidden="true" class="opacity-40">//</span>
              <span>{{ $main_post->date }}</span>
            </div>
          </div>
        </article>

        {{-- COL 2: ARTÍCULOS DESTACADOS SECUNDARIOS (25% de ancho en desktop, 3 filas verticalmente con distribución perfecta de espacio) --}}
        <div class="col-span-1 md:col-span-1 grid grid-cols-1 sm:grid-cols-3 md:grid-cols-1 md:grid-rows-3 md:h-full border-b md:border-b-0 md:border-r border-base-300">
          @foreach (array_slice($featured_posts, 1) as $post)
            <article class="flex flex-col h-full justify-between p-3.5 md:p-3 group overflow-hidden bg-base-100 {{ !$loop->last ? 'border-b sm:border-b-0 sm:border-r md:border-r-0 md:border-b border-base-300' : '' }}">
              <div class="flex flex-col gap-1">
                {{-- Category Kicker --}}
                <div>
                  <span class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary">
                    <span aria-hidden="true" class="opacity-50">//</span> {{ $post->category }} <span aria-hidden="true" class="text-primary font-black ml-1">.:</span>
                  </span>
                </div>
                
                {{-- Title --}}
                <h3 class="font-display text-sm md:text-base lg:text-[1.0625rem] text-base-content font-bold leading-[1.2] group-hover:text-primary transition-colors duration-300 line-clamp-3">
                  <a href="{{ $post->url }}" class="hover:text-primary transition-colors duration-300">
                    {{ $post->title }}
                  </a>
                </h3>
                
                {{-- Meta Author --}}
                <div class="font-sans text-[10px] text-neutral font-semibold uppercase tracking-wider mt-0.5">
                  <span>{{ sprintf(__('Por %s', 'voxpopuli'), $post->author) }}</span>
                </div>
              </div>

              {{-- Image Box (Fluid Aspect Ratio as requested, border transition for premium accent using primary color) --}}
              <div class="relative w-full aspect-video md:aspect-none md:h-16 lg:h-20 xl:h-24 shrink-0 overflow-hidden bg-base-200 border border-base-300 group-hover:border-primary transition-colors duration-500 rounded-none mt-2.5 md:mt-1">
                @if (!empty($post->image))
                  <img 
                    alt="{{ $post->alt ?? $post->title }}" 
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out" 
                    src="{{ $post->image }}"
                    loading="lazy"
                    decoding="async"
                  />
                  {{-- Clean Brand-Primary Tint Overlay (Soft 15% opacity mix-blend-overlay keeping original color vibrant) --}}
                  <div class="absolute inset-0 bg-primary opacity-15 mix-blend-overlay group-hover:opacity-0 transition duration-500 ease-out pointer-events-none"></div>
                  <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay"></div>
                @else
                  <div class="w-full h-full bg-base-200 flex items-center justify-center">
                    <span class="badge badge-ghost font-sans font-extrabold uppercase text-[10px]">{{ __('Artículo', 'voxpopuli') }}</span>
                  </div>
                @endif
              </div>
            </article>
          @endforeach
        </div>

        {{-- COL 3: NUESTRAS ÚLTIMAS HISTORIAS (25% de ancho en desktop, NO inner scroll in mobile) --}}
        @if (!empty($latest_posts) && count($latest_posts) > 0)
          <aside class="col-span-1 md:col-span-1 flex flex-col md:grid md:grid-rows-[auto_1fr] md:h-full p-5 md:p-6 bg-base-100">
            {{-- Header of sidebar --}}
            <div class="flex items-center gap-2 border-b border-base-content pb-2 mb-4 md:mb-0 md:pb-3 shrink-0">
              <span class="w-2 h-2 rounded-full bg-secondary animate-pulse" aria-hidden="true"></span>
              <h4 class="font-sans font-extrabold uppercase text-xs tracking-[0.15em] text-base-content">
                {{ __('Últimas historias', 'voxpopuli') }}
              </h4>
            </div>

            {{-- List of stories --}}
            <div class="flex flex-col gap-4 md:gap-0 md:grid md:grid-cols-1 md:grid-rows-5 md:h-full md:pt-4">
              @foreach ($latest_posts as $post)
                <div class="relative group flex flex-col gap-1 pl-14 min-h-[4.5rem] md:min-h-0 justify-center border-b border-base-300 last:border-b-0 md:border-b md:border-base-300 md:last:border-b-0 py-2.5 md:py-0 md:h-full">
                  {{-- Large brand ranking number (Eye Candy - with increased padding and soft opacity for elegant spacing) --}}
                  <span class="absolute left-0 top-1/2 -translate-y-1/2 font-sans font-extrabold text-3xl md:text-[2rem] text-primary/25 pointer-events-none select-none tracking-tighter leading-none" aria-hidden="true">
                    {{ sprintf('%02d', $loop->iteration) }}
                  </span>

                  {{-- Category label --}}
                  <div>
                    <span class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary">
                      <span aria-hidden="true" class="opacity-50">//</span> {{ $post->category }} <span aria-hidden="true" class="text-primary font-black ml-1">.:</span>
                    </span>
                  </div>

                  {{-- Story Title --}}
                  <h5 class="font-display text-base md:text-sm lg:text-[0.9375rem] xl:text-base text-base-content font-bold leading-snug group-hover:text-primary transition-colors duration-300 line-clamp-2">
                    <a href="{{ $post->url }}" class="hover:text-primary transition-colors duration-300">
                      {{ $post->title }}
                    </a>
                  </h5>

                  {{-- Author info --}}
                  <div class="font-sans text-[10px] text-neutral font-semibold uppercase tracking-wider mt-0.5">
                    <span>{{ sprintf(__('Por %s', 'voxpopuli'), $post->author) }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </aside>
        @endif

      </div>
    </div>
  </section>
@endif
