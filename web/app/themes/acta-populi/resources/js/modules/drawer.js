/**
 * Mobile drawer management for DaisyUI drawer component.
 * Handles focus trapping, body scroll lock, Escape close,
 * and respects prefers-reduced-motion.
 *
 * @module drawer
 */

function getFocusableElements(container) {
  return container.querySelectorAll(
    'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
  );
}

export function init() {
  const drawerCheckbox = document.getElementById('main-drawer');
  const drawerSide = document.querySelector('.drawer-side');
  if (!drawerCheckbox || !drawerSide) return;

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  let activeElementBeforeOpen = null;

  function onDrawerOpen() {
    if (!drawerCheckbox.checked) return;

    activeElementBeforeOpen = document.activeElement;

    // Body scroll lock
    document.body.style.overflow = 'hidden';

    if (prefersReducedMotion) {
      drawerSide.style.scrollBehavior = 'auto';
    }

    // Focus first focusable element
    const focusable = getFocusableElements(drawerSide);
    if (focusable.length > 0) {
      focusable[0].focus();
    }
  }

  function onDrawerClose() {
    document.body.style.overflow = '';

    // Return focus to hamburger
    if (activeElementBeforeOpen) {
      activeElementBeforeOpen.focus();
      activeElementBeforeOpen = null;
    }
  }

  // Track open/close via change event on the checkbox
  drawerCheckbox.addEventListener('change', function onChange() {
    if (drawerCheckbox.checked) {
      onDrawerOpen();
    } else {
      onDrawerClose();
    }
  });

  // Escape key closes drawer
  document.addEventListener('keydown', function onEscape(e) {
    if (e.key === 'Escape' && drawerCheckbox.checked) {
      drawerCheckbox.checked = false;
      onDrawerClose();
    }
  });

  // Focus trap: Tab cycles within drawer elements
  drawerSide.addEventListener('keydown', function onTab(e) {
    if (e.key !== 'Tab' || !drawerCheckbox.checked) return;

    const focusable = getFocusableElements(drawerSide);
    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (e.shiftKey) {
      if (document.activeElement === first) {
        e.preventDefault();
        last.focus();
      }
    } else {
      if (document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  // Close drawer when a nav link is clicked
  drawerSide.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', function onNavLinkClick() {
      drawerCheckbox.checked = false;
      onDrawerClose();
    });
  });

  // Store refs for destroy
  drawerCheckbox._onChange = onChange;
  drawerSide._onEscape = onEscape;
  drawerSide._onTab = onTab;
}

export function destroy() {
  const drawerCheckbox = document.getElementById('main-drawer');
  const drawerSide = document.querySelector('.drawer-side');
  if (!drawerCheckbox || !drawerSide) return;

  drawerCheckbox.removeEventListener('change', drawerCheckbox._onChange);
  document.removeEventListener('keydown', drawerSide._onEscape);
  drawerSide.removeEventListener('keydown', drawerSide._onTab);

  document.body.style.overflow = '';
}
