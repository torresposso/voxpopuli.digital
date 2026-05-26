# Design Brief — Main Hero Redesign

## Feature Summary

Hero principal de Vox Populi Digital. La entrada al sitio — lo primero que ve un lector al llegar. Debe comunicar autoridad editorial, identidad caribeña y urgencia informativa, todo en una composición que funcione en móvil (prioritario) y desktop.

## Primary User Action

Encontrar la noticia del día y engancharse a leer. El hero debe hacer evidente qué contenido es el más importante ahora y cuál es el segundo/tercer orden de importancia, todo sin requerir scroll.

## Design Direction

**Color strategy:** **Drenched** — override del Restrained del proyecto. El hero es la entrada y se lo merece. Fondo oscuro (Primary `#332d76` o Base-Content `#080c0f`) como canvas para que las imágenes y el contenido brillen.

**Scene sentence:** Un lector caribeño abre el sitio al despertar, en su móvil, con café en mano o en el bus rumbo al trabajo — el hero lo recibe con una imagen que domina la pantalla, tipografía de autoridad y color que afirma "esto es Vox Populi, esto es Caribe, esto es serio."

**Anchor references:** New York Times (hero inmersivo, imagen dominante, jerarquía editorial impecable) + Revista 5W (ritmo pausado, fotografía que cuenta la historia, tipografía con aire).

**Typography:** Playfair Display para titulares (autoridad, empaque editorial) sobre body en Literata. Plus Jakarta Sans para metadata y etiquetas de categoría.

## Scope

- **Fidelity:** Production-ready
- **Breadth:** Una sola superficie — el hero de homepage
- **Interactivity:** Hover reveals en imágenes (grayscale → color), links a artículos
- **Time intent:** Polish hasta que esté en producción

## Layout Strategy

- **3–4 posts con jerarquía igualitaria, no un feature + sidebar.** En desktop: grilla tipo magazine (asimétrica, 2 o 3 columnas según el corte). En móvil: carrusel swipeable horizontal con dots indicadores.
- **Imagen como protagonista.** Cada post es una card/panel con imagen de fondo a sangre (sin padding interno), overlay oscuro, título superpuesto.
- **Sin container rígido** — el hero va al borde, de borde a borde.
- **Categoría label** arriba del título (Plus Jakarta Sans 800, uppercase, wide tracking) como kicker.
- **Badge "Destacado"** solo en el post editorialmente más importante (no en todos).
- **Wordmark posicional** — el logo Vox Populi en la esquina superior izquierda del hero o integrado en el header transparente sobre el hero.

## Key States

- **Default (4 posts):** Hero completo con 4 artículos destacados.
- **1–2 posts (bajo contenido):** Hero se adapta — los posts disponibles ocupan todo el espacio disponible. Mantener la misma densidad visual.
- **0 posts (sin contenido):** Hero reducido a wordmark + tagline + call-to-action editorial (breve manifiesto o invitación a leer).
- **Loading:** Esqueleto sutil — placeholders con shimmer en los paneles del hero.

## Interaction Model

- **Hover (desktop):** Imagen grayscale → color, overlay se aclara ligeramente, título mantiene legibilidad.
- **Click:** Navega al artículo correspondiente.
- **Mobile swipe:** Si se opta por carrusel, dots indicadores de posición.
- **Scroll:** El hero no es sticky — el contenido de abajo empuja naturalmente.

## Content Requirements

- **Por cada post destacado:** Imagen de fondo (featured image), categoría (kicker), título, excerpt breve (2 líneas máx), fecha, autor (opcional).
- **Realistic ranges:** 0–6 posts destacados (ideal 3–4).
- **Wordmark** integrado en la composición (puede ser inline en el hero, no necesariamente en el header separado).
- **Tagline funcional** ("Periodismo independiente desde el Caribe colombiano") visible en desktop, oculto u opcional en móvil.

## Recommended References

- `reference/brand.md` — ya cargado. Drenched strategy, font selection procedure.
- `reference/craft.md` — para implementación post-aprobación.
- `reference/adapt.md` — responsive behavior entre mobile carrusel y desktop grid.

## Open Questions

*(ninguna — resueltas)*
