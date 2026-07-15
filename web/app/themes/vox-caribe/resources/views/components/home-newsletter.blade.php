@props([])

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4">
    <div class="bg-base-200 p-8 md:p-12">
      <div class="max-w-lg mx-auto text-center">
        {{-- Title --}}
        <h2 class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-base-content mb-4">
          {{ __('Alerta Vox Populi', 'vox-caribe') }}
        </h2>

        {{-- Description --}}
        <p class="font-serif text-lg leading-relaxed text-base-content/80 mb-6">
          {{ __('Recibe en tu correo las alertas de investigación y el resumen semanal del Caribe colombiano.', 'vox-caribe') }}
        </p>

        {{-- Signup form placeholder --}}
        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" method="post" action="#">
          <label for="newsletter-email" class="sr-only">{{ __('Correo electrónico', 'vox-caribe') }}</label>
          <input
            id="newsletter-email"
            type="email"
            placeholder="{{ __('tu@correo.com', 'vox-caribe') }}"
            class="input w-full bg-base-100 border-base-300 rounded-none px-4 py-3 font-sans text-sm text-base-content"
            disabled
          />
          <button
            type="submit"
            class="btn btn-primary rounded-none px-6 py-3 font-sans text-sm font-bold uppercase tracking-wider whitespace-nowrap"
            disabled
          >
            {{ __('Suscribirme', 'vox-caribe') }}
          </button>
        </form>

        {{-- Disclaimer --}}
        <p class="font-sans text-xs text-base-content/40 mt-4">
          {{ __('Sin spam. Solo investigaciones y análisis. Puedes darte de baja cuando quieras.', 'vox-caribe') }}
        </p>
      </div>
    </div>
  </div>
</section>
