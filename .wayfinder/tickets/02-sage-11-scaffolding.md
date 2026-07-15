# Research: Sage 11 — scaffolding process for a new theme

**Labels**: `wayfinder:research`

**Blocking**: `scaffold-vox-caribe`

---

## Question

¿Cuál es el proceso exacto para crear un nuevo theme Sage 11 desde cero en este proyecto?

Contexto:
- Sage 11 es un clon local en `web/app/themes/voxpopuli/` — no está en `composer.lock`
- El nuevo theme `vox-caribe` debe vivir en `web/app/themes/vox-caribe/`

Investigar:
1. ¿Se puede copiar el theme existente y renombrar? ¿O hay un mejor approach?
2. ¿Qué archivos son necesarios vs. opcionales? (composer.json, package.json, functions.php, style.css, vite.config.js, app/, resources/, etc.)
3. ¿El theme necesita registrarse en Bedrock de alguna forma?
4. ¿Cómo manejar el autoloading PSR-4 con `App\` apuntando a `web/app/themes/vox-caribe/app/`?
5. ¿El `roots/acorn` ya instalado en el proyecto raíz es suficiente, o necesita algo adicional?
6. ¿Qué providers son obligatorios vs. opcionales?
7. ¿Vite necesita configuración especial para un segundo theme?

Incluir comandos exactos y archivos mínimos viables.
