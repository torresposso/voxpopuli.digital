@props(['variant' => 'accent', 'tracking' => 'tracking-widest'])

<span {{ $attributes->merge(['class' => "badge badge-{$variant} font-sans font-extrabold text-[0.5rem] {$tracking} uppercase bg-transparent text-{$variant} p-0 border-none"]) }}>
  {{ $slot }}
</span>
