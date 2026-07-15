# PRD: Profundización Arquitectónica — VoxPopuli Digital

**Estado:** Borrador
**Versión:** 1.0
**Audiencia:** Implementadores del tema Sage 11
**Dominios:** WordPress 6.9.4, Bedrock, Sage 11, Acorn 6.2, Tailwind v4 / daisyUI 5

---

## Resumen ejecutivo

La auditoría arquitectónica (`/tmp/architecture-review-20260714.html`) identificó 8 oportunidades de profundización en el tema voxpopuli. Este PRD consolida 7 de ellas (excluyendo PostDesignToken transformer) en un plan de implementación secuencial. El objetivo es restaurar la profundidad perdida por la eliminación del módulo SEO y el tooling de desarrollo, y establecer patrones arquitectónicos sostenibles.

**Prioridad:** Reconstituir el módulo SEO primero — es el de máximo leverage, código ya escrito y testeado, sin dependencias del resto del tema.

---

## Tabla de contenidos

1. [Estrategia de implementación](#1-estrategia-de-implementación)
2. [Fase 1: Reconstituir módulo SEO](#2-fase-1-reconstituir-módulo-seo)
3. [Fase 2: Reconstituir dev tooling](#3-fase-2-reconstituir-dev-tooling)
4. [Fase 3: Eliminar código muerto](#4-fase-3-eliminar-código-muerto)
5. [Fase 4: Profundizar ThemeServiceProvider](#5-fase-4-profundizar-themeserviceprovider)
6. [Fase 5: Capa de repositorio de contenido](#6-fase-5-capa-de-repositorio-de-contenido)
7. [Fase 6: Normalizar text domain](#7-fase-6-normalizar-text-domain)
8. [Fase 7: Templates de archivo faltantes](#8-fase-7-templates-de-archivo-faltantes)
9. [Dependencias y orden](#9-dependencias-y-orden)
10. [Criterios de aceptación](#10-criterios-de-aceptación)

---

## 1. Estrategia de implementación

### Principios rectores

1. **Un PR por fase** — cada fase es un commit atómico. No mezclar fases en un mismo commit.
2. **Tests primero** — donde existan tests (SEO), verificar que pasen después de cada cambio.
3. **Backward compatibility** — ninguna fase debe romper funcionalidad existente del frontend.
4. **Archivos fuente únicos** — el backup `web/app/themes/voxpopuli-old/` es la fuente de verdad para el código eliminado. Una vez reconstituido, el backup puede eliminarse.

### Orden de fases

| Orden | Fase | Dependencias | Riesgo | Esfuerzo estimado |
|-------|------|--------------|--------|-------------------|
| 1 | Módulo SEO | Ninguna | Bajo | 1 sesión |
| 2 | Dev tooling | Ninguna | Bajo | 1 sesión |
| 3 | Código muerto | Ninguna | Bajo | 1 sesión |
| 4 | ThemeServiceProvider | Fase 2 (tiene los hooks) | Medio | 1-2 sesiones |
| 5 | Content Repository | Fase 4 (provider) | Medio | 1-2 sesiones |
| 6 | Text domain | Ninguna | Bajo | 1 sesión |
| 7 | Archive templates | Fase 5 (repository) | Bajo | 1 sesión |

---

## 2. Fase 1: Reconstituir módulo SEO

### Problema

Todo el namespace `App\Seo` (6 clases), `SeoServiceProvider`, el `Seo` composer, y 30+ tests fueron eliminados del working tree. El sitio no tiene salida SEO: sin OG tags, Twitter Cards, JSON-LD structured data, meta descriptions, canonical URLs, sitemap.xml, ni admin UI para metadatos.

### Archivos fuente (backup)

Todos existen en `web/app/themes/voxpopuli-old/`:

| Clase | Ruta origen | Ruta destino |
|-------|-------------|--------------|
| `SeoMeta` | `voxpopuli-old/app/Seo/SeoMeta.php` | `web/app/themes/voxpopuli/app/Seo/SeoMeta.php` |
| `JsonLd` | `voxpopuli-old/app/Seo/JsonLd.php` | `web/app/themes/voxpopuli/app/Seo/JsonLd.php` |
| `MetaRenderer` | `voxpopuli-old/app/Seo/MetaRenderer.php` | `web/app/themes/voxpopuli/app/Seo/MetaRenderer.php` |
| `Sitemap` | `voxpopuli-old/app/Seo/Sitemap.php` | `web/app/themes/voxpopuli/app/Seo/Sitemap.php` |
| `TitleExpander` | `voxpopuli-old/app/Seo/TitleExpander.php` | `web/app/themes/voxpopuli/app/Seo/TitleExpander.php` |
| `Migration` | `voxpopuli-old/app/Seo/Migration.php` | `web/app/themes/voxpopuli/app/Seo/Migration.php` |
| `SeoServiceProvider` | `voxpopuli-old/app/Providers/SeoServiceProvider.php` | `web/app/themes/voxpopuli/app/Providers/SeoServiceProvider.php` |
| `Seo Composer` | `voxpopuli-old/app/View/Composers/Seo.php` | `web/app/themes/voxpopuli/app/View/Composers/Seo.php` |

### Pasos de implementación

1. Copiar los 8 archivos del backup a sus destinos
2. Registrar `SeoServiceProvider` en `functions.php`:

```php
// functions.php — añadir al array de providers
Application::configure()
    ->withProviders([
        ThemeServiceProvider::class,
        App\Providers\SeoServiceProvider::class,  // ← añadir
    ])
    ->boot();
```

3. Verificar autoloading: `composer dump-autoload` en el contenedor
4. Ejecutar tests: `composer test` (deben pasar los 7 archivos de test SEO)
5. Verificar manual: cargar una página y revisar `<head>` para OG tags + JSON-LD

### Archivos de test asociados

| Test | Lo que verifica |
|------|----------------|
| `tests/Feature/Seo/SeoMetaTest.php` | 15 tests — value object, fallbacks, validación |
| `tests/Feature/Seo/JsonLdTest.php` | 7 tests — schemas Organization, WebSite, Article, BreadcrumbList |
| `tests/Feature/Seo/MetaRendererTest.php` | 14 tests — renderizado de meta tags |
| `tests/Feature/Seo/MigrationTest.php` | 11 tests — mapeo Yoast → voxpopuli |
| `tests/Feature/Seo/SitemapTest.php` | 14 tests — generación XML sitemap |
| `tests/Feature/Seo/TitleExpanderTest.php` | 10 tests — expansión de variables |
| `tests/Feature/Providers/SeoServiceProviderTest.php` | 3 tests — estructura del provider |

### Criterios de aceptación

- [ ] `composer test` pasa sin errores (mínimo 64 tests)
- [ ] `<head>` incluye OG tags en páginas single y homepage
- [ ] `<head>` incluye JSON-LD con `@graph` (Organization, WebSite, Article, BreadcrumbList)
- [ ] `GET /sitemap.xml` devuelve XML válido con URLs de posts publicados
- [ ] Admin muestra meta box "Vox Populi SEO" en pantallas de edición de posts/pages
- [ ] `wp voxpopuli migrate-seo --dry-run` funciona

---

## 3. Fase 2: Reconstituir dev tooling

### Problema

`filters.php` pasó de ~200 líneas a 16. Se perdieron:

- Bloqueo de enumeración de usuarios vía `?author=N`
- Restricción de REST API (`/wp/v2/users` anónimo)
- Dev URL rewriting dinámico (LAN IP, puertos)
- Content URL migration (reescritura de URLs de imágenes antiguas)
- Fallback a full-size cuando thumbnails no existen en disco

Además, la clase `Vite.php` personalizada (que reemplazaba `0.0.0.0` por el host real en URLs HMR para desarrollo mobile) fue eliminada.

### Archivos fuente (backup)

| Archivo | Ruta origen | Ruta destino |
|---------|-------------|--------------|
| filters.php completo | `voxpopuli-old/app/filters.php` | `web/app/themes/voxpopuli/app/filters.php` (fusionar con el actual) |
| Vite class | `voxpopuli-old/app/Vite.php` | `web/app/themes/voxpopuli/app/Vite.php` |
| setup.php (registro Vite) | `voxpopuli-old/app/setup.php` | `web/app/themes/voxpopuli/app/setup.php` (fusionar) |

### Pasos de implementación

1. **Extraer seguridad del filters.php antiguo** y añadirlo al actual sin perder el `excerpt_more` filter existente:

```php
// filters.php — añadir después del excerpt_more existente

/**
 * Block anonymous REST /wp/v2/users.
 */
add_filter('rest_authentication_errors', function ($result) {
    if (! is_user_logged_in() && ! empty($GLOBALS['wp']->query_vars['rest_route'])) {
        $route = $GLOBALS['wp']->query_vars['rest_route'];
        if (str_starts_with($route, '/wp/v2/users')) {
            return new WP_Error('rest_user_cannot_view', 'Forbidden', ['status' => 401]);
        }
    }
    return $result;
});

/**
 * Block ?author=N enumeration.
 */
add_action('parse_request', function ($wp) {
    if (isset($wp->query_vars['author']) && ! is_admin()) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
});
```

2. **Extraer dev URL rewriting** — condicionar a `WP_ENV === 'development'`:

```php
add_filter('home_url', function ($url) {
    if (defined('WP_ENV') && WP_ENV === 'development') {
        return rewrite_url_to_current_host($url);
    }
    return $url;
}, 10, 1);
// ... same for site_url, wp_get_attachment_url, wp_calculate_image_srcset
```

3. **Extraer content migration** — el `the_content` filter que reescribe URLs de imágenes antiguas y hace fallback a full-size.

4. **Reconstituir Vite class** — copiar `app/Vite.php` del backup. Luego en `setup.php`, registrar el override:

```php
// setup.php — dentro de after_setup_theme o init
add_filter('http_request_host_is_external', function ($internal, $host) {
    // Allow HMR host from VITE_DEV_HOST
    $devHost = parse_url(Vite::asset(''), PHP_URL_HOST);
    return $internal || $host === $devHost;
}, 10, 2);
```

> **Nota:** Verificar primero si Sage 11.x-dev ya maneja HMR dinámico. Si es así, la clase Vite personalizada no es necesaria.

### Criterios de aceptación

- [ ] `?author=1` redirige a homepage (no muestra perfil de usuario)
- [ ] `GET /wp/v2/users` sin autenticación devuelve 401
- [ ] URLs de medios en desarrollo usan el host correcto (no `0.0.0.0`)
- [ ] `excerpt_more` filter sigue funcionando
- [ ] Contenido con URLs de imágenes antiguas se reescribe correctamente

---

## 4. Fase 3: Eliminar código muerto

### Problema

Tres artefactos en el tema no hacen nada pero generan mantenimiento:

| Archivo | Problema |
|---------|----------|
| `resources/js/app.js` | Vacío — pero se carga en cada página vía `@vite` |
| `resources/js/editor.js` | Importa `@wordpress/dom-ready` con callback vacío |
| `resources/views/template-custom.blade.php` | Idéntico a `page.blade.php` |

Además, `setup.php` tiene un pipeline de 18 líneas para cargar `editor.js` y sus dependencias — orquestación para un no-op.

### Pasos de implementación

1. **Eliminar `app.js` de los inputs de Vite:**

```js
// vite.config.js — eliminar 'resources/js/app.js' de la lista
laravel({
    input: [
        'resources/css/app.css',
        // 'resources/js/app.js',  ← ELIMINAR
        'resources/css/editor.css',
        'resources/js/editor.js',
    ],
    // ...
}),
```

2. **Simplificar el pipeline de editor.js en `setup.php`:**

```php
// setup.php — reemplazar el bloque admin_head existente
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }
    // Cargar solo si editor.js tiene contenido real
    if (filesize(__DIR__ . '/../resources/js/editor.js') > 0) {
        echo Vite::withEntryPoints([
            'resources/js/editor.js',
        ])->toHtml();
    }
});
```

O directamente eliminar el bloque si se confirma que editor.js nunca tendrá contenido.

3. **Eliminar `template-custom.blade.php`:**

```bash
rm web/app/themes/voxpopuli/resources/views/template-custom.blade.php
```

### Criterios de aceptación

- [ ] `@vite()` en `layouts.app` genera una petición HTTP menos (solo CSS)
- [ ] El block editor funciona sin errores de consola en admin
- [ ] En el selector de plantillas en edición de páginas, "Custom Template" no aparece
- [ ] `npm run build` completa sin errores
- [ ] HMR en desarrollo sigue funcionando

---

## 5. Fase 4: Profundizar ThemeServiceProvider

### Problema

`ThemeServiceProvider` extiende `SageServiceProvider` pero solo llama a `parent::register()` y `parent::boot()`. No registra view composers, no vincula Blade components, no bindea servicios. Todo el wiring del tema está en `setup.php` y `filters.php` como closures globales.

### Diseño del módulo profundizado

```php
class ThemeServiceProvider extends SageServiceProvider
{
    public function register(): void
    {
        parent::register();

        // Bind services
        $this->app->singleton(\App\Seo\JsonLd::class);

        // Register Blade components
        $this->app->make('blade.compiler')->component('alert', \App\View\Components\Alert::class);
        // ... (los componentes Blade actuales son anónimos, solo si se crean clases PHP)
    }

    public function boot(): void
    {
        parent::boot();

        // Register view composers
        $this->loadViewComposers();

        // Register WordPress hooks
        $this->registerHooks();
    }

    private function loadViewComposers(): void
    {
        $view = $this->app->make('view');

        $view->composer('*', \App\View\Composers\App::class);
        $view->composer('partials.page-header', \App\View\Composers\Post::class);
        $view->composer('partials.content*', \App\View\Composers\Post::class);
        $view->composer('partials.comments', \App\View\Composers\Comments::class);
        $view->composer('front-page', \App\View\Composers\FrontPage::class);
    }

    private function registerHooks(): void
    {
        // Migrar hooks de setup.php y filters.php aquí
        // Ejemplo:
        add_filter('excerpt_more', function () {
            return sprintf(' &hellip; <a href="%s">%s</a>',
                get_permalink(), __('Continued', 'voxpopuli')
            );
        });
    }
}
```

### Pasos de implementación

1. Expandir `ThemeServiceProvider::register()` para bindear servicios del contenedor
2. Expandir `ThemeServiceProvider::boot()` para registrar view composers explícitamente
3. Migrar hooks de `setup.php` y `filters.php` a métodos del provider
4. Decidir si mantener `setup.php` y `filters.php` como archivos de inclusión o eliminarlos

> **Decisión abierta:** ¿Mantener `setup.php`/`filters.php` como archivos de inclusión (siguiendo el patrón Sage actual) o migrar todo al provider? Recomendación: migrar los hooks funcionales al provider y mantener solo configuraciones declarativas en `setup.php`.

### Criterios de aceptación

- [ ] `ThemeServiceProvider` registra view composers explícitamente
- [ ] Todas las vistas existentes reciben los mismos datos que antes (sin regresión)
- [ ] Los hooks de WordPress migrados desde setup.php/filters.php siguen funcionando
- [ ] `composer test` sigue pasando

---

## 6. Fase 5: Capa de repositorio de contenido

### Problema

Los composers de vista (`FrontPage`, y el eliminado `Index`) ejecutan `get_posts()` directamente, mezclando lógica de consulta con lógica de presentación. No hay caché por request ni transitoria. El `Hero` componente eliminado tenía caché transitoria con invalidez — esa capacidad se perdió.

### Diseño

```php
namespace App\Repositories;

class PostRepository
{
    /**
     * Find featured posts from 'destacadas' category with fallback.
     *
     * @return \WP_Post[]
     */
    public function findFeatured(int $count = 4): array
    {
        $cacheKey = "vp_featured_{$count}";
        $cached = wp_cache_get($cacheKey, 'voxpopuli');
        if ($cached !== false) {
            return $cached;
        }

        $featured = get_posts([
            'post_type' => 'post',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC',
            'category_name' => 'destacadas',
            'no_found_rows' => true,
        ]);

        // Fallback si no hay suficientes destacadas
        if (count($featured) < $count) {
            $excludeIds = wp_list_pluck($featured, 'ID');
            $fallbacks = get_posts([
                'post_type' => 'post',
                'posts_per_page' => $count - count($featured),
                'orderby' => 'date',
                'order' => 'DESC',
                'post__not_in' => $excludeIds,
                'no_found_rows' => true,
            ]);
            $featured = array_merge($featured, $fallbacks);
        }

        wp_cache_set($cacheKey, $featured, 'voxpopuli', HOUR_IN_SECONDS);

        return $featured;
    }

    /**
     * Find latest posts, excluding specific IDs.
     *
     * @param  int[]  $exclude
     * @return \WP_Post[]
     */
    public function findLatest(int $count = 5, array $exclude = []): array
    {
        return get_posts([
            'post_type' => 'post',
            'posts_per_page' => $count,
            'orderby' => 'date',
            'order' => 'DESC',
            'post__not_in' => $exclude,
            'no_found_rows' => true,
        ]);
    }

    /**
     * Find posts for a specific section/category.
     *
     * @return \WP_Post[]
     */
    public function findForSection(string $slug, int $count = 3, array $exclude = []): array
    {
        return get_posts([
            'category_name' => $slug,
            'posts_per_page' => $count,
            'post__not_in' => $exclude,
            'no_found_rows' => true,
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
        ]);
    }

    /**
     * Invalidate caches on post save.
     */
    public static function flushCaches(): void
    {
        wp_cache_delete_group('voxpopuli');
    }
}
```

### Pasos de implementación

1. Crear `app/Repositories/PostRepository.php`
2. Migrar queries desde `FrontPage` composer al repositorio
3. Inyectar el repositorio en `FrontPage::with()`
4. Agregar hook de invalidación de caché:

```php
// En ThemeServiceProvider o un action separado
add_action('save_post', [\App\Repositories\PostRepository::class, 'flushCaches']);
add_action('deleted_post', [\App\Repositories\PostRepository::class, 'flushCaches']);
```

5. Extraer la lógica de transformación `process()` del `FrontPage` a un método público o a un helper compartido (ver nota sobre Candidate #2).

> **Nota sobre Candidate #2 (excluido):** La lógica `process()` en `FrontPage` y la duplicación de filtrado "destacadas" en `content.blade.php` no se abordan en este PRD. Queda como deuda técnica identificada para resolución futura.

### Criterios de aceptación

- [ ] `FrontPage` composer usa `PostRepository` en lugar de `get_posts()` directo
- [ ] Caché transitoria reduce queries en página principal
- [ ] Al guardar/publicar un post, la caché se invalida
- [ ] Sin regresiones visuales en front-page

---

## 7. Fase 6: Normalizar text domain

### Problema

El tema mezcla `'sage'` (text domain del starter theme) y `'voxpopuli'` en distintas vistas. Las traducciones nunca aplican al 100%.

### Archivos a modificar

| Patrón | Búsqueda | Reemplazo |
|--------|----------|-----------|
| En todas las vistas Blade | `__(...', 'sage')` | `__(...', 'voxpopuli')` |
| En todas las vistas Blade | `_e(...', 'sage')` | `_e(...', 'voxpopuli')` |
| En todas las vistas Blade | `_n(...', 'sage')` | `_n(...', 'voxpopuli')` |
| En todas las vistas Blade | `_nx(...', 'sage')` | `_nx(...', 'voxpopuli')` |
| En composers PHP | `__(...', 'sage')` | `__(...', 'voxpopuli')` |

### Archivos a actualizar

- `style.css` — declarar `Text Domain: voxpopuli`
- Todos los archivos `.blade.php` en `resources/views/`
- Todos los archivos PHP en `app/View/Composers/`

### Pasos de implementación

1. Buscar todas las ocurrencias de `'sage'` como text domain en el theme:

```bash
grep -r "__(.*'sage'" web/app/themes/voxpopuli/resources/views/
grep -r "__(.*'sage'" web/app/themes/voxpopuli/app/
```

2. Reemplazar sistemáticamente con `'voxpopuli'`

3. Actualizar `style.css`:

```css
/*
Theme Name: VoxPopuli
Text Domain: voxpopuli  ← actualizar
*/
```

4. Generar archivo .pot:

```bash
docker exec -w /app/voxpopuli-app-1 wp i18n make-pot web/app/themes/voxpopuli/ languages/voxpopuli.pot
```

### Criterios de aceptación

- [ ] No hay ocurrencias de `__(...', 'sage')` en el theme
- [ ] `style.css` declara `Text Domain: voxpopuli`
- [ ] Archivo `.pot` generado con todas las cadenas

---

## 8. Fase 7: Templates de archivo faltantes

### Problema

No existen `archive.blade.php`, `category.blade.php`, `tag.blade.php` ni `author.blade.php`. WordPress cae a `index.blade.php` para todos estos casos. El `Index` composer que segmentaba por secciones editoriales fue eliminado.

### Diseño

```blade
{{-- archive.blade.php --}}
@extends('layouts.app')

@section('content')
  <div class="max-w-7xl mx-auto px-4">
    @include('partials.page-header')

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'voxpopuli') !!}
      </x-alert>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @while(have_posts()) @php(the_post())
        @include('partials.content')
      @endwhile
    </div>

    {!! get_the_posts_navigation() !!}
  </div>
@endsection
```

### Archivos a crear

| Archivo | Composer asociado | Propósito |
|---------|--------------------|-----------|
| `resources/views/archive.blade.php` | — | Lista genérica de archivos |
| `resources/views/category.blade.php` | `App\View\Composers\Category` | Metadatos de categoría + listado |
| `app/View/Composers/Category.php` | — | Provee nombre, descripción, conteo de categoría |

### Archivos a restaurar (opcional)

| Archivo | Ruta en backup | Función |
|---------|----------------|---------|
| `Index` composer | `voxpopuli-old/app/View/Composers/Index.php` | Segmentación por secciones editoriales |

> **Decisión abierta:** Restaurar el `Index` composer o mantener el listado plano. El `Index` composer proporcionaba una portada editorial rica (análisis, investigación, opinión, deportes, ahora) con deduplicación cross-sección. Recomendación: restaurarlo como base y adaptarlo al diseño actual.

### Criterios de aceptación

- [ ] `archive.blade.php` renderiza correctamente para archivos de fecha
- [ ] `category.blade.php` muestra nombre y descripción de categoría
- [ ] La jerarquía de templates de WordPress funciona correctamente
- [ ] Sin regresiones en `index.blade.php`

---

## 9. Dependencias y orden

### Grafo de dependencias

```
Fase 1 (SEO)       → sin dependencias → puede ir primero
Fase 2 (Tooling)   → sin dependencias → puede ir en paralelo con Fase 1
Fase 3 (Dead code) → sin dependencias → puede ir en paralelo
Fase 4 (Provider)  → depende de Fase 2 (los hooks migrados) → va después
Fase 5 (Repo)      → depende de Fase 4 (provider para inyección) → va después
Fase 6 (Text dom)  → sin dependencias → puede ir en paralelo con Fases 1-3
Fase 7 (Templates) → idealmente después de Fase 4-5 (para usar provider/repo)
```

### Orden recomendado para sesiones paralelas

```
Sesión A: Fase 1 (SEO)
Sesión B: Fase 2 (Tooling) + Fase 3 (Dead code) + Fase 6 (Text domain)
Sesión C: Fase 4 (Provider)
Sesión D: Fase 5 (Repository) + Fase 7 (Archive templates)
```

---

## 10. Criterios de aceptación globales

- [ ] **`composer test`** pasa sin errores (mínimo 71 tests: 64 SEO + 6 nuevo + 1 ExampleTest)
- [ ] **W3C HTML validation** sin errores nuevos
- [ ] **Lighthouse SEO** sin regresiones
- [ ] **OG tags y JSON-LD** presentes en todas las páginas públicas
- [ ] **`/sitemap.xml`** accesible y válido
- [ ] **Desarrollo mobile** HMR funciona desde LAN IP
- [ ] **Seguridad:** no se puede enumerar usuarios via `?author=N` o `/wp/v2/users`
- [ ] **Traducciones:** text domain unificado (`voxpopuli`)
- [ ] **Sin archivos muertos:** app.js, editor.js, template-custom eliminados
- [ ] **Caché:** front-page usa PostRepository con caché transitoria

---

## Apéndice A: Vocabulario arquitectónico

Ver skill `/codebase-design` para definiciones completas. Términos clave usados en este PRD:

| Término | Definición |
|---------|-----------|
| **Profundidad** | Un módulo es profundo cuando su interfaz es más simple que su implementación |
| **Seam** | Punto donde se puede alterar el comportamiento del programa sin editarlo |
| **Localidad** | Cuánto código hay que leer para entender un concepto |
| **Leverage** | Impacto de un cambio relativo a su tamaño |
| **Test de eliminación** | Borrar un módulo ¿concentra complejidad en otro lado o la elimina? |

## Apéndice B: Referencias

- Auditoría arquitectónica: `/tmp/architecture-review-20260714.html`
- Backup del código eliminado: `web/app/themes/voxpopuli-old/`
- Design System: `DESIGN.md`
- Brandbook: `BRANDBOOK.md`
