# Cómo crear un nuevo theme Sage 11 desde cero en este proyecto

> Fecha: 2026-07-15
> Proyecto: voxpopuli.digital — WordPress 6.9.4 / Bedrock / Sage 11 (clone local) / Acorn ^6.0
> Objetivo: Crear `web/app/themes/vox-caribe/` como un theme Sage 11 funcional

---

## 1. ¿Qué es Sage 11 realmente?

**Sage 11 NO es un paquete Composer.** No hay `vendor/roots/sage`. Sage 11 es un *starter theme* cuyo código vive íntegramente dentro del directorio del theme. La única dependencia externa que necesita es **Acorn** (`roots/acorn ^6.0`), que es un paquete Composer que proporciona el contenedor Laravel (Illuminate) y la clase base `Roots\Acorn\Sage\SageServiceProvider`.

**Fuentes:**
- No existe `vendor/roots/sage/` en el proyecto.
- `vendor/roots/acorn/src/Roots/Acorn/Sage/SageServiceProvider.php` proporciona toda la funcionalidad "Sage" (template hierarchy, view filters, bindings de Blade).

Todo lo demás en el theme (`app/`, `resources/views/`, `vite.config.js`, etc.) **es código propio del proyecto**, copiado del starter Sage y personalizado. Esto significa que para crear un nuevo theme hay que copiar la estructura entera de un theme Sage existente y renombrar.

---

## 2. Árbol completo de archivos que necesita el nuevo theme

Basado en el análisis del theme `voxpopuli`, estos son los archivos requeridos para que `vox-caribe` sea funcional:

### 2.1 Archivos obligatorios (mínimo funcional)

| Archivo | Propósito | Origen |
|---|---|---|
| `style.css` | Cabecera del theme (WordPress lo requiere) | Propio, adaptado |
| `index.php` | Entry point de Blade (Sage lo usa como fallback) | Copia de voxpopuli |
| `functions.php` | Boot de Acorn + carga de providers y app/*.php | Copia, adaptado |
| `composer.json` | Dependencias PHP + autoload PSR-4 | Copia, adaptado |
| `package.json` | Dependencias frontend (Vite, Tailwind, daisyUI) | Copia, adaptado |
| `vite.config.js` | Build de Vite con Tailwind v4 + `@roots/vite-plugin` | Copia, adaptado |
| `theme.json` | Config del block editor (settings de layout, color, spacing) | Copia, adaptado |
| `.gitignore` | Ignorar `/vendor`, `/node_modules`, `/public/*` menos `.gitkeep` | Copia directa |
| `app/Providers/ThemeServiceProvider.php` | Extiende `SageServiceProvider`, registra composers y bindings | Copia, adaptado |
| `app/setup.php` | `after_setup_theme` (menús, features, sidebars) + block editor | Copia, adaptado |
| `app/filters.php` | Filtros de WordPress (opcional, se puede empezar vacío en `namespace App;`) | Copia, adaptado |
| `resources/views/layouts/app.blade.php` | Layout base HTML con `@vite()` | Copia, adaptado |
| `resources/css/app.css` | Tailwind v4 `@import "tailwindcss"` + daisyUI plugin + tema | Copia, adaptado |
| `resources/css/editor.css` | Estilos para el block editor (`@import "tailwindcss"`) | Copia directa |
| `resources/js/app.js` | Entry point JS (puede estar vacío) | Copia directa |
| `resources/js/editor.js` | Editor JS (puede tener el import mínimo de WP) | Copia, adaptado |
| `public/.gitkeep` | Para que `public/` exista en git y Vite pueda escribir ahí | Copia directa |
| `resources/fonts/.gitkeep` | Para fuentes locales | Opcional |
| `resources/images/.gitkeep` | Para imágenes del theme | Opcional |

### 2.2 Archivos opcionales pero recomendados

| Archivo | Propósito |
|---|---|
| `screenshot.png` | Screenshot del theme (1200x900) |
| `LICENSE.md` | MIT License |
| `README.md` | Documentación del theme |

### 2.3 Views adicionales típicas de Sage

Para que el theme renderice páginas reales, necesita al menos algunas vistas Blade:

| Ruta | Propósito |
|---|---|
| `resources/views/index.blade.php` | Plantilla principal (loop genérico) |
| `resources/views/single.blade.php` | Entrada individual |
| `resources/views/page.blade.php` | Página estática |
| `resources/views/404.blade.php` | Página 404 |
| `resources/views/archive.blade.php` | Archivos |
| `resources/views/search.blade.php` | Resultados de búsqueda |
| `resources/views/category.blade.php` | Categorías |
| `resources/views/front-page.blade.php` | Portada |
| `resources/views/sections/header.blade.php` | Header |
| `resources/views/sections/footer.blade.php` | Footer |
| `resources/views/partials/content.blade.php` | Loop content |
| `resources/views/partials/content-single.blade.php` | Single content |

---

## 3. Comandos precisos para crear el nuevo theme

### 3.1 Crear directorios

```bash
# Estructura base
mkdir -p web/app/themes/vox-caribe/{app/Providers,resources/{views/{layouts,components,sections,partials},css,js,fonts,images},public}

# Para autoloading PSR-4 (ver sección 6)
mkdir -p web/app/themes/vox-caribe/app
```

### 3.2 Copiar archivos del theme voxpopuli (y adaptar)

```bash
# Raíz
cp web/app/themes/voxpopuli/.gitignore          web/app/themes/vox-caribe/
cp web/app/themes/voxpopuli/index.php            web/app/themes/vox-caribe/
cp web/app/themes/voxpopuli/theme.json           web/app/themes/vox-caribe/
cp web/app/themes/voxpopuli/resources/css/editor.css web/app/themes/vox-caribe/resources/css/
cp web/app/themes/voxpopuli/resources/js/app.js  web/app/themes/vox-caribe/resources/js/
cp web/app/themes/voxpopuli/resources/js/editor.js web/app/themes/vox-caribe/resources/js/
cp web/app/themes/voxpopuli/public/.gitkeep      web/app/themes/vox-caribe/public/
cp web/app/themes/voxpopuli/resources/fonts/.gitkeep web/app/themes/vox-caribe/resources/fonts/
cp web/app/themes/voxpopuli/resources/images/.gitkeep web/app/themes/vox-caribe/resources/images/
```

Los siguientes archivos NO se copian directamente porque requieren cambios por namespace:
- `style.css` — crear desde cero
- `composer.json` — crear desde cero (o copiar y editar)
- `package.json` — copiar y editar `name`
- `vite.config.js` — copiar y editar `base`
- `functions.php` — crear desde cero
- `app/Providers/ThemeServiceProvider.php` — crear desde cero
- `app/setup.php` — copiar y editar text domain
- `app/filters.php` — copiar y editar text domain
- `resources/views/layouts/app.blade.php` — copiar y editar text domain + data-theme
- `resources/css/app.css` — copiar y editar theme name de daisyUI

---

## 4. Qué cambiar en cada archivo

### 4.1 `style.css`

```css
/*
Theme Name:         Vox Caribe
Theme URI:          https://voxpopuli.digital
Description:        Tema Caribe para VoxPopuli Digital.
Version:            11.x-dev
Author:             VoxPopuli Digital
Author URI:         https://voxpopuli.digital
Text Domain:        vox-caribe
License:            MIT License
License URI:        https://opensource.org/licenses/MIT
Requires PHP:       8.3
Requires at least:  6.6
*/
```

Cambios clave:
- `Theme Name` → `Vox Caribe`
- `Text Domain` → `vox-caribe`
- `Description` → descripción apropiada

### 4.2 `composer.json`

```json
{
  "name": "roots/sage",
  "type": "wordpress-theme",
  "license": "MIT",
  "description": "Vox Caribe theme — based on Sage 11",
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  },
  "require": {
    "php": ">=8.3",
    "roots/acorn": "^6.0"
  },
  "config": {
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true
  },
  "minimum-stability": "dev",
  "prefer-stable": true,
  "scripts": {
    "post-autoload-dump": [
      "Roots\\Acorn\\ComposerScripts::postAutoloadDump"
    ]
  }
}
```

> ⚠️ **Nota sobre el namespace `App\`**: Ver sección 6 para la discusión sobre PSR-4 con dos themes.

### 4.3 `package.json`

```json
{
  "name": "vox-caribe",
  "private": true,
  "engines": {
    "node": "^20.19.0 || >=22.12.0"
  },
  "type": "module",
  "scripts": {
    "dev": "vite",
    "build": "vite build"
  },
  "devDependencies": {
    "@roots/vite-plugin": "^2.0.0",
    "@tailwindcss/vite": "^4.0.0",
    "laravel-vite-plugin": "^3.0.0",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
  },
  "dependencies": {
    "daisyui": "5.5.20"
  }
}
```

Cambios clave:
- `"name"` → `"vox-caribe"` (solo informativo)
- `devDependencies` y `dependencies` deben igualar las versiones del proyecto (usar las de voxpopuli como referencia)

### 4.4 `vite.config.js`

```js
import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

const devHost = process.env.VITE_DEV_HOST || 'localhost';

if (! process.env.APP_URL) {
  process.env.APP_URL = 'http://example.test';
}

export default defineConfig({
  base: '/app/themes/vox-caribe/public/build/',  // ← CAMBIA ESTO
  server: {
    host: true,
    port: 5175,  // ← USA UN PUERTO DISTINTO (5174 ocupado)
    strictPort: true,
    cors: {
      origin: [
        'http://localhost:8080',
        'http://127.0.0.1:8080',
        `http://${devHost}:8080`,
        `http://${devHost}:5175`,
      ],
    },
    hmr: {
      host: devHost,
      clientPort: 5175,
      protocol: 'ws',
    },
  },
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
      assets: ['resources/images/**', 'resources/fonts/**'],
    }),
    wordpressPlugin(),
    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
      disableTailwindBorderRadius: false,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
    },
  },
})
```

Cambios clave:
- `base` → `'/app/themes/vox-caribe/public/build/'`
- `server.port` → `5175` (el 5174 lo usa voxpopuli)
- `server.cors.origin` → actualizar el puerto
- `server.hmr.clientPort` → `5175`

### 4.5 `functions.php`

```php
<?php

use App\Providers\ThemeServiceProvider;
use Roots\Acorn\Application;

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'vox-caribe'));
}

require $composer;

Application::configure()
    ->withProviders([
        ThemeServiceProvider::class,
    ])
    ->boot();

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'vox-caribe'), $file)
            );
        }
    });
```

Cambios clave:
- Text domain en `__()` pasa de `'voxpopuli'` a `'vox-caribe'`
- Si solo se necesita `ThemeServiceProvider` (sin SEO), se omite `SeoServiceProvider`

### 4.6 `app/Providers/ThemeServiceProvider.php`

```php
<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    public function register()
    {
        parent::register();
    }

    public function boot()
    {
        parent::boot();

        $this->registerViewComposers();
    }

    private function registerViewComposers(): void
    {
        $view = $this->app->make('view');

        // Registrar composers específicos del theme vox-caribe
        $view->composer('*', \App\View\Composers\App::class);
    }
}
```

Cambios clave:
- Namespace se mantiene como `App\Providers` (si se usa `App\`)
- Se registran solo los composers que necesite el nuevo theme
- No incluye bindings de SEO (JsonLd, Vite personalizado) a menos que se necesiten

### 4.7 `app/setup.php`

```php
<?php

namespace App;

use Illuminate\Support\Facades\Vite;

add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');
    $settings['styles'][] = ['css' => "@import url('{$style}')"];
    return $settings;
});

add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) return;
    if (file_exists(get_theme_file_path('resources/js/editor.js'))
        && filesize(get_theme_file_path('resources/js/editor.js')) > 0) {
        echo Vite::withEntryPoints(['resources/js/editor.js'])->toHtml();
    }
});

add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

add_action('after_setup_theme', function () {
    remove_theme_support('block-templates');

    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'vox-caribe'),
    ]);

    remove_theme_support('core-block-patterns');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', [
        'caption', 'comment-form', 'comment-list', 'gallery', 'search-form', 'script', 'style',
    ]);
    add_theme_support('customize-selective-refresh-widgets');
}, 20);
```

Cambios clave:
- Text domain en `__()` pasa a `'vox-caribe'`
- Los sidebars/widgets/menús se personalizan para el nuevo theme
- El filtro `theme_file_path` redirige `theme.json` → `public/build/assets/theme.json`

### 4.8 `resources/views/layouts/app.blade.php`

```blade
<!doctype html>
<html @php(language_attributes()) data-theme="vox-caribe">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())
    @vite(['resources/css/app.css'])
</head>

<body @php(body_class()) class="bg-base-100 text-base-content antialiased">
    @php(wp_body_open())

    @include('sections.header')

    <main id="main">
        @yield('content')
    </main>

    @include('sections.footer')

    @php(do_action('get_footer'))
    @php(wp_footer())
</body>

</html>
```

Cambios clave:
- `data-theme` → `"vox-caribe"` (debe coincidir con el nombre del tema daisyUI en `app.css`)
- Text domain en `__()` (si hay) → `'vox-caribe'`
- Se eliminan referencias a `$seoMetaTags` / `$seoJsonLd` si no se usa SEO
- Se simplifica la estructura (sin drawer si no se necesita)

### 4.9 `resources/css/app.css`

```css
@import "tailwindcss" theme(static);
@source "../../app/**/*.php";
@source "../**/*.blade.php";
@source "../**/*.js";

@plugin "daisyui" {
  themes: vox-caribe;
}
@plugin "@tailwindcss/typography";

@plugin "daisyui/theme" {
  name: "vox-caribe";
  default: true;
  prefersdark: false;
  color-scheme: light;

  --color-base-100: oklch(100% 0 0);
  --color-base-200: oklch(98% 0.005 272.314);
  --color-base-300: oklch(96% 0.018 272.314);
  --color-base-content: oklch(21% 0.006 285.885);

  --color-primary: oklch(35% 0.144 278.697);
  --color-primary-content: oklch(75% 0.183 55.934);

  --color-accent: oklch(75% 0.183 55.934);
  --color-accent-content: oklch(35% 0.144 278.697);

  --color-neutral: oklch(14% 0.005 285.823);
  --color-neutral-content: oklch(92% 0.004 286.32);

  --radius-selector: 0.25rem;
  --radius-field: 0.25rem;
  --radius-box: 0.25rem;

  --size-selector: 0.25rem;
  --size-field: 0.25rem;

  --border: 1px;
  --depth: 0;
  --noise: 0;
}

@theme {
  --font-display: "Plus Jakarta Sans", system-ui, sans-serif;
  --font-serif: "Newsreader", Georgia, serif;
  --font-sans: "Plus Jakarta Sans", system-ui, sans-serif;
}
```

Cambios clave:
- `@plugin "daisyui" { themes: vox-caribe; }` — nombre único del theme daisyUI
- `@plugin "daisyui/theme" { name: "vox-caribe"; ... }` — el nombre debe coincidir con `data-theme` en el layout
- Colores y tipografías se personalizan para la identidad de vox-caribe

### 4.10 `index.php`

No necesita cambios. Es simplemente:

```php
<?php

echo view(app('sage.view'), app('sage.data'))->render();
```

Este archivo es el que WordPress ejecuta (a través de `template_include`), que Sage redirige a la vista Blade correspondiente.

---

## 5. Cómo registrar el theme en Bedrock

**No se necesita registro en `composer.json` del proyecto.** Bedrock no requiere que los themes locales estén en el `composer.json` raíz.

Para que WordPress reconozca el theme, basta con:

1. Tener el directorio `web/app/themes/vox-caribe/` con su `style.css` válido.
2. Activar el theme via WP Admin o WP-CLI:

```bash
docker exec -w /app voxpopuli-app-1 wp theme activate vox-caribe
```

Si se quisiera distribuir el theme vía Composer (para despliegues), se podría agregar un [repositorio path](https://getcomposer.org/doc/05-repositories.md#path) en el `composer.json` raíz:

```json
"repositories": [
    {
        "type": "path",
        "url": "web/app/themes/vox-caribe"
    }
],
"require": {
    "roots/sage": "*"
}
```

Pero esto **no es necesario** para desarrollo local.

---

## 6. El problema del autoloading PSR-4 con dos themes

### 6.1 El escenario

Cada theme Sage 11 define en su `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/"
    }
}
```

El `composer.json` raíz también tiene en `autoload-dev`:

```json
"autoload-dev": {
    "psr-4": {
        "App\\": "web/app/themes/voxpopuli/app/"
    }
}
```

### 6.2 ¿Hay conflicto?

**Depende del contexto:**

| Contexto | ¿Conflicto? | Explicación |
|---|---|---|
| **Solo un theme activo en WP** | No | WordPress solo carga el `functions.php` del theme activo, que a su vez carga su propio `vendor/autoload.php`. El theme inactivo nunca se ejecuta. |
| **Autoloader global de Composer** (raíz) | Sí | El `vendor/autoload.php` raíz registra `App\` → `web/app/themes/voxpopuli/app/` (en dev, via autoload-dev). Esto significa que si el theme vox-caribe se activa, sus clases `App\*` apuntarían al directorio de voxpopuli si el autoloader raíz tiene prioridad. |
| **Tests (Pest)** | Sí | `composer test` usa el autoloader raíz, que tiene `App\` → voxpopuli. Las clases de vox-caribe con namespace `App\` no se encontrarían. |

### 6.3 Soluciones recomendadas

#### Opción A (recomendada): Namespace único por theme

Cambiar el namespace de `vox-caribe` de `App\` a `VoxCaribe\` (o similar):

En `composer.json` del theme:
```json
"autoload": {
    "psr-4": {
        "VoxCaribe\\": "app/"
    }
}
```

Luego renombrar **todos** los namespaces dentro de `app/`:
- `namespace App\Providers;` → `namespace VoxCaribe\Providers;`
- `namespace App;` en `setup.php` / `filters.php` → `namespace VoxCaribe;`
- `\App\View\Composers\*` → `\VoxCaribe\View\Composers\*`
- `\App\Providers\*` → `\VoxCaribe\Providers\*`

En `functions.php`:
```php
use VoxCaribe\Providers\ThemeServiceProvider;
```

**Ventajas:** Cero colisiones, tests pueden incluir ambos themes, intenciones claras.
**Desventajas:** Se desvía de la convención Sage de usar `App\`.

#### Opción B: Mantener `App\` pero solo un theme activo a la vez

- Cada theme tiene su propio `vendor/autoload.php` que se carga solo cuando el theme está activo.
- En el autoloader raíz (`composer.json` raíz), **no** incluir el `autoload-dev` apuntando a vox-caribe. Mantener solo voxpopuli en `autoload-dev`.
- Para tests, vox-caribe necesitaría su propia entrada en `autoload-dev` o ejecutar tests desde dentro del theme.

**Ventajas:** Sigue la convención Sage, funciona en producción sin cambios.
**Desventajas:** No se pueden tener los dos themes cargados simultáneamente (para migración progresiva o comparación).

#### Opción C: Usar `classmap` en lugar de PSR-4 para el segundo theme

```json
"autoload": {
    "classmap": [
        "app/"
    ]
}
```

**Ventajas:** Namespace puede seguir siendo `App\`, sin colisión de directorios.
**Desventajas:** Pierdes la conveniencia del PSR-4, cada clase nueva requiere `composer dump-autoload`.

### 6.4 Recomendación final

Para este proyecto, con voxpopuli como theme principal activo y vox-caribe como un theme separado (no un child theme):

> **Usar namespace `App\`** (Opción B) y solo activar un theme a la vez. Es lo más simple y sigue la convención Sage. El autoloading PSR-4 de cada theme se resuelve desde su propio `vendor/autoload.php`. El autoload-dev raíz solo apunta a voxpopuli, que es el theme que se testea.

Si en el futuro se necesita tener ambos themes cargados simultáneamente (ej. para importar clases entre ellos), migrar a namespaces distintos (Opción A).

---

## 7. Acorn y múltiples themes

**Acorn no requiere configuración especial para múltiples themes.** El boot de Acorn ocurre desde el `functions.php` del theme activo:

```php
Application::configure()
    ->withProviders([ThemeServiceProvider::class])
    ->boot();
```

Esto crea una instancia del contenedor de Acorn que está atada al theme activo. Cuando se activa vox-caribe:

1. WordPress carga `web/app/themes/vox-caribe/functions.php`
2. Ese archivo llama a `Application::configure()->withProviders(...)->boot()`
3. Acorn configura el contenedor, registra los providers y los view composers
4. SageServiceProvider registra los filtros de template hierarchy
5. Las vistas se resuelven desde `resources/views/` del theme activo

No hay estado compartido entre themes. Cada theme activo crea su propia instancia de Acorn.

**Importante:** Si voxpopuli y vox-caribe comparten providers o servicios (ej. `SeoServiceProvider`), ese código tendría que duplicarse o extraerse a un paquete/mu-plugin compartido.

---

## 8. Proceso de creación resumido (checklist)

- [ ] Crear directorios: `web/app/themes/vox-caribe/{app/Providers,resources/views/{layouts,components,sections,partials},resources/{css,js,fonts,images},public}`
- [ ] Escribir `style.css` con cabecera y text domain `vox-caribe`
- [ ] Crear `composer.json` con `roots/acorn ^6.0` y autoload PSR-4
- [ ] Crear `package.json` con Vite 8, Tailwind 4, daisyUI 5, `@roots/vite-plugin` 2
- [ ] Crear `vite.config.js` con `base` apuntando a `/app/themes/vox-caribe/public/build/` y puerto 5175
- [ ] Crear `functions.php` con Acorn boot + providers
- [ ] Crear `app/Providers/ThemeServiceProvider.php` extendiendo `SageServiceProvider`
- [ ] Crear `app/setup.php` con `after_setup_theme`, block editor styles, `theme_file_path` filter
- [ ] Crear `app/filters.php` (mínimo o vacío con `namespace App;`)
- [ ] Copiar `index.php` (no necesita cambios)
- [ ] Copiar `theme.json` y ajustar si es necesario
- [ ] Copiar `.gitignore`
- [ ] Crear `resources/views/layouts/app.blade.php` con `data-theme="vox-caribe"`
- [ ] Crear `resources/css/app.css` con Tailwind + daisyUI theme `vox-caribe`
- [ ] Copiar `resources/css/editor.css`
- [ ] Crear `resources/js/app.js` (vacío o con imports)
- [ ] Crear `resources/js/editor.js` (mínimo)
- [ ] Crear `.gitkeep` en `public/`, `resources/fonts/`, `resources/images/`
- [ ] Ejecutar `docker exec -w /app/web/app/themes/vox-caribe voxpopuli-app-1 composer install`
- [ ] Ejecutar `npm install` (o recrear container node)
- [ ] Activar con `wp theme activate vox-caribe`
- [ ] Verificar que el frontend cargue correctamente
