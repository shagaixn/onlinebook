// Хөнгөн гүйцэтгэлтэй, зөвхөн секшнд ажиллах particles + анимейшн
const HeroAnimations = (() => {
  const state = {
    ioReveal: null,
    ioVisibility: null,
    particles: [],
    ctx: null,
    canvas: null,
    raf: null,
    running: false,
    dark: false,
    hero: null,
    mouse: { x: null, y: null },
    lastTime: 0,
  };

  const isMobile = window.matchMedia('(max-width: 640px)').matches;

  const config = {
    targetFPS: 30,          // FPS-г бууруулж CPU-г хэмнэнэ
    maxDPR: 1.2,            // DPR хязгаар
    intensity: 1.0,         // Нягтралын үржүүлэгч
    maxParticles: isMobile ? 80 : 140, // Дээд ширхэг
    speed: isMobile ? 0.6 : 0.8,
    connectDistance: isMobile ? 0 : 110, // Mobile дээр шугам унтарна
    maxNeighbors: 6,        // Нэг цэгээс холбох дээд хөршүүд
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

  // Илрэх анимейшн
  function setupReveal() {
    const targets = document.querySelectorAll('[data-animate="reveal"]');
    state.ioReveal = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('in-view');
          state.ioReveal.unobserve(e.target);
        }
      });
    }, { threshold: 0.2 });
    targets.forEach((t) => state.ioReveal.observe(t));
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

  // Canvas зөвхөн hero дотор, DPR хязгаарлана
  function sizeCanvasToHero() {
    const hero = state.hero;
    const canvas = state.canvas;
    const ctx = state.ctx;
    const dpr = Math.min(window.devicePixelRatio || 1, config.maxDPR);

    const width = hero.clientWidth;
    const height = hero.clientHeight;

    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.width = Math.max(1, Math.floor(width * dpr));
    canvas.height = Math.max(1, Math.floor(height * dpr));
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  // Particles зөвхөн hero дотор
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

    const ro = new ResizeObserver(() => {
      sizeCanvasToHero();
      // Хэмжээ өөрчлөгдвөл нягтралыг дахин тооцоолно
      resetParticles();
    });
    ro.observe(state.hero);
    window.addEventListener('resize', sizeCanvasToHero);

    function desiredCount() {
      const area = state.hero.clientWidth * state.hero.clientHeight;
      const base = Math.floor(area / 16000); // бага зэрэг шингэн
      return Math.min(config.maxParticles, Math.floor(base * config.intensity));
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

    window.addEventListener('mousemove', (e) => {
      const rect = state.hero.getBoundingClientRect();
      state.mouse.x = e.clientX - rect.left;
      state.mouse.y = e.clientY - rect.top;
    });
    window.addEventListener('mouseleave', () => {
      state.mouse.x = null;
      state.mouse.y = null;
    });

    // Харагдах эсэхээр анимейшнийг асаах/унтраах
    state.ioVisibility = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (e.target === state.hero) {
          if (e.isIntersecting) start();
          else stop();
        }
      });
    }, { threshold: 0.15 });
    state.ioVisibility.observe(state.hero);
  }

  function start() {
    if (!state.running) {
      state.running = true;
      state.lastTime = performance.now();
      state.raf = requestAnimationFrame(tick);
    }
  }
  function stop() {
    state.running = false;
    if (state.raf) cancelAnimationFrame(state.raf);
  }

  function particleColor() {
    return state.dark ? 'rgba(255,255,255,0.35)' : 'rgba(0,0,0,0.28)';
  }
  function lineColor() {
    return state.dark ? 'rgba(255,255,255,0.14)' : 'rgba(0,0,0,0.12)';
  }

  // Spatial grid — ойролцоох хөршүүдийг л шалгана
  function buildGrid(cellSize) {
    const grid = new Map();
    for (let i = 0; i < state.particles.length; i++) {
      const p = state.particles[i];
      const cx = Math.floor(p.x / cellSize);
      const cy = Math.floor(p.y / cellSize);
      const key = `${cx},${cy}`;
      let bucket = grid.get(key);
      if (!bucket) {
        bucket = [];
        grid.set(key, bucket);
      }
      bucket.push(i);
    }
    return grid;
  }

  function tick(now) {
    if (!state.running) return;

    const interval = 1000 / config.targetFPS;
    if (now - state.lastTime < interval) {
      state.raf = requestAnimationFrame(tick);
      return;
    }
    state.lastTime = now;

    const ctx = state.ctx;
    const w = state.hero.clientWidth;
    const h = state.hero.clientHeight;

    ctx.clearRect(0, 0, w, h);

    // Update + draw particles
    ctx.fillStyle = particleColor();
    for (const p of state.particles) {
      // Mouse influence (зөөлөн)
      if (state.mouse.x != null && state.mouse.y != null) {
        const dx = p.x - state.mouse.x;
        const dy = p.y - state.mouse.y;
        const d2 = dx * dx + dy * dy;
        const r = 80;
        if (d2 < r * r) {
          const dist = Math.sqrt(d2) || 1;
          const force = (r - dist) / r * 0.18;
          p.vx += (dx / dist) * force;
          p.vy += (dy / dist) * force;
        }
      }

      p.x += p.vx;
      p.y += p.vy;

      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fill();
    }

    // Connect nearby using grid
    const dist = config.connectDistance;
    if (dist > 0) {
      const dist2 = dist * dist;
      const cellSize = dist;
      const grid = buildGrid(cellSize);
      ctx.strokeStyle = lineColor();
      ctx.lineWidth = 0.7;

      for (let i = 0; i < state.particles.length; i++) {
        const a = state.particles[i];
        const cx = Math.floor(a.x / cellSize);
        const cy = Math.floor(a.y / cellSize);

        let neighbors = 0;
        for (let gx = cx - 1; gx <= cx + 1; gx++) {
          for (let gy = cy - 1; gy <= cy + 1; gy++) {
            const bucket = grid.get(`${gx},${gy}`);
            if (!bucket) continue;
            for (const j of bucket) {
              if (j <= i) continue; // давталтын давхардалгүй
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

                neighbors++;
                if (neighbors >= config.maxNeighbors) break;
              }
            }
            if (neighbors >= config.maxNeighbors) break;
          }
          if (neighbors >= config.maxNeighbors) break;
        }
      }
    }

    state.raf = requestAnimationFrame(tick);
  }

  function init() {
    setupReveal();
    setupStagger();
    setupParticles();
    // Параллакс нь секшн доторх bg-д бага зэргийн хөдөлгөөнтэй, гүйцэтгэлд хөнгөн
    setupParallax();
  }

  // Параллакс — зөвхөн hero доторх bg
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

  return { init, start, stop };
})();

document.addEventListener('DOMContentLoaded', () => {
  HeroAnimations.init();
});