# Task: Scaffold vox-caribe theme skeleton

**Labels**: `wayfinder:task`

**Blocked by**: `02-sage-11-scaffolding`

**Blocking**: `04-daisyui-vite-config`, `05-homepage-blade-templates`, `06-hero-component`, `07-story-grid-component`, `08-secondary-sections`, `09-footer-component`, `10-wordpress-queries`

---

## Resolution

✅ Theme skeleton created at `web/app/themes/vox-caribe/` with 22 archivos:

| Archivo | Propósito |
|---|---|
| `style.css` | Cabecera del theme (Theme Name: Vox Caribe, text-domain: vox-caribe) |
| `composer.json` | Dependencias PHP (roots/acorn ^6.0) + autoload PSR-4 App\ → app/ |
| `package.json` | Vite 8 + Tailwind 4 + daisyUI 5 + @roots/vite-plugin 2 |
| `vite.config.js` | Base `/app/themes/vox-caribe/public/build/`, puerto **5175** |
| `functions.php` | Acorn boot con `ThemeServiceProvider` |
| `index.php` | Entry point Blade de Sage |
| `theme.json` | Settings del block editor |
| `.gitignore` | Ignorar vendor, node_modules, public/* |
| `app/Providers/ThemeServiceProvider.php` | Extiende `SageServiceProvider`, sin composers aún |
| `app/setup.php` | after_setup_theme, menús, sidebars, block editor assets |
| `app/filters.php` | excerpt_more, REST API protection, author scan block |
| `resources/views/layouts/app.blade.php` | Layout base con Source Serif 4 + Plus Jakarta Sans, `data-theme="vox-caribe"` |
| `resources/views/index.blade.php` | Template principal de fallback (grilla de posts) |
| `resources/views/sections/header.blade.php` | Header con wordmark + drawer toggle (placeholder) + nav desktop |
| `resources/views/sections/footer.blade.php` | Footer Azul Caribe con wordmark + 4 columnas + copyright |
| `resources/css/app.css` | daisyUI theme `vox-caribe` con colores VoxPopuli, sin border-radius, sin bordes |
| `resources/css/editor.css` | Editor styles (Tailwind) |
| `resources/js/app.js` | Entry point JS |
| `resources/js/editor.js` | Editor JS (domReady) |
| `public/.gitkeep` | Para que Vite pueda escribir en public/build/ |

**Pendiente**: Falta correr `composer install` y `npm install` dentro del theme para que sea funcional (requiere acceso al contenedor).

---

## Question

Crear la estructura mínima del theme `vox-caribe` en `web/app/themes/vox-caribe/` siguiendo el proceso definido en `02-sage-11-scaffolding`.

Incluir:
- `style.css` con cabecera de theme (Theme Name: Vox Caribe, etc.)
- `composer.json` con las dependencias mínimas (roots/acorn)
- `package.json` con dependencias (vite, tailwindcss, daisyui, @roots/vite-plugin, etc.)
- `functions.php` que bootea Acorn con los providers necesarios
- `app/Providers/ThemeServiceProvider.php` básico
- `app/setup.php` y `app/filters.php` mínimos
- `vite.config.js` base
- `resources/views/layouts/app.blade.php` base
- `resources/css/app.css` base con Tailwind + daisyUI
- `index.php` o la entrada que Sage 11 necesite
- Cualquier otro archivo necesario para que el theme sea reconocido por WordPress

No incluir lógica de componentes de homepage — eso se hace en tickets posteriores. Solo el esqueleto funcional.
