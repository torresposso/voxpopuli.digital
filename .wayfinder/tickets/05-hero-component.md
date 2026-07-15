# Prototype: Hero component (full-bleed)

**Labels**: `wayfinder:prototype`

**Blocked by**: `03-scaffold-vox-caribe`, `04-daisyui-vite-config`

---

## Resolution

✅ Hero component implementado en `resources/views/components/home-hero.blade.php`.

### Decisiones de diseño

| Aspecto | Decisión |
|---|---|
| **Altura** | `min-h-[70vh]` dinámico |
| **Overlay** | Gradiente `bg-gradient-to-t from-black/60 via-black/30 to-transparent` (abajo→arriba) |
| **Posición texto** | Abajo a la izquierda, dentro de contenedor `max-w-7xl` |
| **Headline** | `clamp(1.5rem, 4vw, 3rem)` Source Serif 4, `leading-tight`, max-w-3xl |
| **Kicker** | Categoría en uppercase, Plus Jakarta Sans, accent (Naranja Caribe) |
| **Byline** | Autor en texto blanco 70% opacidad, abajo del headline |
| **Enlace** | Toda el área del hero es clickeable vía `<a>` absoluto + aria-label |
| **Sin thumbnail** | Fondo base-200 como fallback visual |
| **Mobile** | Mismo layout vertical, padding reducido en laterales |

---

## Question

Diseñar e implementar el hero de la homepage: una historia destacada con imagen full-bleed y headline superpuesto.

Decisiones pendientes:
1. **Altura del hero**: ¿viewport-height completo (100vh), 80vh, o altura fija?
2. **Overlay**: ¿gradiente oscuro sobre la imagen para legibilidad del headline? ¿qué opacidad?
3. **Headline**: ¿tamaño en clamp()? ¿posición (centrado, abajo-izquierda, etc.)?
4. **Metadata**: ¿kicker (categoría) y byline visibles en el hero? ¿dónde?
5. **Imagen**: ¿aspect ratio? ¿la imagen se estira o cubre? ¿cómo se comporta en mobile?
6. **Enlace**: ¿toda el área del hero es clickeable o solo el headline?
7. **Sin imagen destacada**: ¿fallback? ¿texto grande sin imagen?
8. **Mobile**: ¿el hero mantiene imagen + headline superpuesto, o el headline pasa debajo de la imagen?

Resolver con un prototipo renderizable en Blade + Tailwind.
