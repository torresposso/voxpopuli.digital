@if (!empty($featured_posts) && count($featured_posts) >= 4)
    @php
        $main_post = $featured_posts[0];
    @endphp

    {{-- 
       Módulo de Hero Principal: Layout Grid de tres columnas
       - Columna 1 (cols 1-2): Artículo principal destacado (LCP focal)
       - Columna 2 (col 3): Artículos secundarios en formato de pila vertical
       - Columna 3 (col 4): Sidebar de Últimos Artículos (Listado interactivo)
    --}}
    <section
        class="w-full max-w-[1440px] mx-auto bg-base-100 animate-fade-in-up grid grid-cols-1 md:grid-cols-4 gap-2 py-2 px-4"
        aria-label="{{ __('Artículos principales destacados', 'voxpopuli') }}">

        {{-- Artículo Principal Destacado (LCP Candidate) --}}
        <article
            class="bg-primary col-span-1 md:col-span-2 h-[calc(100vh-4rem)] flex flex-col justify-between overflow-hidden rounded-xl border border-base-300 shadow-md group">
            <div class="h-full flex flex-col">
                {{-- Contenedor de Imagen Destacada (Optimizada para LCP) --}}
                <figure class="relative w-full aspect-video overflow-hidden bg-base-200 rounded-none shrink-0 m-0">
                    @if (!empty($main_post->image))
                        <img alt="{{ $main_post->alt ?? $main_post->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out"
                            src="{{ $main_post->image }}" loading="eager" fetchpriority="high" decoding="async" />

                        {{-- Overlay sutil con tinte de marca --}}
                        <div
                            class="absolute inset-0 bg-primary opacity-15 mix-blend-overlay group-hover:opacity-0 transition duration-500 ease-out pointer-events-none">
                        </div>
                        {{-- Textura de ruido analógico/digital --}}
                        <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay">
                        </div>
                    @else
                        <div class="w-full h-full bg-base-200 flex items-center justify-center">
                            <span
                                class="badge badge-ghost font-sans font-extrabold uppercase text-xs">{{ __('No hay imagen', 'voxpopuli') }}</span>
                        </div>
                    @endif
                </figure>

                {{-- Cuerpo del Artículo Principal --}}
                <div class="px-4 flex-0 flex flex-col ">
                    <div class="w-full flex-1 md:h-2/5 flex flex-col justify-between pt-5">
                        <div class="flex flex-col gap-2">
                            {{-- Kicker de Categoría y Etiqueta de Destacado --}}
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

                            {{-- Título Principal con Enlace Semántico --}}
                            <h2
                                class="font-display text-3xl md:text-3xl lg:text-4xl xl:text-5xl text-primary-content font-black leading-[1.05] tracking-tight group-hover:text-secondary transition-colors duration-300">
                                <a href="{{ $main_post->url }}"
                                    class="hover:text-secondary text-primary-content transition-colors duration-300">
                                    {{ $main_post->title }}
                                </a>
                            </h2>

                            {{-- Extracto del Artículo --}}
                            @if (!empty($main_post->excerpt))
                                <p class="py-6 text-primary-content/85 text-base md:text-lg line-clamp-5">
                                    {{ $main_post->excerpt }}
                                </p>
                            @endif
                        </div>

                        {{-- Metadata del Autor y Fecha de Publicación --}}
                        <footer
                            class="flex items-center gap-2 font-sans text-xs text-primary-content/75 font-semibold uppercase tracking-wider mt-4 border-t border-primary-content/15 py-6">
                            <span>{{ sprintf(__('Por %s', 'voxpopuli'), $main_post->author) }}</span>
                            <span aria-hidden="true" class="opacity-40">//</span>
                            <time
                                datetime="{{ date('Y-m-d', strtotime($main_post->date)) }}">{{ $main_post->date }}</time>
                        </footer>
                    </div>
                </div>
            </div>
        </article>

        {{-- Columna Central: Artículos Secundarios Destacados --}}
        <div class="col-span-1 flex flex-col gap-2 h-[calc(100vh-4rem)] overflow-hidden" role="feed"
            aria-label="{{ __('Artículos secundarios destacados', 'voxpopuli') }}">
            @foreach (array_slice($featured_posts, 1) as $post)
                <article
                    class="flex-1 rounded-xl border border-base-300 shadow-md group overflow-hidden relative w-full h-full flex flex-col justify-end [isolation:isolate] [transform:translateZ(0)]">

                    {{-- Capa de Imagen de Fondo (Optimizada contra aliasing mediante GPU transform) --}}
                    <figure class="absolute inset-0 w-full h-full m-0 z-0 overflow-hidden">
                        @if (!empty($post->image))
                            <img alt="{{ $post->alt ?? $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-out [backface-visibility:hidden] [transform:translate3d(0,0,0)]"
                                src="{{ $post->image }}" loading="eager" fetchpriority="high" decoding="async" />
                        @else
                            <div class="w-full h-full bg-base-200 flex items-center justify-center">
                                <span
                                    class="badge badge-ghost font-sans font-extrabold uppercase text-[10px]">{{ __('Artículo', 'voxpopuli') }}</span>
                            </div>
                        @endif
                    </figure>

                    {{-- Degradado premium ultra-oscuro (Inline CSS para asegurar renderizado perfecto independiente del framework) --}}
                    <div class="absolute inset-0 opacity-100 transition duration-500 ease-out pointer-events-none z-10"
                        style="background: linear-gradient(to top, rgba(0,0,0,0.98) 0%, rgba(0,0,0,0.85) 50%, rgba(0,0,0,0.2) 100%);">
                    </div>
                    {{-- Textura de ruido --}}
                    <div class="absolute inset-0 opacity-10 pointer-events-none mix-blend-overlay noise-overlay z-10">
                    </div>

                    {{-- Contenido e Información --}}
                    <div class="relative z-20 p-5 flex flex-col gap-1.5 justify-end h-full">
                        {{-- Kicker de Categoría --}}
                        <div>
                            <span
                                class="font-sans font-extrabold uppercase tracking-[0.2em] text-[10px] text-secondary drop-shadow-sm">
                                <span aria-hidden="true" class="opacity-70">//</span> {{ $post->category }}
                            </span>
                        </div>

                        {{-- Título Secundario con Click Area Expandida --}}
                        <h3 class="font-display text-lg  text-white font-bold leading-tight  drop-shadow-md">
                            <a href="{{ $post->url }}"
                                class="hover:text-secondary transition-colors duration-300 after:absolute after:inset-0">
                                {{ $post->title }}
                            </a>
                        </h3>

                        {{-- Metadata del Artículo --}}
                        <footer
                            class="flex items-center gap-2 font-sans text-[10px] text-white/80 font-semibold uppercase tracking-wider mt-1 drop-shadow-sm">
                            <span>{{ sprintf(__('Por %s', 'voxpopuli'), $post->author) }}</span>
                            <span aria-hidden="true" class="opacity-50">//</span>
                            <time datetime="{{ date('Y-m-d', strtotime($post->date)) }}">{{ $post->date }}</time>
                        </footer>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Columna Lateral: Últimas Novedades --}}
        <aside
            class="col-span-1 h-[calc(100vh-4rem)] bg-base-100 flex flex-col border border-base-300 rounded-xl overflow-hidden shadow-md"
            aria-labelledby="sidebar-title">

            {{-- Encabezado del Listado --}}
            <header class="p-4 border-b border-base-300 bg-base-200/50 flex items-center justify-between shrink-0">
                <h2 id="sidebar-title"
                    class="font-sans font-black uppercase tracking-wider text-xs text-base-content flex items-center gap-1.5 m-0">
                    <span aria-hidden="true" class="text-secondary font-black">//</span>
                    {{ __('Últimos Artículos', 'voxpopuli') }}
                </h2>
                <span class="badge badge-secondary badge-xs animate-pulse" aria-hidden="true"></span>
            </header>

            {{-- Feed de Novedades --}}
            <ul class="list flex flex-col flex-1 divide-y divide-base-200 bg-base-100 h-full overflow-y-auto p-0 m-0">
                @foreach ($latest_posts as $index => $post)
                    <li
                        class="list-row flex-1 flex items-center gap-3 p-4 group hover:bg-base-200/60 transition-colors duration-300 relative cursor-pointer">
                        {{-- Contador visual --}}
                        <div
                            class="text-3xl font-display font-light text-base-content/25 group-hover:text-secondary/60 transition-colors duration-300 tabular-nums shrink-0">
                            {{ sprintf('%02d', $index + 1) }}
                        </div>

                        {{-- Miniatura del Artículo --}}
                        <div class="size-16 rounded-box overflow-hidden bg-base-200 shrink-0 relative">
                            @if (!empty($post->image))
                                <img src="{{ $post->image }}" alt="{{ $post->alt ?? $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300 ease-out" />
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-base-300 text-[10px] text-base-content/40 font-bold uppercase">
                                    VP
                                </div>
                            @endif
                        </div>

                        {{-- Detalles y Enlace --}}
                        <div class="list-col-grow flex flex-col gap-0.5 min-w-0">
                            <span class="font-sans font-extrabold uppercase tracking-wider text-[9px] text-secondary">
                                {{ $post->category }}
                            </span>

                            <h3
                                class="font-display text-xs md:text-sm text-base-content font-bold leading-snug group-hover:text-primary transition-colors duration-300 m-0">
                                <a href="{{ $post->url }}"
                                    class="after:absolute after:inset-0 hover:text-primary transition-colors duration-300">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <time datetime="{{ date('Y-m-d', strtotime($post->date)) }}"
                                class="font-sans text-[9px] text-base-content/50 font-semibold uppercase tracking-wider mt-0.5">
                                {{ $post->date }}
                            </time>
                        </div>

                        {{-- Indicador visual de acción --}}
                        <div
                            class="opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition duration-300 shrink-0 text-primary">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </li>
                @endforeach
            </ul>
        </aside>
    </section>
@endif
