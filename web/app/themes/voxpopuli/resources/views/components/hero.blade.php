@if (!empty($featured_posts) && count($featured_posts) >= 4)
    @php
        $main_post = $featured_posts[0];
    @endphp

    <section
        class="w-full max-w-[1440px] mx-auto px-0 md:px-8 pt-0 md:pt-0 h-[calc(100vh-4rem)] overflow-y-auto snap-y snap-mandatory scroll-smooth md:h-[calc(100vh-4rem)] md:overflow-hidden bg-base-100 animate-fade-in-up border-b border-base-300 grid grid-cols-1 md:grid-cols-4"
        aria-label="{{ __('Artículos principales destacados', 'voxpopuli') }}">
        <div class="bg-primary col-span-1 md:col-span-2 snap-start h-[calc(100vh-4rem)] md:h-full flex flex-col justify-between overflow-hidden py-4 md:py-0">
            <article class="h-full flex flex-col justify-between pb-4 md:pb-0">
                <div class="relative w-full aspect-video overflow-hidden bg-base-200 rounded-none shrink-0">
                    @if (!empty($main_post->image))
                        <img alt="{{ $main_post->alt ?? $main_post->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out"
                            src="{{ $main_post->image }}" loading="eager" fetchpriority="high" decoding="async" />
                        {{-- Clean Brand-Primary Tint Overlay (Soft 15% opacity mix-blend-overlay keeping original color vibrant) --}}
                        <div
                            class="absolute inset-0 bg-primary opacity-15 mix-blend-overlay group-hover:opacity-0 transition duration-500 ease-out pointer-events-none">
                        </div>
                        {{-- Fractal noise overlay for texture --}}
                        <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay">
                        </div>
                    @else
                        <div class="w-full h-full bg-base-200 flex items-center justify-center">
                            <span
                                class="badge badge-ghost font-sans font-extrabold uppercase text-xs">{{ __('No hay imagen', 'voxpopuli') }}</span>
                        </div>
                    @endif
                </div>
                <div class="px-6 pt-2 flex-1 flex flex-col justify-between">
                    {{-- Text Partition (Bottom, remaining 2/5 height on desktop) --}}
                    <div class="w-full flex-1 md:h-2/5 flex flex-col justify-between pt-5">
                        <div class="flex flex-col gap-2">
                            {{-- Category Kicker --}}
                            <div class="flex items-center gap-2">
                                <span
                                    class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary">
                                    <span aria-hidden="true" class="opacity-50">//</span> {{ $main_post->category }}
                                    <span aria-hidden="true" class="text-secondary font-black ml-1">.:</span>
                                </span>
                                <span
                                    class="badge badge-secondary font-sans font-extrabold uppercase tracking-wider text-[10px] text-secondary-content h-5 rounded-none">
                                    {{ __('Destacado', 'voxpopuli') }}
                                </span>
                            </div>

                            {{-- Title --}}
                            <h2
                                class="font-display text-3xl md:text-3xl lg:text-4xl xl:text-5xl text-primary-content font-black leading-[1.05] tracking-tight group-hover:text-secondary transition-colors duration-300">
                                <a href="{{ $main_post->url }}"
                                    class="hover:text-secondary text-primary-content transition-colors duration-300">
                                    {{ $main_post->title }}
                                </a>
                            </h2>

                            {{-- Excerpt --}}
                            @if (!empty($main_post->excerpt))
                                <p class="py-3 pr-4 text-primary-content/85 text-base md:text-lg font-serif line-clamp-2 md:line-clamp-3 leading-relaxed">
                                    {{ $main_post->excerpt }}
                                </p>
                            @endif
                        </div>

                        {{-- Meta info (Author and Date) --}}
                        <div
                            class="flex items-center gap-2 font-sans text-xs text-primary-content/75 font-semibold uppercase tracking-wider mt-4 border-t border-primary-content/15 pt-3">
                            <span>{{ sprintf(__('Por %s', 'voxpopuli'), $main_post->author) }}</span>
                            <span aria-hidden="true" class="opacity-40">//</span>
                            <span>{{ $main_post->date }}</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>
        <div class="col-span-1 flex flex-col h-[calc(100vh-4rem)] md:h-full border-r border-base-300 divide-y divide-base-300 snap-start shrink-0 overflow-hidden">
            @foreach (array_slice($featured_posts, 1) as $post)
                <div class="card image-full flex-1 rounded-none group overflow-hidden relative">
                    <figure class="w-full h-full">
                        @if (!empty($post->image))
                            <img alt="{{ $post->alt ?? $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out"
                                src="{{ $post->image }}" loading="lazy" decoding="async" />
                            {{-- Fractal noise overlay for texture --}}
                            <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay"></div>
                        @else
                            <div class="w-full h-full bg-base-200 flex items-center justify-center">
                                <span class="badge badge-ghost font-sans font-extrabold uppercase text-[10px]">{{ __('Artículo', 'voxpopuli') }}</span>
                            </div>
                        @endif
                    </figure>
                    <div class="card-body justify-end p-5 z-10 bg-gradient-to-t from-black/85 via-black/40 to-transparent">
                        {{-- Category Kicker --}}
                        <div>
                            <span class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary">
                                <span aria-hidden="true" class="opacity-50">//</span> {{ $post->category }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="card-title font-display text-base md:text-sm lg:text-base text-neutral-content font-bold leading-tight line-clamp-2">
                            <a href="{{ $post->url }}" class="hover:text-secondary transition-colors duration-300 after:absolute after:inset-0">
                                {{ $post->title }}
                            </a>
                        </h3>

                        {{-- Meta Author and Date --}}
                        <div class="flex items-center gap-2 font-sans text-[10px] text-neutral-content/75 font-semibold uppercase tracking-wider mt-1">
                            <span>{{ sprintf(__('Por %s', 'voxpopuli'), $post->author) }}</span>
                            <span aria-hidden="true" class="opacity-40">//</span>
                            <span>{{ $post->date }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-span-1 h-[calc(100vh-4rem)] md:h-full bg-base-100 flex flex-col snap-start shrink-0 overflow-hidden">
            {{-- Header/Title --}}
            <div class="p-4 border-b border-base-300 bg-base-200/50 flex items-center justify-between shrink-0">
                <span class="font-sans font-black uppercase tracking-wider text-xs text-base-content flex items-center gap-1.5">
                    <span aria-hidden="true" class="text-secondary font-black">//</span>
                    {{ __('Últimos Artículos', 'voxpopuli') }}
                </span>
                <span class="badge badge-secondary badge-xs animate-pulse" aria-hidden="true"></span>
            </div>

            {{-- List occupying the remaining/all available height --}}
            <ul class="list flex flex-col flex-1 divide-y divide-base-200 bg-base-100 h-full overflow-y-auto">
                @foreach ($latest_posts as $index => $post)
                    <li class="list-row flex-1 flex items-center gap-3 p-4 group hover:bg-base-200/60 transition-colors duration-300 relative cursor-pointer">
                        {{-- Number / Index counter --}}
                        <div class="text-3xl font-display font-light text-base-content/25 group-hover:text-secondary/60 transition-colors duration-300 tabular-nums shrink-0">
                            {{ sprintf('%02d', $index + 1) }}
                        </div>

                        {{-- Image --}}
                        <div class="size-16 rounded-box overflow-hidden bg-base-200 shrink-0 relative">
                            @if (!empty($post->image))
                                <img src="{{ $post->image }}" alt="{{ $post->alt ?? $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 ease-out" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-base-300 text-[10px] text-base-content/40 font-bold uppercase">
                                    VP
                                </div>
                            @endif
                        </div>

                        {{-- Text Content --}}
                        <div class="list-col-grow flex flex-col gap-0.5 min-w-0">
                            {{-- Category Kicker --}}
                            <span class="font-sans font-extrabold uppercase tracking-wider text-[9px] text-secondary">
                                {{ $post->category }}
                            </span>
                            {{-- Title --}}
                            <h4 class="font-display text-xs md:text-sm text-base-content font-bold leading-snug group-hover:text-primary transition-colors duration-300">
                                <a href="{{ $post->url }}" class="after:absolute after:inset-0 hover:text-primary transition-colors duration-300">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            {{-- Date --}}
                            <span class="font-sans text-[9px] text-base-content/50 font-semibold uppercase tracking-wider mt-0.5">
                                {{ $post->date }}
                            </span>
                        </div>

                        {{-- Arrow Action Indicator --}}
                        <div class="opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition duration-300 shrink-0 text-primary">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
