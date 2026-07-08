/**
 * Search overlay: opens a centered search input on button click.
 * Closes on Escape, backdrop click, or form submission.
 * Returns focus to the trigger button on close.
 *
 * @module search-overlay
 */

export function init() {
  const toggle = document.querySelector('[data-search-toggle]');
  if (!toggle) return;

  let overlay = document.getElementById('search-overlay');

  if (!overlay) {
    overlay = document.createElement('div');
    overlay.id = 'search-overlay';
    overlay.className = 'search-overlay';
    overlay.innerHTML =
      '<div class="search-overlay-backdrop" data-search-backdrop></div>' +
      '<div class="search-overlay-content">' +
        '<form role="search" method="get" action="/">' +
          '<label for="search-overlay-input" class="sr-only">Buscar</label>' +
          '<input id="search-overlay-input" type="search" name="s" class="search-overlay-input" placeholder="Buscar artículos…" autocomplete="off" />' +
        '</form>' +
        '<button class="search-overlay-close" data-search-close aria-label="Cerrar búsqueda">' +
          '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">' +
            '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />' +
          '</svg>' +
        '</button>' +
      '</div>';
    document.body.appendChild(overlay);
  }

  const input = overlay.querySelector('#search-overlay-input');
  const backdrop = overlay.querySelector('[data-search-backdrop]');
  const closeBtn = overlay.querySelector('[data-search-close]');

  function open() {
    overlay.classList.add('is-open');
    input.focus();
  }

  function close() {
    overlay.classList.remove('is-open');
    toggle.focus();
  }

  toggle.addEventListener('click', open);
  backdrop.addEventListener('click', close);
  closeBtn.addEventListener('click', close);

  function onEscape(e) {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
      close();
    }
  }
  document.addEventListener('keydown', onEscape);

  // Store for destroy
  overlay._toggle = toggle;
  overlay._open = open;
  overlay._close = close;
  overlay._onEscape = onEscape;
  overlay._backdrop = backdrop;
  overlay._closeBtn = closeBtn;
}

export function destroy() {
  const overlay = document.getElementById('search-overlay');
  if (!overlay) return;

  overlay._toggle.removeEventListener('click', overlay._open);
  overlay._backdrop.removeEventListener('click', overlay._close);
  overlay._closeBtn.removeEventListener('click', overlay._close);
  document.removeEventListener('keydown', overlay._onEscape);

  overlay.remove();
}
