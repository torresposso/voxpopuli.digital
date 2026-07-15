<section aria-labelledby="heading-esenciales">
  <div class="max-w-7xl mx-auto px-4">
  {{-- Encabezado con borde inferior --}}
  <div class="border-b-2 border-base-content pb-3.5 mb-6">
    <h2 id="heading-esenciales"
        class="font-display font-extrabold text-xl tracking-tight text-base-content">
      {{ __('Lecturas Esenciales', 'voxpopuli') }}
    </h2>
  </div>

  {{-- Lista numerada de lecturas esenciales --}}
  <ul class="list">
    @foreach ($posts as $index => $post)
      @if ($index < 5)
        <li class="list-row p-0">
          <a href="{{ $post->url }}"
              class="flex items-center gap-5 w-full py-4 no-underline text-base-content group">
            {{-- Número ordinal (1-5) --}}
            <span class="font-bold text-3xl text-neutral shrink-0 leading-none">
              {{ $index + 1 }}
            </span>

            {{-- Contenido: categoría, título, fecha --}}
            <div class="min-w-0 flex-1">
              <x-badge>{{ $post->category }}</x-badge>
              <h3 class="font-display font-bold text-[1.125rem] leading-tight tracking-tight mt-1 group-hover:text-accent transition-colors duration-200">
                {{ $post->title }}
              </h3>
              <div class="font-sans font-semibold text-[0.6875rem] uppercase tracking-wider text-neutral mt-2">
                {{ $post->date }}
              </div>
            </div>
          </a>
        </li>
      @endif
    @endforeach
  </ul>
</div>
</section>
