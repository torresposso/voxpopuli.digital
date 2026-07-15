@props([
  'slug' => '',
  'name' => '',
  'description' => '',
  'icon' => '',
  'posts' => [],
  'alternate' => false,
])

@if (! empty($posts))
  <section class="py-12 lg:py-16 @if ($alternate) bg-base-200 @else bg-base-100 @endif" id="seccion-{{ $slug }}" aria-labelledby="titulo-{{ $slug }}">
    <div class="max-w-6xl mx-auto px-4">
      <header class="mb-8 lg:mb-10">
        <div class="flex items-center gap-3 mb-2">
          @if ($icon)
            <span class="font-sans text-lg" aria-hidden="true">{{ $icon }}</span>
          @endif
          <span class="font-sans text-[10px] font-extrabold uppercase tracking-[0.2em] text-secondary">{{ __('Sección', 'voxpopuli') }}</span>
        </div>
        <h2 id="titulo-{{ $slug }}" class="font-display text-2xl lg:text-3xl font-black text-base-content leading-tight tracking-tighter">
          {{ $name }}
        </h2>
        @if ($description)
          <p class="font-serif text-sm text-base-content/70 leading-relaxed mt-2 max-w-xl">{{ $description }}</p>
        @endif
      </header>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($posts as $post)
          @php(setup_postdata($post))
          <x-post-card :post="$post" />
        @endforeach
        @php(wp_reset_postdata())
      </div>

      <div class="mt-8">
        <a href="{{ home_url('/category/' . $slug . '/') }}"
           class="inline-flex items-center gap-2 font-sans text-[10px] font-extrabold uppercase tracking-widest text-secondary hover:text-base-content transition-colors">
          Ver todos en {{ $name }}
          <span aria-hidden="true">→</span>
        </a>
      </div>
    </div>
  </section>
@endif
