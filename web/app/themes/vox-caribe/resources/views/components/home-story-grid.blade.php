@props(['posts'])

@php
  $chunks = array_chunk($posts, 4);
@endphp

<section class="py-12 md:py-16">
  <div class="mx-auto max-w-7xl px-4">
    @foreach ($chunks as $rowIndex => $row)
      {{-- Grid row --}}
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($row as $post)
          @php
            $permalink = get_permalink($post);
            $title = get_the_title($post);
            $thumbnailId = get_post_thumbnail_id($post);
            $categories = get_the_category($post->ID);
            $primaryCategory = ! empty($categories) ? $categories[0] : null;
          @endphp

          <article class="group relative">
            {{-- Imagen con aspect ratio 4:3 --}}
            <a href="{{ $permalink }}" class="block overflow-hidden" aria-hidden="true" tabindex="-1">
              @if ($thumbnailId)
                <div class="aspect-[4/3] overflow-hidden">
                  {!! wp_get_attachment_image($thumbnailId, 'large', false, [
                    'class' => 'w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105',
                    'loading' => 'lazy',
                  ]) !!}
                </div>
              @endif
            </a>

            {{-- Contenido --}}
            <div class="mt-4">
              {{-- Kicker (categoría) --}}
              @if ($primaryCategory)
                <span class="font-sans text-xs font-bold uppercase tracking-[0.2em] text-accent">
                  {{ $primaryCategory->name }}
                </span>
              @endif

              {{-- Headline --}}
              <h2 class="font-serif text-xl leading-snug mt-1.5 font-bold">
                <a href="{{ $permalink }}" class="text-base-content no-underline transition-colors duration-200 ease-out group-hover:text-accent">
                  <span class="line-clamp-3">{{ $title }}</span>
                </a>
              </h2>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Separador entre filas (menos en la última) --}}
      @if (! $loop->last)
        <div class="my-10 border-t border-base-300"></div>
      @endif
    @endforeach
  </div>
</section>
