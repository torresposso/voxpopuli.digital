@props(['as' => 'div'])

<{{ $as }} {{ $attributes->merge(['class' => 'font-serif text-[1.25rem] leading-relaxed max-w-prose [font-size-adjust:from-font]']) }}>
  {{ $slot }}
</{{ $as }}>
