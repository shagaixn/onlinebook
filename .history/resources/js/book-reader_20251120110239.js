(function () {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  const root = $('#reader');
  if (!root) return;

  const total = Number(root.dataset.total || '0');
  const initialMode = (root.dataset.initialMode || 'scroll').toLowerCase();
  const progressBar = $('#readerProgress');
  const progressLabel = $('#readerProgressLabel');
  const modeBtn = $('#modeToggle');
  const modeLabel = modeBtn?.querySelector('[data-mode-label]');
  const pageNav = $('#pageModeNav');
  const btnPrev = $('#goPrev');
  const btnNext = $('#goNext');
  const pageCounter = $('#pageCounter');

  let mode = (initialMode === 'page' ? 'page' : 'scroll');
  let pageIndex = 0;

  // Helpers
  const setMode = (m) => {
    mode = m;
    root.classList.toggle('mode-page', mode === 'page');
    root.classList.toggle('mode-scroll', mode === 'scroll');

    // URL параметрт хадгалах
    try {
      const url = new URL(window.location.href);
      url.searchParams.set('mode', mode);
      window.history.replaceState(null, '', url.toString());
    } catch (_) {}

    // Page mode UI
    if (mode === 'page') {
      pageNav?.removeAttribute('hidden');
      ensureOnlyCurrentVisible();
      updatePageCounter();
      updateProgress();
      prefetchNext();
    } else {
      pageNav?.setAttribute('hidden', 'true');
      // Скроллд бүх хуудсыг харуулна
      $$('.page').forEach((img) => img.classList.remove('current'));
    }

    if (modeLabel) modeLabel.textContent = (mode === 'page' ? 'Page' : 'Scroll');
  };

  const clamp = (n, min, max) => Math.max(min, Math.min(max, n));

  const updateProgress = () => {
    let pct = 0;
    if (mode === 'page') {
      if (total > 0) pct = (pageIndex + 1) / total;
    } else {
      const doc = document.documentElement;
      const scrollTop = doc.scrollTop || document.body.scrollTop;
      const scrollHeight = doc.scrollHeight - doc.clientHeight;
      pct = scrollHeight > 0 ? clamp(scrollTop / scrollHeight, 0, 1) : 0;
    }
    progressBar && (progressBar.style.width = `${Math.round(pct * 100)}%`);
    if (progressLabel) {
      progressLabel.textContent = `${Math.round(pct * 100)}%` + (mode === 'page' && total ? ` • ${pageIndex + 1}/${total}` : '');
    }
  };

  const ensureOnlyCurrentVisible = () => {
    const pages = $$('.page');
    pages.forEach((img, i) => {
      if (i === pageIndex) img.classList.add('current');
      else img.classList.remove('current');
    });
    // Page index рүү скролл үсрэхгүй байхаар container-ыг дээр нь төвшинд барина
    const currentWrap = $(`.page-wrap[data-index="${pageIndex}"]`);
    if (currentWrap && mode === 'page') {
      currentWrap.scrollIntoView({ block: 'center', behavior: 'instant' in (window || {}) ? 'instant' : 'auto' });
    }
  };

  const updatePageCounter = () => {
    if (pageCounter) pageCounter.textContent = `${total ? pageIndex + 1 : 0} / ${total}`;
  };

  const goPrev = () => {
    if (mode === 'page') {
      pageIndex = clamp(pageIndex - 1, 0, total - 1);
      ensureOnlyCurrentVisible();
      updatePageCounter();
      updateProgress();
    } else {
      window.scrollBy({ top: -window.innerHeight * 0.8, behavior: 'smooth' });
    }
  };
  const goNext = () => {
    if (mode === 'page') {
      pageIndex = clamp(pageIndex + 1, 0, total - 1);
      ensureOnlyCurrentVisible();
      updatePageCounter();
      updateProgress();
      prefetchNext();
    } else {
      window.scrollBy({ top: window.innerHeight * 0.8, behavior: 'smooth' });
    }
  };

  const prefetchNext = () => {
    if (mode !== 'page') return;
    const imgs = $$('.page');
    const windowSize = 5;
    for (let i = pageIndex + 1; i < Math.min(imgs.length, pageIndex + 1 + windowSize); i++) {
      const el = imgs[i];
      const src = el.getAttribute('data-src');
      if (src && !el.dataset.prefetched) {
        const img = new Image();
        img.src = src;
        el.dataset.prefetched = '1';
      }
    }
  };

  // Lazy load with IntersectionObserver
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
      const img = e.target.querySelector('img.page');
      const sk = e.target.querySelector('.skeleton.page');
      if (!img) return;
      if (e.isIntersecting && !img.src) {
        const src = img.getAttribute('data-src');
        if (src) {
          img.addEventListener('load', () => {
            if (sk) sk.style.display = 'none';
            img.classList.add('loaded');
          }, { once: true });
          img.src = src;
        }
      }
    });
  }, { rootMargin: '1200px 0px 1200px 0px' });

  $$('.page-wrap').forEach((el) => io.observe(el));

  // Scroll progress
  window.addEventListener('scroll', () => { if (mode === 'scroll') updateProgress(); }, { passive: true });
  window.addEventListener('resize', () => { if (mode === 'scroll') updateProgress(); });

  // Keyboard shortcuts
  window.addEventListener('keydown', (e) => {
    const k = e.key.toLowerCase();
    if (k === 'arrowright' || k === 'j') { e.preventDefault(); goNext(); }
    if (k === 'arrowleft'  || k === 'k') { e.preventDefault(); goPrev(); }
  });

  // Buttons
  btnPrev?.addEventListener('click', goPrev);
  btnNext?.addEventListener('click', goNext);

  modeBtn?.addEventListener('click', () => {
    setMode(mode === 'scroll' ? 'page' : 'scroll');
  });

  // Init mode
  setMode(mode);

  // Initial progress after first paint
  requestAnimationFrame(updateProgress);
})();