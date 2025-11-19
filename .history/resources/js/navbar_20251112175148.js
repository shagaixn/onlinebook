(function () {
  const toggle = document.getElementById('nav-toggle');
  const menu = document.getElementById('nav-menu');
  const iconOpen = document.getElementById('nav-toggle-icon-open');
  const iconClose = document.getElementById('nav-toggle-icon-close');

  if (!toggle || !menu) return;

  const setExpanded = (expanded) => {
    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    menu.classList.toggle('hidden', !expanded);
    iconOpen?.classList.toggle('hidden', expanded);
    iconClose?.classList.toggle('hidden', !expanded);
    // Trap focus on open (basic): move focus to first link
    if (expanded) {
      const firstLink = menu.querySelector('a, button, input');
      firstLink && firstLink.focus();
    }
  };

  let expanded = false;

  toggle.addEventListener('click', () => {
    expanded = !expanded;
    setExpanded(expanded);
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && expanded) {
      expanded = false;
      setExpanded(expanded);
      toggle.focus();
    }
  });
})();