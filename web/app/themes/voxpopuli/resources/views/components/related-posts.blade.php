@props([
  'suggested' => [],
  'featured' => null,
])

@if (!empty($suggested) || $featured)
  <div class="mt-16 pt-12 border-t border-base-300/80">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
      
      {{-- 1. Suggested Readings Section (col-span-2) --}}
      @if (!empty($suggested))
        <div class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-6">
            <h2 class="font-sans font-extrabold text-xs tracking-[0.2em] uppercase text-primary select-none">
              {{ __('Lecturas sugeridas', 'voxpopuli') }}
            </h2>
            <span class="flex-1 h-[1px] bg-base-300/80"></span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($suggested as $post)
              <article class="flex flex-col group h-full">
                @if ($post['image'])
                  <a href="{{ $post['link'] }}" class="relative aspect-[16/10] overflow-hidden rounded-xl border border-base-300 shadow-sm mb-4 bg-base-200 block">
                    <img src="{{ $post['image'] }}" 
                         alt="{{ $post['title'] }}" 
                         class="w-full h-full object-cover transition duration-500 ease-out group-hover:scale-105"
                         loading="lazy" />
                  </a>
                @endif
                
                <div class="flex-1 flex flex-col justify-between">
                  <div>
                    @if ($post['category'])
                      <span class="font-sans font-extrabold text-[9px] tracking-widest uppercase text-secondary mb-2 block">
                        {{ $post['category'] }}
                      </span>
                    @endif
                    <h3 class="font-display font-bold text-lg md:text-xl text-base-content leading-snug group-hover:text-primary transition-colors duration-300">
                      <a href="{{ $post['link'] }}">{!! $post['title'] !!}</a>
                    </h3>
                  </div>
                  
                  <time class="font-sans font-semibold text-[10px] text-muted/70 mt-3 block">
                    {{ $post['date'] }}
                  </time>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      @endif

      {{-- 2. Latest Featured Post Section (col-span-1) --}}
      @if ($featured)
        <div class="lg:col-span-1">
          <div class="flex items-center gap-3 mb-6">
            <h2 class="font-sans font-extrabold text-xs tracking-[0.2em] uppercase text-primary select-none">
              {{ __('Destacado', 'voxpopuli') }}
            </h2>
            <span class="flex-1 h-[1px] bg-base-300/80"></span>
          </div>

          <article class="flex flex-col group h-full bg-base-200/50 border border-base-300 p-6 rounded-2xl shadow-sm">
            @if ($featured['image'])
              <a href="{{ $featured['link'] }}" class="relative aspect-[16/10] overflow-hidden rounded-xl border border-base-300 shadow-sm mb-5 bg-base-200 block">
                <img src="{{ $featured['image'] }}" 
                     alt="{{ $featured['title'] }}" 
                     class="w-full h-full object-cover transition duration-500 ease-out group-hover:scale-105"
                     loading="lazy" />
              </a>
            @endif

            <div class="flex-1 flex flex-col justify-between">
              <div>
                @if ($featured['category'])
                  <span class="inline-block font-sans font-extrabold text-[9px] tracking-widest uppercase bg-secondary text-secondary-content px-2 py-0.5 rounded-sm mb-3">
                    {{ $featured['category'] }}
                  </span>
                @endif
                <h3 class="font-display font-extrabold text-xl text-base-content leading-snug group-hover:text-primary transition-colors duration-300 mb-3">
                  <a href="{{ $featured['link'] }}">{!! $featured['title'] !!}</a>
                </h3>
                @if ($featured['excerpt'])
                  <p class="font-serif text-sm text-base-content/85 line-clamp-3 leading-relaxed mb-4">
                    {!! strip_tags($featured['excerpt']) !!}
                  </p>
                @endif
              </div>

              <div class="flex items-center justify-between border-t border-base-300/60 pt-4 mt-auto">
                <time class="font-sans font-semibold text-[10px] text-muted/70">
                  {{ $featured['date'] }}
                </time>
                <a href="{{ $featured['link'] }}" class="font-sans font-extrabold text-[10px] tracking-wider uppercase text-primary hover:text-secondary transition-colors flex items-center gap-1">
                  {{ __('Leer nota', 'voxpopuli') }}
                  <span class="transition-transform group-hover:translate-x-1 duration-300">→</span>
                </a>
              </div>
            </div>
          </article>
        </div>
      @endif

    </div>
  </div>
@endif
