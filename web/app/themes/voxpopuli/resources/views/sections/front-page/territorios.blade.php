<section aria-labelledby="titulo-territorios">
  {{-- Encabezado --}}
  <header class="mb-8 lg:mb-10">
    <div class="flex items-center justify-between pb-4 border-b-2 border-base-300">
      <h2
        id="titulo-territorios"
        class="font-display text-2xl lg:text-3xl font-black text-base-content leading-tight tracking-tighter"
      >
        Territorios
      </h2>
      <a
        href="{{ home_url('/category/territorios/') }}"
        class="font-sans text-[10px] font-extrabold uppercase tracking-[0.2em] text-secondary hover:text-base-content transition-colors shrink-0"
      >
        Ver todas →
      </a>
    </div>
  </header>

  {{-- Grid de 3 cards --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @foreach ($posts as $post)
      <div class="card card-border bg-base-200 rounded-box">
        <div class="card-body p-[1.5rem] gap-[1rem]">
          {{-- Badge con nombre del territorio --}}
          <x-badge>{{ $post->category }}</x-badge>

          {{-- Titular --}}
          <h3 class="card-title font-display font-bold text-[1.5rem] tracking-tighter text-base-content leading-tight">
            <a
              href="{{ $post->url }}"
              class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms] no-underline"
            >
              {{ $post->title }}
            </a>
          </h3>

          {{-- Bajada --}}
          @if ($post->excerpt)
            <p class="font-serif text-sm text-base-content/70 leading-relaxed">
              {{ $post->excerpt }}
            </p>
          @endif

          {{-- Firma y fecha --}}
          <div class="text-neutral font-sans font-semibold text-[0.75rem] uppercase tracking-wider mt-[0.5rem]">
            {{ __('Por', 'voxpopuli') }}
            <span class="text-accent">{{ $post->author }}</span>
            · {{ $post->date }}
          </div>
        </div>
      </div>
    @endforeach
    </div>
</section>
