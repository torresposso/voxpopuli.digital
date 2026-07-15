<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <style>
            *,:after,:before{box-sizing:border-box;border:0 solid}html{line-height:1.15;-webkit-text-size-adjust:100%;font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif;line-height:1.5}body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif}a{color:inherit;text-decoration:inherit}code{font-family:Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace}
        </style>
    </head>
    <body class="antialiased bg-base-100 text-base-content">
        <div class="relative flex items-top justify-center min-h-screen bg-base-100 text-base-content sm:items-center sm:pt-0" role="main">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col items-center pt-8 sm:justify-start sm:pt-0">

                    <div class="text-center mb-6">
                        <p class="text-sm font-semibold text-primary tracking-widest uppercase">
                            Vox Populi Digital
                        </p>
                    </div>

                    <div class="flex items-center">
                        <h1 class="px-4 text-lg text-base-content border-r border-base-300">
                            @yield('code')
                        </h1>

                        <div class="ml-4 text-lg text-base-content">
                            @yield('message')
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-base-content/60">
                            @yield('message', __('¡Oops!'))
                        </p>
                        <p class="text-sm text-base-content/40 mt-1">
                            {{ __('Algo salió mal.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
