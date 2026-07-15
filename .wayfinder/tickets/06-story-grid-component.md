# Prototype: Story Grid component (3-column)

**Labels**: `wayfinder:prototype`

**Blocked by**: `03-scaffold-vox-caribe`, `04-daisyui-vite-config`

---

## Resolution

✅ Story Grid component implementado en `resources/views/components/home-story-grid.blade.php`.

### Decisiones de diseño

| Aspecto | Decisión |
|---|---|
| **Layout** | 4 columnas × 2 filas (8 posts) |
| **Gap** | 32px (`gap-8`) |
| **Aspect ratio imágenes** | 4:3 forzado via `aspect-[4/3] object-cover` |
| **Crédito imagen** | No |
| **Kicker** | Sí, categoría en uppercase, accent, arriba del headline |
| **Headline** | `text-xl` (1.25rem), Source Serif 4 bold, `line-clamp-3` |
| **Deck / Bajada** | No |
| **Byline / Fecha** | No |
| **Hover** | Headline → accent + imagen → `scale-105` con 500ms ease-out |
| **Separador filas** | `border-t border-base-300` + `my-10` entre filas |
| **Responsive** | 1 col mobile → 2 cols tablet → 4 cols desktop (Tailwind defaults) |
| **Sin thumbnail** | No aplica (query garantiza posts con thumbnail) |

---

## Question

Diseñar e implementar el story grid de 3 columnas que va debajo del hero.

Decisiones pendientes:
1. **Gap**: ¿cuánto espacio entre columnas? (The Atlantic usa gap grande, ~2rem+)
2. **Imagen**: ¿aspect ratio fijo para todas las imágenes? (16:9, 4:3, 3:2, etc.)
3. **Crédito de imagen**: ¿visible arriba de la imagen como The Atlantic?
4. **Kicker (categoría)**: ¿visible? ¿dónde — arriba de la imagen o arriba del headline?
5. **Headline**: ¿tamaño consistente o varía por posición? ¿truncamiento (line-clamp)?
6. **Deck/bajada**: ¿incluir debajo del headline? ¿siempre o solo cuando existe?
7. **Byline**: ¿visible siempre? ¿formato "Nombre Apellido" sin "Por"?
8. **Fecha**: ¿visible?
9. **Hover state**: ¿el headline cambia de color? ¿la imagen tiene efecto sutil?
10. **Casos borde**: ¿qué pasa si hay menos de 3 historias? ¿si no hay imagen disponible?
11. **Responsive**: ¿cómo colapsa a 2 columnas tablet y 1 columna mobile?
12. **Separación entre filas**: ¿línea horizontal sutil o solo whitespace?

Resolver con prototipo renderizable en Blade + Tailwind. Manejar el caso "sin imagen" con un placeholder text-only.
