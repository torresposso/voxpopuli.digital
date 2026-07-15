# Task: Secondary sections (Archive, Podcasts, Latest, Popular, Newsletter)

**Labels**: `wayfinder:task`

**Blocked by**: `03-scaffold-vox-caribe`, `04-daisyui-vite-config`

---

## Resolution

✅ Los 5 componentes secundarios fueron creados.

| Componente | Archivo | Detalles |
|---|---|---|
| **Archive** | `home-archive.blade.php` | 3 posts con imagen 4:3, año como metadata, sección "Archivo" con link ver todo |
| **Podcasts** | `home-podcasts.blade.php` | Placeholder con 4 cuadros de audio + mensaje "próximamente", fondo base-200 |
| **Latest** | `home-latest.blade.php` | 4 posts, headline only, divididos por líneas base-300 |
| **Popular** | `home-popular.blade.php` | 5 posts numerados (01-05), headline only, divididos por líneas |
| **Newsletter** | `home-newsletter.blade.php` | Inline signup con input + botón (deshabilitados), diseño según Brandbook |
| **Latest+Popular** | `front-page.blade.php` | Side-by-side en grid 2/3 + 1/3 |

---

## Question

Diseñar e implementar las secciones secundarias de la homepage:

### Archive
- 2-3 historias del archivo histórico
- ¿Con imagen? ¿o solo headline + fecha + autor?
- ¿Con badge "Archivo" o similar?

### Podcasts (placeholder)
- Estructura preparada para cuando haya contenido
- ¿Tarjetas con arte de podcast? ¿qué datos mostrar (título, episodio, programa)?
- ¿Mostrar un mensaje "Próximamente" o simplemente oculto hasta que haya datos?

### Latest (timeline cronológico)
- Lista vertical de las últimas publicaciones
- ¿Solo headline + autor + fecha? ¿sin imagen?
- ¿Cuántos ítems mostrar?
- ¿Separador entre ítems?
- ¿Carga infinita o paginación?

### Popular (ranking numerado)
- Lista numerada del 1 al 10
- ¿Headline solamente o con más metadata?
- ¿Mecanismo: plugin (WP Popular Posts) o query personalizada?
- ¿Cachear resultados?

### Newsletter (placeholder)
- Bloque de suscripción inline
- Estilo visual según pautas del Brandbook (fondo base-200, tipografía sans, subordinado al contenido editorial)
- ¿Placeholder "Próximamente" o funcional pero sin backend conectado?

Todas las secciones deben tener título de sección + link "ver todo" (excepto newsletter).
