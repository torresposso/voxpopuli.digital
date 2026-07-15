@props(['variant' => 'accent', 'tracking' => 'tracking-[0.14em]'])

@php
  $classes = 'badge bg-' . $variant . ' text-white font-sans font-bold text-[0.6875rem] ' . $tracking . ' uppercase border-none';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</span>
