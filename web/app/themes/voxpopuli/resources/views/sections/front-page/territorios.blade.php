<div class="bg-base-200 py-8 lg:py-16">

<section class="max-w-7xl mx-auto px-4" aria-labelledby="titulo-territorios">
  {{-- Encabezado --}}
  <header class="flex items-center justify-between border-b-2 border-primary pb-2 mb-8 lg:mb-10">
    <div class="flex items-center gap-3">
      <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
      <h2
        id="titulo-territorios"
        class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight"
      >
        Territorios
      </h2>
    </div>
    <a
      href="{{ home_url('/category/territorios/') }}"
      class="group font-sans font-bold text-xs uppercase tracking-[0.2em] text-primary hover:text-primary/80 transition-colors duration-200 no-underline shrink-0 min-h-[44px] inline-flex items-center focus-visible:outline-primary focus-visible:outline-2 focus-visible:outline-offset-2 rounded-sm"
    >
      {{ __('Ver todas', 'voxpopuli') }}
      <span aria-hidden="true" class="inline-block transition-transform duration-300 ease-out group-hover:translate-x-1">→</span>
    </a>
  </header>

  {{-- Grid de 3 columnas — una por ciudad --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Barranquilla --}}
    <div>
      <h3 class="font-display font-black text-lg text-base-content border-b-2 border-base-content pb-2 mb-4">
        Barranquilla
      </h3>
      <div class="space-y-4">
        @forelse ($barranquilla as $post)
          <article class="card card-border bg-base-200 rounded-box">
            <div class="flex items-start gap-3 p-4">
              <div class="flex-1 min-w-0">
                <h4 class="font-display font-bold text-[1.125rem] tracking-tight text-base-content leading-tight">
                  <a
                    href="{{ $post->url }}"
                    class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms] no-underline"
                  >
                    {{ $post->title }}
                  </a>
                </h4>
                @if ($post->excerpt)
                <p class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-2 mt-1">
                  {{ $post->excerpt }}
                </p>
              @endif
              <div class="text-neutral font-sans font-semibold text-[0.6875rem] uppercase tracking-wider mt-2">
                {{ __('Por', 'voxpopuli') }}
                <span class="text-primary">{{ $post->author }}</span>
                · {{ $post->date }}
              </div>
            </div>
            <div class="shrink-0 w-[80px] h-[80px] overflow-hidden rounded-box bg-base-300">
              @if ($post->image)
                <img src="{{ $post->image }}" alt="{{ $post->alt }}" loading="lazy" class="w-full h-full object-cover" />
              @else
                <div class="w-full h-full flex items-center justify-center text-base-content/20">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.41a2.25 2.25 0 0 1 3.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                  </svg>
                </div>
              @endif
            </div>
          </div>
        </article>
      @empty
        <p class="font-sans text-sm text-base-content/70 italic">{{ __('Sin crónicas de Barranquilla', 'voxpopuli') }}</p>
      @endforelse
    </div>
  </div>

  {{-- Cartagena --}}
  <div>
    <h3 class="font-display font-black text-lg text-base-content border-b-2 border-base-content pb-2 mb-4">
      Cartagena
    </h3>
    <div class="space-y-4">
      @forelse ($cartagena as $post)
        <article class="card card-border bg-base-200 rounded-box">
          <div class="flex items-start gap-3 p-4">
            <div class="flex-1 min-w-0">
              <h4 class="font-display font-bold text-[1.125rem] tracking-tight text-base-content leading-tight">
                <a
                  href="{{ $post->url }}"
                  class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms] no-underline"
                >
                  {{ $post->title }}
                </a>
              </h4>
              @if ($post->excerpt)
                <p class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-2 mt-1">
                  {{ $post->excerpt }}
                </p>
              @endif
                <div class="text-neutral font-sans font-semibold text-[0.6875rem] uppercase tracking-wider mt-2">
                  {{ __('Por', 'voxpopuli') }}
                  <span class="text-primary">{{ $post->author }}</span>
                  · {{ $post->date }}
                </div>
              </div>
              <div class="shrink-0 w-[80px] h-[80px] overflow-hidden rounded-box bg-base-300">
                @if ($post->image)
                  <img src="{{ $post->image }}" alt="{{ $post->alt }}" loading="lazy" class="w-full h-full object-cover" />
                @else
                  <div class="w-full h-full flex items-center justify-center text-base-content/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.41a2.25 2.25 0 0 1 3.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                  </div>
                @endif
              </div>
            </div>
          </article>
        @empty
          <p class="font-sans text-sm text-base-content/70 italic">{{ __('Sin crónicas de Cartagena', 'voxpopuli') }}</p>
        @endforelse
      </div>
    </div>

    {{-- Santa Marta --}}
    <div>
      <h3 class="font-display font-black text-lg text-base-content border-b-2 border-base-content pb-2 mb-4">
        Santa Marta
      </h3>
      <div class="space-y-4">
        @forelse ($santaMarta as $post)
          <article class="card card-border bg-base-200 rounded-box">
            <div class="flex items-start gap-3 p-4">
              <div class="flex-1 min-w-0">
                <h4 class="font-display font-bold text-[1.125rem] tracking-tight text-base-content leading-tight">
                  <a
                    href="{{ $post->url }}"
                    class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms] no-underline"
                  >
                    {{ $post->title }}
                  </a>
                </h4>
                @if ($post->excerpt)
                  <p class="font-serif text-sm text-base-content/85 leading-relaxed line-clamp-2 mt-1">
                    {{ $post->excerpt }}
                  </p>
                @endif
                <div class="text-neutral font-sans font-semibold text-[0.6875rem] uppercase tracking-wider mt-2">
                  {{ __('Por', 'voxpopuli') }}
                  <span class="text-primary">{{ $post->author }}</span>
                  · {{ $post->date }}
                </div>
              </div>
              <div class="shrink-0 w-[80px] h-[80px] overflow-hidden rounded-box bg-base-300">
                @if ($post->image)
                  <img src="{{ $post->image }}" alt="{{ $post->alt }}" loading="lazy" class="w-full h-full object-cover" />
                @else
                  <div class="w-full h-full flex items-center justify-center text-base-content/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.41a2.25 2.25 0 0 1 3.182 0l2.909 2.91m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                  </div>
                @endif
              </div>
            </div>
          </article>
        @empty
          <p class="font-sans text-sm text-base-content/70 italic">{{ __('Sin crónicas de Santa Marta', 'voxpopuli') }}</p>
        @endforelse
      </div>
    </div>

  </div>
</section>
</div>
