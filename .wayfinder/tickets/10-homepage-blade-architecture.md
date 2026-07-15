# Grilling: Homepage Blade template architecture

**Labels**: `wayfinder:grilling`

**Blocked by**: `03-scaffold-vox-caribe`, `04-daisyui-vite-config`

**Blocking**: `05-hero-component`, `06-story-grid-component`, `07-secondary-sections`, `08-footer-component`

---

## Resolution

✅ Decisiones de arquitectura tomadas:

1. **Template principal**: `resources/views/front-page.blade.php` (Sage 11 template hierarchy automático)
2. **Secciones**: Componentes Blade (`<x-home-hero />`, `<x-home-story-grid />`, `<x-home-archive />`, `<x-home-podcasts />`, `<x-home-latest />`, `<x-home-popular />`, `<x-home-newsletter />`)
3. **Datos**: View Composer central `App\View\Composers\FrontPage.php` que pasa todos los datos a los componentes
4. **Layout**: `layouts.app` con `data-theme="vox-caribe"` (el existente)

---

## Question

Definir la arquitectura de templates Blade para la homepage.

Decisiones:
1. **front-page.blade.php** — template principal que orquesta las secciones
2. ¿Cada sección es un **componente Blade** (x-hero, x-story-grid) o un **partial** incluido (@include)?
3. ¿Las secciones reciben datos desde un **View Composer** o se pasan desde el template?
4. ¿Layout: app.blade.php existente o uno nuevo para vox-caribe?

Propuesta:
- `resources/views/template-homepage.blade.php` (si no usamos front-page.php)
- O `resources/views/front-page.blade.php`
- Cada sección como componente: `<x-home-hero />`, `<x-story-grid />`, `<x-latest />`, etc.
- Un solo View Composer `App\View\Composers\FrontPage.php` que pasa todos los datos
- El layout `app.blade.php` se mantiene con `data-theme="vox-caribe"` (nuevo nombre del tema daisyUI)

¿Estás de acuerdo con esta arquitectura?
