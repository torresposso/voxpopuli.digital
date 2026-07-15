# Research: The Atlantic — responsive behavior & component deep-dive

**Labels**: `wayfinder:research`

**Blocking**: `story-grid-component`, `hero-component`, `secondary-sections`, `responsive-review`

---

## Question

¿Cómo maneja theatlantic.com el responsive design en cada sección de su homepage? Específicamente:

1. **Hero**: ¿cómo se comporta el full-bleed hero en mobile? ¿La imagen se recorta, se achica, cambia de aspecto? ¿El headline se mueve debajo de la imagen o se mantiene superpuesto?
2. **Story Grid**: ¿cómo colapsa el grid de 3 columnas a tablet y mobile? ¿A qué breakpoints? ¿Las imágenes mantienen aspect ratio?
3. **Latest / Popular**: ¿cómo se ve cada lista en mobile? ¿cambia el layout?
4. **Newsletter signup**: ¿inline o modal? ¿qué estructura tiene?
5. **Navegación**: ¿cómo se comporta el header en mobile?
6. **Imágenes**: ¿usan lazy loading nativo? ¿qué atributos? ¿qué tamaños de srcset?
7. **Tipografía responsive**: ¿cómo escalan los headlines? ¿usan clamp()?

Extraer patrones concretos de CSS y HTML que podamos replicar en Tailwind.
