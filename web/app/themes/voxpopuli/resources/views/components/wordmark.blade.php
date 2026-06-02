@props(['dark' => false, 'onSecondary' => false])

<a href="{{ home_url('/') }}"
    {{ $attributes->merge(['class' => 'group font-logo font-extrabold tracking-tighter select-none text-2xl md:text-3xl text-shadow-lg leading-none flex items-center']) }}>
    @if ($onSecondary)
        <span class="text-secondary-content group-hover:text-primary transition-colors duration-300">Vox</span>
        <span class="text-primary group-hover:text-secondary-content transition-colors duration-300 ml-0.5">Populi</span>
    @elseif ($dark)
        <span class="text-base-100 group-hover:text-secondary transition-colors duration-300">Vox</span>
        <span class="text-secondary group-hover:text-base-100 transition-colors duration-300 ml-0.5">Populi</span>
    @else
        <span class="text-secondary group-hover:text-primary transition-colors duration-300">Vox</span>
        <span class="text-primary group-hover:text-secondary transition-colors duration-300 ml-0.5">Populi</span>
    @endif
</a>
