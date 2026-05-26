<!doctype html>
<html <?php language_attributes(); ?> data-theme="voxpopuli">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,200..900;1,7..72,200..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/favicon.svg') }}">
    <?php do_action('get_header'); ?>
    <?php wp_head(); ?>
    @include('partials.seo-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="app" class="drawer drawer-end">
      <input id="main-drawer" type="checkbox" class="drawer-toggle" />
      
      <div class="drawer-content flex flex-col min-h-screen">
        <a class="sr-only focus:not-sr-only" href="#main">
          {{ __('Skip to content', 'voxpopuli') }}
        </a>

        <x-navbar />

        <main id="main" class="flex-1 pt-16">
          @yield('content')
        </main>

        @hasSection('sidebar')
          <aside class="sidebar">
            @yield('sidebar')
          </aside>
        @endif

        @include('sections.footer')
      </div>

      <x-drawer />
    </div>

    <?php do_action('get_footer'); ?>
    <?php wp_footer(); ?>
  </body>
</html>
