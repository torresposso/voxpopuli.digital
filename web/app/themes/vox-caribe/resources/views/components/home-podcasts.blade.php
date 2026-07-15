@props(['podcasts' => []])

<section class="py-12 md:py-16 bg-base-200">
  <div class="mx-auto max-w-7xl px-4">
    {{-- Section header --}}
    <div class="flex items-center justify-between mb-8">
      <h2 class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-base-content">
        {{ __('Podcasts', 'vox-caribe') }}
      </h2>
      <span class="font-sans text-xs uppercase tracking-wider text-base-content/40">
        {{ __('próximamente', 'vox-caribe') }}
      </span>
    </div>

    {{-- Placeholder grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @for ($i = 0; $i < 4; $i++)
        <div class="aspect-square bg-base-300/50 rounded-none flex items-center justify-center">
          <span class="font-sans text-xs text-base-content/30 uppercase tracking-wider">
            {{ __('Audio', 'vox-caribe') }}
          </span>
        </div>
      @endfor
    </div>

    <p class="font-sans text-sm text-base-content/40 mt-6 text-center">
      {{ __('Contenido de audio próximamente.', 'vox-caribe') }}
    </p>
  </div>
</section>
