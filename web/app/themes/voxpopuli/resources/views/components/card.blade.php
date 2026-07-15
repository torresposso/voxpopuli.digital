@props([
    'title',
    'category' => null,
    'author',
    'date',
    'url' => '#',
])

<div {{ $attributes->merge(['class' => 'card bg-base-200 border-2 border-base-300 rounded-box']) }}>
  <div class="card-body p-[1.5rem] gap-[1rem]">
    @if($category)
      <x-badge>{{ $category }}</x-badge>
    @endif
    
    <!-- Titular de la Ficha -->
    <h3 class="font-display font-bold text-[1.5rem] tracking-tighter text-base-content leading-tight">
      <a href="{{ $url }}" class="hover:text-accent focus-visible:outline-primary transition-colors duration-[200ms]">
        {{ $title }}
      </a>
    </h3>
    
    <!-- Metadatos Documentales -->
    <div class="text-neutral font-sans font-semibold text-[0.75rem] uppercase tracking-wider mt-[0.5rem]">
      {{ __('Por', 'voxpopuli') }} <span class="text-accent">{{ $author }}</span> — {{ $date }}
    </div>
  </div>
</div>
