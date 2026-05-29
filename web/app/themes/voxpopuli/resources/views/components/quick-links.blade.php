{{-- Componente de Accesos Rápidos (Videos y Podcasts) --}}
<div {{ $attributes->merge(['class' => 'flex items-center gap-3 sm:gap-4 text-primary font-sans font-extrabold text-xs tracking-[0.2em]']) }}>
    <span
        class="flex items-center gap-2 text-primary/50 cursor-default uppercase py-1.5 select-none">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4.5 w-4.5 stroke-base-content/30"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
            <rect x="2" y="3" width="20" height="18" rx="2" ry="2" />
            <path d="M10 9l5 3-5 3V9z" fill="currentColor"
                class="text-base-content/30" />
        </svg>
        <span>{{ __('Videos', 'voxpopuli') }}</span>
    </span>

    <span
        class="flex items-center gap-2 text-primary/50 cursor-default uppercase py-1.5 select-none">
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4.5 w-4.5 stroke-base-content/30"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a9 9 0 0118 0v6" />
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z" />
        </svg>
        <span>{{ __('Podcasts', 'voxpopuli') }}</span>
    </span>
</div>
