# Prototype: Footer component

**Labels**: `wayfinder:prototype`

**Blocked by**: `03-scaffold-vox-caribe`, `04-daisyui-vite-config`

---

## Resolution

✅ Footer implementado en `resources/views/sections/footer.blade.php`.

| Aspecto | Estado |
|---|---|
| **Fondo** | Azul Caribe (`bg-primary`) |
| **Wordmark** | `Vox` en blanco (`text-base-100`), `Populi` en accent |
| **Columnas** | 4: Secciones (wp_nav_menu), El Medio, Síguenos, Legal |
| **Responsive** | 2 cols mobile → 4 cols `md:` |
| **Copyright** | `© 2026 VoxPopuli Digital. Todos los derechos reservados.` |
| **Borde superior** | `border-t border-primary-content/20` entre columnas y copyright |

Pendiente: actualizar URLs de redes sociales y páginas institucionales cuando estén creadas.

---

## Question

Diseñar e implementar el footer rediseñado del theme vox-caribe.

Ya decidido:
- Fondo Azul Caribe (color primary)
- Wordmark VoxPopuli con mirror rule (Vox en blanco, Populi en naranja sobre fondo azul)
- Columnas de links
- Copyright
- Redes sociales

Decisiones pendientes:
1. **Columnas**: ¿cuántas? ¿qué contenido va en cada una?
   - Sugerencia: Secciones editoriales | El Medio (Nosotros, Contacto, Equipo) | Síguenos (redes sociales) | Legal (Términos, Privacidad, Licencia)
   - ¿Apruebas esta estructura?
2. **Wordmark**: ¿centrado arriba o en columna izquierda?
3. **Newsletter**: ¿incluir signup en el footer además de la sección dedicada?
4. **Copyright**: formato del texto (ej. "© 2026 VoxPopuli Digital. Todos los derechos reservados.")
5. **Redes sociales**: ¿íconos o texto? ¿cuáles?
6. **Mobile**: ¿las columnas se apilan? ¿en qué orden?
7. **Borde superior**: ¿línea divisoria sutil (base-200/base-300) o sin borde, solo fondo azul comenzando abruptamente?

Resolver con prototipo renderizable en Blade + Tailwind.
