// Simple authors grid animations: appear-on-scroll, 3D tilt, magnetic follow
function clamp(n, min, max) { return Math.max(min, Math.min(max, n)); }

export function initAuthorsAnimations() {
  const cards = Array.from(document.querySelectorAll('[data-author-card]'));
  if (!cards.length) return;

  // Appear on scroll (stagger)
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      const el = entry.target;
      if (entry.isIntersecting) {
        // stagger by index within grid
        const idx = cards.indexOf(el);
        el.style.transitionDelay = `${(idx % 6) * 60}ms`;
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
        io.unobserve(el);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -5% 0px' });
  cards.forEach((c) => io.observe(c));

  // Tilt + magnet
  cards.forEach((card) => {
    const tiltMax = parseFloat(card.dataset.tilt || '8');      // deg
    const magnetMax = parseFloat(card.dataset.magnet || '10'); // px
    let rafId = null;
    let tx = 0, ty = 0, rx = 0, ry = 0;

    const onMove = (e) => {
      const rect = card.getBoundingClientRect();
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = e.clientX - cx;
      const dy = e.clientY - cy;

      // percentage inside card
      const px = clamp(dx / (rect.width / 2), -1, 1);
      const py = clamp(dy / (rect.height / 2), -1, 1);

      ry = -px * tiltMax; // rotateY
      rx = py * tiltMax;  // rotateX
      tx = clamp(px * magnetMax, -magnetMax, magnetMax);
      ty = clamp(py * magnetMax, -magnetMax, magnetMax);

      if (!rafId) {
        rafId = requestAnimationFrame(apply);
      }
    };

    const apply = () => {
      rafId = null;
      card.style.transform = `
        translate3d(${tx}px, ${ty}px, 0)
        rotateX(${rx}deg)
        rotateY(${ry}deg)
        scale3d(1.02, 1.02, 1)
      `;
    };

    const onLeave = () => {
      card.style.transition = 'transform 200ms ease-out';
      card.style.transform = 'translate3d(0,0,0) rotateX(0) rotateY(0) scale3d(1,1,1)';
      setTimeout(() => { card.style.transition = ''; }, 220);
    };

    card.addEventListener('mousemove', onMove, { passive: true });
    card.addEventListener('mouseleave', onLeave);
  });
}

// Auto-init if Vite bundles this and DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAuthorsAnimations, { once: true });
} else {
  initAuthorsAnimations();
}