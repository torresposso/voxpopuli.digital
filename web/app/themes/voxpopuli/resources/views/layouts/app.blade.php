<!doctype html>
<html @php(language_attributes()) data-theme="voxpopuli">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    @php(do_action('get_header'))
    @php(wp_head())

    @if (isset($seoMetaTags))
        {!! $seoMetaTags !!}
    @endif

    @if (isset($seoJsonLd))
        {!! $seoJsonLd !!}
    @endif

    @vite(['resources/css/app.css'])
</head>

<body @php(body_class()) class="bg-base-100 text-base-content antialiased">
    @php(wp_body_open())

    <a class="sr-only focus:not-sr-only focus:absolute focus:z-999 btn btn-sm btn-primary top-4 left-4" href="#main">
        {{ __('Saltar al contenido', 'voxpopuli') }}
    </a>

    <x-drawer id="main-navigation-drawer">
        @include('sections.header')

        <main id="main">
            @yield('content')
        </main>

        @if (isset($footer))
            {{ $footer }}
        @else
            @include('sections.footer')
        @endif
    </x-drawer>

    @php(do_action('get_footer'))
    @php(wp_footer())
</body>

</html>
