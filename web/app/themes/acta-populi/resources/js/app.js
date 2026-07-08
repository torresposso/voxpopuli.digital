/**
 * Acta Populi — JavaScript entry point.
 *
 * Imports all modules and exports init() / destroy() lifecycle functions.
 * Each module follows the same init() / destroy() pattern for clean setup/teardown.
 */

import { init as searchOverlayInit, destroy as searchOverlayDestroy } from './modules/search-overlay.js';
import { init as drawerInit, destroy as drawerDestroy } from './modules/drawer.js';
import { init as scrollProgressInit, destroy as scrollProgressDestroy } from './modules/scroll-progress.js';
import { init as videoFeatureInit, destroy as videoFeatureDestroy } from './modules/video-feature.js';
import { init as navTabsInit, destroy as navTabsDestroy } from './modules/nav-tabs.js';

const modules = [
  { name: 'search-overlay', init: searchOverlayInit, destroy: searchOverlayDestroy },
  { name: 'drawer', init: drawerInit, destroy: drawerDestroy },
  { name: 'scroll-progress', init: scrollProgressInit, destroy: scrollProgressDestroy },
  { name: 'video-feature', init: videoFeatureInit, destroy: videoFeatureDestroy },
  { name: 'nav-tabs', init: navTabsInit, destroy: navTabsDestroy },
];

/**
 * Initialize all modules. Call on DOMContentLoaded.
 */
export function init() {
  modules.forEach(function (mod) {
    try {
      mod.init();
    } catch (err) {
      console.warn('[acta-populi] ' + mod.name + ' init error:', err);
    }
  });
}

/**
 * Destroy all modules — clean up event listeners and DOM state.
 */
export function destroy() {
  modules.forEach(function (mod) {
    try {
      mod.destroy();
    } catch (err) {
      console.warn('[acta-populi] ' + mod.name + ' destroy error:', err);
    }
  });
}

// Auto-init on DOMContentLoaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
