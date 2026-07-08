/**
 * Reading progress bar for single posts.
 * Uses CSS scroll-driven animations when supported,
 * falls back to a scroll event listener with passive: true.
 *
 * @module scroll-progress
 */

export function init() {
  const bar = document.querySelector('[data-reading-progress]');
  if (!bar) return;

  // Check if CSS scroll-driven animations are supported
  const supportsCssScroll = CSS.supports('animation-timeline: scroll()');

  if (supportsCssScroll) {
    // CSS handles it — just mark as ready
    bar.dataset.progressMode = 'css';
    return;
  }

  // JS fallback: update scaleX transform on scroll
  bar.dataset.progressMode = 'js';

  function updateProgress() {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;

    if (docHeight <= 0) {
      bar.style.transform = 'scaleX(1)';
      return;
    }

    const progress = Math.min(scrollTop / docHeight, 1);
    bar.style.transform = 'scaleX(' + progress + ')';
  }

  // Initial calculation
  updateProgress();

  window.addEventListener('scroll', updateProgress, { passive: true });

  // Store for destroy
  bar._updateProgress = updateProgress;
}

export function destroy() {
  const bar = document.querySelector('[data-reading-progress]');
  if (!bar) return;

  if (bar.dataset.progressMode === 'js' && bar._updateProgress) {
    window.removeEventListener('scroll', bar._updateProgress);
  }

  bar.style.transform = '';
  delete bar.dataset.progressMode;
}
