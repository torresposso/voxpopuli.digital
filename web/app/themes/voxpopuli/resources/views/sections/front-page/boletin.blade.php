<section class="bg-primary text-primary-content py-8 lg:py-16">
  <div class="max-w-lg mx-auto text-center px-4">
    <header class="flex items-center justify-center border-b-2 border-primary pb-2 mb-6">
      <div class="flex items-center gap-3">
        <span class="w-1.25 h-5.5 bg-accent block rounded-full" aria-hidden="true"></span>
        <h2 class="font-display font-black text-primary text-2xl lg:text-3xl tracking-tight">
          {{ __('El Boletín Vox Populi', 'voxpopuli') }}
        </h2>
      </div>
    </header>

    {{-- Descripcion --}}
    <p class="font-serif text-lg md:text-xl mt-4 leading-relaxed text-pretty">
      Las investigaciones, crónicas y análisis que importan, cada semana en tu correo.
    </p>

    {{-- Formulario --}}
    <form action="#" method="POST" class="mt-10">
      <fieldset class="fieldset">
        <legend class="fieldset-legend font-sans font-[800] tracking-[0.2em] uppercase text-sm text-primary-content">
          Suscríbete al boletín
        </legend>

        <div class="flex flex-col sm:flex-row gap-3 mt-2">
          <input
            type="email"
            name="email"
            placeholder="tu@correo.com"
            required
            class="input input-lg flex-1 bg-base-100 text-base-content placeholder:text-base-content/70"
          />

          <button type="submit" class="btn btn-accent btn-lg px-8">
            Suscribirse
          </button>
        </div>

        <p class="label text-base-100/80 text-xs mt-2">
          Sin spam. Sin compromiso. Puedes darte de baja en cualquier momento.
        </p>
      </fieldset>
    </form>
  </div>
</section>
