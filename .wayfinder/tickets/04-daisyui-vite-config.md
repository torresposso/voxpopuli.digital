# Task: daisyUI theme configuration & Vite pipeline

**Labels**: `wayfinder:task`

**Blocked by**: `03-scaffold-vox-caribe`

**Blocking**: `05-homepage-blade-templates`, `06-hero-component`, `07-story-grid-component`, `08-secondary-sections`, `09-footer-component`

---

## Resolution

✅ Configuración completada durante el scaffold (ticket 03):

1. **daisyUI theme**: `vox-caribe` definido en `resources/css/app.css` con:
   - Colores VoxPopuli (Azul Caribe primary, Naranja Caribe accent, blancos base-100/200/300)
   - `--radius-selector: 0px`, `--radius-field: 0px`, `--radius-box: 0px`
   - `--border: 0px`, `--depth: 0`

2. **Tailwind @theme**:
   - `--font-display: "Source Serif 4", serif`
   - `--font-serif: "Source Serif 4", serif`
   - `--font-sans: "Plus Jakarta Sans", system-ui, sans-serif`

3. **Vite**: `vite.config.js` configurado con:
   - `base: '/app/themes/vox-caribe/public/build/'`
   - Puerto: 5175 (no conflict con voxpopuli en 5174)
   - Plugins: tailwindcss, laravel-vite-plugin, @roots/vite-plugin
   - Input: app.css, editor.css, editor.js

4. **Google Fonts**: Source Serif 4 + Plus Jakarta Sans cargados en `app.blade.php`

---

## Question

Configurar el tema daisyUI `vox-caribe` y el pipeline de Vite para el nuevo theme.

1. **daisyUI theme**: Definir `@plugin "daisyui/theme"` con:
   - name: `vox-caribe`
   - Tokens de color: copiar los existentes de VoxPopuli (Azul Caribe, Naranja Caribe, blancos, neutros)
   - Tipografía: Source Serif 4 como variable de fuente (pero mantener las variables actuales para no romper componentes)
   - Border-radius: 0 (cards sin bordes redondeados)
   - Border: 0 (sin bordes)
   - Depth: 0 (sin sombras)

2. **Tailwind theme**: Configurar `@theme` con:
   - `--font-display`: "Source Serif 4", serif
   - `--font-serif`: "Source Serif 4", serif  
   - `--font-sans`: "Plus Jakarta Sans", sans-serif

3. **Vite**: Configurar vite.config.js para el nuevo theme con:
   - base correcta para la ruta del theme (`/app/themes/vox-caribe/public/build/`)
   - Plugins: tailwindcss, laravel-vite-plugin, @roots/vite-plugin con wordpressThemeJson
   - Input: resources/css/app.css, resources/css/editor.css
   - HMR config para el stack Docker existente

4. **Carga de Google Fonts**: Agregar `@import` de Source Serif 4 en app.css

El resultado debe ser un theme que compile CSS con Tailwind v4 + daisyUI 5 y sirva assets via Vite.
