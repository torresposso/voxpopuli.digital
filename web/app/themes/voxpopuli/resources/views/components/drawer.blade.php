@props([
    'id' => 'main-navigation-drawer',
])

<div {{ $attributes->merge(['class' => 'drawer min-h-screen']) }}>
    <input id="{{ $id }}" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex flex-col">
        {{ $slot }}
    </div>

    <aside class="drawer-side z-50">
        <label for="{{ $id }}" aria-label="{{ __('Cerrar menú de navegación', 'voxpopuli') }}"
            class="drawer-overlay"></label>

        <nav class="min-h-full w-80 bg-base-100 p-[1.5rem] border-r border-base-300"
            aria-label="{{ __('Menú principal lateral', 'voxpopuli') }}">
            <div class="mb-8 flex items-center justify-between">
                <span class="font-sans font-extrabold text-[1.5rem]">
                    <span class="text-accent">Vox</span><span class="text-primary"
                        style="margin-left:0.15em">Populi</span>
                </span>
                <label for="{{ $id }}" class="btn btn-sm btn-circle btn-ghost cursor-pointer"
                    aria-label="{{ __('Cerrar menú', 'voxpopuli') }}" role="button">✕</label>
            </div>

            @if (isset($sidebar))
                {{ $sidebar }}
            @else
                <ul class="menu text-base-content font-sans font-semibold text-[0.875rem] gap-[0.5rem] p-0">
                    <li><a href="{{ home_url('/') }}"
                            class="focus-visible:outline-primary">{{ __('Inicio', 'voxpopuli') }}</a></li>
                    @if (has_nav_menu('primary_navigation'))
                        {!! wp_nav_menu([
                            'theme_location' => 'primary_navigation',
                            'container' => false,
                            'items_wrap' => '%3$s',
                            'echo' => false,
                        ]) !!}
                    @else
                        <li><a href="/seccion/investigacion"
                                class="focus-visible:outline-primary">{{ __('Investigación', 'voxpopuli') }}</a></li>
                        <li><a href="/seccion/analisis"
                                class="focus-visible:outline-primary">{{ __('Análisis', 'voxpopuli') }}</a></li>
                        <li><a href="/seccion/opinion"
                                class="focus-visible:outline-primary">{{ __('Opinión', 'voxpopuli') }}</a></li>
                        <li><a href="/seccion/ahora"
                                class="focus-visible:outline-primary">{{ __('Ahora', 'voxpopuli') }}</a></li>
                    @endif
                </ul>
            @endif
        </nav>
    </aside>
</div>
