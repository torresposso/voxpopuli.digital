---
target: seccion investigacion frontpage
total_score: 24
p0_count: 0
p1_count: 3
p2_count: 2
timestamp: 2026-07-15T13-22-52Z
slug: views-sections-front-page-investigacion-blade-php
---
## Anti-Patterns Verdict

**LLM assessment**: No es AI slop obvio, pero es genérico por omisión. La sección tiene los huesos correctos (cards limpias, stagger animation, placeholder honesto) pero no comunica que esto es Periodismo de Investigación — el contenido más importante del medio.

**Deterministic scan**: 4 findings, todos design-system-font-size (advisory): heading mobile (0.8rem), heading desktop (1.2rem), "Ver todas" (0.75rem), placeholder (0.625rem). Todos fuera del type ramp de DESIGN.md.

## Design Health Score: 24/40 (Acceptable)

| # | Heuristic | Score | Key Issue |
|---|-----------|-------|-----------|
| 1 | Visibility of System Status | 3 | Sin skeleton pero stagger animation da feedback |
| 2 | Match System / Real World | 3 | Sin metadatos el usuario adivina vigencia |
| 3 | User Control and Freedom | 3 | Links claros |
| 4 | Consistency and Standards | 2 | Heading es la mitad del tamaño de otras secciones |
| 5 | Error Prevention | 3 | Placeholder y empty state |
| 6 | Recognition Rather Than Recall | 2 | Sin fecha/autor/excerpt |
| 7 | Flexibility and Efficiency | 2 | Solo "Ver todas" |
| 8 | Aesthetic and Minimalist Design | 2 | Borde invisible, cards sin contexto |
| 9 | Error Recovery | 3 | Sin operaciones de riesgo |
| 10 | Help and Documentation | 1 | Sin tooltips ni contexto |

## Cognitive Load: 3 failures (moderate)
- Grouping visual: borde invisible rompe conexión heading→grid
- Visual hierarchy: heading a 0.8rem compite con cards
- Working memory: sin metadata, usuario debe recordar títulos

## Overall Impression
Sub-entrega en lo más importante: comunicar el peso editorial. El detector confirmó 4 violaciones al type ramp. Cards sin metadata que la audiencia necesita. Inconsistencia con secciones hermanas.

## What's Working
1. Stagger animation con prefers-reduced-motion guard
2. Card structure alineada con DS (border-2, bg-base-200, sin sombra en reposo)
3. Tratamiento de imagen documental (aspect-video, object-cover, hover scale)

## Priority Issues

### P1 — Heading subdimensional + fuera del type ramp
- **Qué**: h2 usa text-[0.8rem] md:text-[1.2rem] — fuera del type ramp. Borde invisible (base-300 sobre base-300).
- **Fix**: Subir a text-xl md:text-2xl. Cambiar borde a border-base-content.
- **Suggested**: $impeccable layout

### P1 — Cards sin metadata
- **Qué**: Solo título + imagen. Sin badge, excerpt, autor, fecha. Territorios SÍ tiene.
- **Fix**: Agregar badge + excerpt (line-clamp-2) + autor + fecha.
- **Suggested**: $impeccable harden

### P1 — Sección encajonada inconsistentemente
- **Qué**: bg-base-300 p-4 inusual. Las demás secciones flotan sobre blanco.
- **Fix**: Eliminar bg o expandir a todas.
- **Suggested**: $impeccable layout

### P2 — Hover shadow viola Flat-At-Rest
- **Qué**: border-2 + box-shadow con 20px blur en hover.
- **Fix**: hover:border-accent como Territorios, o reducir blur a ≤8px.
- **Suggested**: $impeccable polish

### P2 — Dead CSS: group-hover/flecha sin group/flecha
- **Qué**: Animación de flecha nunca se ejecuta.
- **Fix**: Agregar group/flecha al anchor.
- **Suggested**: $impeccable polish

### P3 — Placeholder subdimensionado
- **Qué**: text-[0.625rem] — 10px, ilegible en mobile.
- **Fix**: Icono SVG + text-xs mínimo.
- **Suggested**: $impeccable polish

## Persona Red Flags

**Jordan (First-Timer)**: Sin badge/excerpt no sabe qué тип de artículo. Heading 0.8rem no comunica importancia.

**Riley (Stress Tester)**: Empty state sin CTA. Placeholder imagen sin fallback visual.

**Casey (Mobile)**: Sin fecha no evalúa vigencia. Heading pequeño difícil en movimiento.

**Carmen (proyecto)**: Lectora 45, WhatsApp, 3G. Sin autor = sin confianza. Sin fecha = no sabe si es reciente. Placeholder 10px en 3G = "no cargó".

## Minor Observations
1. Falta aria-labelledby (Territorios y Esenciales lo tienen)
2. style inline de animaciones — mejor en app.css
3. $post->alt sin fallback
4. Sin width/height en img — posible CLS

## Questions
1. Si esta es la sección que define al medio, ¿por qué se ve como la menos informativa?
2. ¿El fondo base-300 fue deliberado o accidente?
3. ¿Imagen 16:9 o densidad de información?
