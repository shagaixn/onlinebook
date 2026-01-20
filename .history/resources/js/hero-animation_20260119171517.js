// Hero хэсгийн хүчтэй анимейшн (бүтнээр бүрхэх + нягтрал их)
const HeroAnimations = (() => {
  const state = {
    io: null,
    particles: [],
    ctx: null,
    canvas: null,
    raf: null,
    dark: false,
    hero: null,
    mouse: { x: null, y: null },
  };

  // Тохиргоо: илүү "байжуулж" харагдуулах
  const config = {
    intensity: 1.8,       // Нягтралын үржүүлэгч (ихсэх тусам олон ширхэг)
    speed: 0.8,           // Хурд
    connectDistance: 120, // Шугамаар холбох зай (px)
    maxDPR: 2,            // Дээд DPR.scale (гүйцэтгэл бодолцоно)
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

    splitToChars(title);
    const chars = title.querySelectorAll('.char');
    title.classList.add('started');

    chars.forEach((c, i) => {
      const delay = 40 + i * 22;
      c.style.transitionDelay = `${delay}ms`;
    });
  }

  // Canvas-ийг div-ийг бүтнээр нь бүрхэх + DPR тохируулах
  function sizeCanvasToHero() {
    const hero = state.hero;
    const canvas = state.canvas;
    const ctx = state.ctx;
    const dpr = Math.min(window.devicePixelRatio || 1, config.maxDPR);

    const width = hero.clientWidth;
    const height = hero.clientHeight;

    // CSS хэмжээс
    canvas.style.width = '100%';
    canvas.style.height = '100%';

    // Жинхэнэ пиксел хэмжээс
    canvas.width = Math.max(1, Math.floor(width * dpr));
    canvas.height = Math.max(1, Math.floor(height * dpr));

    // CSS пиксел координатаар зурахын тулд transform
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  // Particles фон — илүү нягт, хурдан
  function setupParticles() {
    state.hero = document.getElementById('hero');
    if (!state.hero) return;

    const canvas = document.createElement('canvas');
    canvas.className = 'particles-canvas';
    state.hero.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    state.canvas = canvas;
    state.ctx = ctx;
    state.dark = isDark();

    sizeCanvasToHero();

    // Hero хэмжээ солигдоход автоматаар тааруулах
    const ro = new ResizeObserver(() => sizeCanvasToHero());
    ro.observe(state.hero);
    window.addEventListener('resize', sizeCanvasToHero);

    // Нягтрал — div талбай + intensity
    function desiredCount() {
      const area = state.hero.clientWidth * state.hero.clientHeight;
      return Math.floor((area / 14000) * config.intensity);
    }

    function resetParticles() {
      const count = desiredCount();
      state.particles = Array.from({ length: count }, () => ({
        x: Math.random() * state.hero.clientWidth,
        y: Math.random() * state.hero.clientHeight,
        vx: (Math.random() - 0.5) * config.speed,
        vy: (Math.random() - 0.5) * config.speed,
        r: 1 + Math.random() * 1.6,
      }));
    }
    resetParticles();

    // Хулгана — ойролцоо хэсэгт багахан татах/түлхэх эффект
    window.addEventListener('mousemove', (e) => {
      const rect = state.hero.getBoundingClientRect();
      state.mouse.x = e.clientX - rect.left;
      state.mouse.y = e.clientY - rect.top;
    });
    window.addEventListener('mouseleave', () => {
      state.mouse.x = null;
      state.mouse.y = null;
    });

    function particleColor() {
      return state.dark ? 'rgba(255,255,255,0.38)' : 'rgba(0,0,0,0.30)';
    }
    function lineColor() {
      return state.dark ? 'rgba(255,255,255,0.16)' : 'rgba(0,0,0,0.14)';
    }

    function tick() {
      ctx.clearRect(0, 0, state.hero.clientWidth, state.hero.clientHeight);

      // Particles хөдөлгөөн
      ctx.fillStyle = particleColor();
      for (const p of state.particles) {
        // Хулганын нөлөө (зөөлөн түлхэлт)
        if (state.mouse.x != null && state.mouse.y != null) {
          const dx = p.x - state.mouse.x;
          const dy = p.y - state.mouse.y;
          const d2 = dx * dx + dy * dy;
          const r = 90;
          if (d2 < r * r) {
            const force = (r - Math.sqrt(d2)) / r * 0.2;
            p.vx += (dx / (Math.sqrt(d2) + 0.001)) * force;
            p.vy += (dy / (Math.sqrt(d2) + 0.001)) * force;
          }
        }

        p.x += p.vx;
        p.y += p.vy;

        // Хил түүнд буцаж ойно
        if (p.x < 0 || p.x > state.hero.clientWidth) p.vx *= -1;
        if (p.y < 0 || p.y > state.hero.clientHeight) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();
      }

      // Ойролцоо цэгүүдийг холбох
      const dist = config.connectDistance;
      const dist2 = dist * dist;
      ctx.strokeStyle = lineColor();
      ctx.lineWidth = 0.8;
      for (let i = 0; i < state.particles.length; i++) {
        for (let j = i + 1; j < state.particles.length; j++) {
          const a = state.particles[i];
          const b = state.particles[j];
          const dx = a.x - b.x;
          const dy = a.y - b.y;
          const d2 = dx * dx + dy * dy;
          if (d2 < dist2) {
            ctx.globalAlpha = Math.max(0.05, 1 - d2 / dist2) * 0.9;
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

  // Параллакс — арын gradient div-ийг хүчтэйгээр хөдөлгөнө
  function setupParallax() {
    const bg = document.getElementById('hero-bg');
    if (!bg) return;
    let rect = bg.getBoundingClientRect();

    function onMove(e) {
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = (e.clientX - cx) / rect.width;
      const dy = (e.clientY - cy) / rect.height;
      bg.style.transform = `translate(${dx * 16}px, ${dy * 16}px) scale(1.03)`;
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