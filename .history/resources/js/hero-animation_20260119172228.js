// Hero хэсгийн анимейшнууд
const HeroAnimations = (() => {
  const state = {
    io: null,
    particles: [],
    ctx: null,
    canvas: null,
    raf: null,
    dark: false,
  };

  function isDark() {
    return document.documentElement.classList.contains('dark');
  }

  // Үсэг болгож wrap хийх
  function splitToChars(el) {
    const original = el.innerHTML;
    const textOnly = el.textContent;
    const frag = document.createDocumentFragment();
    el.innerHTML = '';
    for (const char of textOnly) {
      const span = document.createElement('span');
      span.className = 'char';
      span.textContent = char;
      frag.appendChild(span);
    }
    el.appendChild(frag);
    return () => { el.innerHTML = original; };
  }

  // IntersectionObserver — reveal
  function setupReveal() {
    const targets = document.querySelectorAll('[data-animate="reveal"]');
    state.io = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('in-view');
          state.io.unobserve(e.target);
        }
      });
    }, { threshold: 0.2 });

    targets.forEach((t) => state.io.observe(t));
  }

  // Гарчиг stagger
  function setupStagger() {
    const title = document.querySelector('[data-animate="stagger"]');
    if (!title) return;

    const reset = splitToChars(title);
    const chars = title.querySelectorAll('.char');
    title.classList.add('started');

    chars.forEach((c, i) => {
      const delay = 50 + i * 25; // ээлж дараалал
      c.style.transitionDelay = `${delay}ms`;
    });
  }

  // Particles фон
  function setupParticles() {
    const hero = document.getElementById('hero');
    if (!hero) return;

    const canvas = document.createElement('canvas');
    canvas.className = 'particles-canvas';
    hero.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    state.canvas = canvas;
    state.ctx = ctx;
    state.dark = isDark();

    function resize() {
      const rect = hero.getBoundingClientRect();
      canvas.width = Math.max(600, Math.floor(rect.width));
      canvas.height = Math.max(300, Math.floor(rect.height));
    }
    resize();
    window.addEventListener('resize', resize);

    const count = Math.floor((canvas.width * canvas.height) / 18000); // density
    state.particles = Array.from({ length: count }, () => ({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      vx: (Math.random() - 0.5) * 0.5,
      vy: (Math.random() - 0.5) * 0.5,
      r: 1 + Math.random() * 1.5,
    }));

    function color() {
      return state.dark ? 'rgba(255,255,255,0.35)' : 'rgba(0,0,0,0.25)';
    }
    function lineColor() {
      return state.dark ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.10)';
    }

    function tick() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      ctx.fillStyle = color();
      for (const p of state.particles) {
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > canvas.width) p.vx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();
      }

      ctx.strokeStyle = lineColor();
      ctx.lineWidth = 0.6;
      for (let i = 0; i < state.particles.length; i++) {
        for (let j = i + 1; j < state.particles.length; j++) {
          const a = state.particles[i];
          const b = state.particles[j];
          const dx = a.x - b.x;
          const dy = a.y - b.y;
          const d2 = dx * dx + dy * dy;
          if (d2 < 90 * 90) {
            ctx.globalAlpha = Math.max(0.05, 1 - d2 / (90 * 90)) * 0.8;
            ctx.beginPath();
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.stroke();
            ctx.globalAlpha = 1.0;
          }
        }
      }

      state.raf = requestAnimationFrame(tick);
    }
    tick();
  }

  // Параллакс
  function setupParallax() {
    const bg = document.getElementById('hero-bg');
    if (!bg) return;
    let rect = bg.getBoundingClientRect();

    function onMove(e) {
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = (e.clientX - cx) / rect.width;
      const dy = (e.clientY - cy) / rect.height;
      bg.style.transform = `translate(${dx * 12}px, ${dy * 12}px) scale(1.02)`;
    }
    function onLeave() {
      bg.style.transform = 'translate(0,0) scale(1)';
    }
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseleave', onLeave);
    window.addEventListener('resize', () => { rect = bg.getBoundingClientRect(); });
  }

  function init() {
    setupReveal();
    setupStagger();
    setupParticles();
    setupParallax();
  }

  return { init };
})();

document.addEventListener('DOMContentLoaded', () => {
  HeroAnimations.init();
});