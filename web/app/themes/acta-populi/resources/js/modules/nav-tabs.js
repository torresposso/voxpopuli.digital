/**
 * Nav tabs: reads window.location.pathname and sets the active class
 * on the matching nav-tab link. Handles /category/{slug}/ URL patterns.
 *
 * @module nav-tabs
 */

export function init() {
  const tabs = document.querySelectorAll('.nav-tab');
  if (!tabs.length) return;

  const path = window.location.pathname;
  const matchCategory = path.match(/^\/category\/([^/]+)/);
  const categorySlug = matchCategory ? matchCategory[1] : null;

  tabs.forEach(function (tab) {
    var href = tab.getAttribute('href');

    // Direct path match
    if (href === path) {
      tab.classList.add('nav-tab-active');
      return;
    }

    // Category slug match
    if (categorySlug && href && href.indexOf(categorySlug) !== -1) {
      tab.classList.add('nav-tab-active');
    }
  });
}

export function destroy() {
  const tabs = document.querySelectorAll('.nav-tab');
  tabs.forEach(function (tab) {
    tab.classList.remove('nav-tab-active');
  });
}
