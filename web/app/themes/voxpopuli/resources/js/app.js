// Pure HTML/CSS DaisyUI Drawer is used. No custom JavaScript is required for layout toggle.

// Progressive enhancement for keyboard accessibility (WCAG AA):
// Enable Space/Enter triggers on label buttons that toggle the main drawer checkbox.
document.addEventListener('keydown', (e) => {
  if ((e.key === ' ' || e.key === 'Enter') && e.target.matches('label[for="main-drawer"]')) {
    e.preventDefault();
    const checkbox = document.getElementById(e.target.getAttribute('for'));
    if (checkbox) {
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }
});
