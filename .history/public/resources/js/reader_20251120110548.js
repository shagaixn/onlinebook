(function(){
  const $ = (sel, root=document) => root.querySelector(sel);
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

  const reader = $('#reader');
  if(!reader){ return; }

  const total = parseInt(reader.getAttribute('data-total')||'0',10);
  const initialMode = reader.getAttribute('data-initial-mode') || 'scroll';
  const topbar = $('#readerTopbar');
  const modeToggle = $('#modeToggle');
  const modeLabel = modeToggle ? modeToggle.querySelector('[data-mode-label]') : null;
  const progressBar = $('#readerProgress');
  const progressLabel = $('#readerProgressLabel');
  const pageModeNav = $('#pageModeNav');
  const goPrev = $('#goPrev');
  const goNext = $('#goNext');
  const pageCounter = $('#pageCounter');

  let mode = (initialMode === 'page') ? 'page' : 'scroll';
  let currentIndex = 0;

  // Lazy load images with skeletons
  const wraps = $$('.page-wrap', reader);
  const io = ('IntersectionObserver' in window) ? new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        const wrap = e.target;
        const img = $('img.page', wrap);
        const skeleton = $('.skeleton.page', wrap);
        if(img && img.dataset && img.dataset.src){
          img.src = img.dataset.src;
          img.onload = ()=>{ if(skeleton) skeleton.style.display='none'; };
          img.onerror = ()=>{ if(skeleton) skeleton.style.display='none'; };
          delete img.dataset.src;
        }
        io.unobserve(wrap);
      }
    });
  },{ rootMargin: '200px 0px' }) : null;

  wraps.forEach(w=>{ if(io) io.observe(w); else {
    const img=$('img.page', w); const sk=$('.skeleton.page', w);
    if(img && img.dataset && img.dataset.src){ img.src=img.dataset.src; img.onload=()=>{ if(sk) sk.style.display='none'; }; }
  }});

  // Progress update
  function updateProgress(){
    if(mode==='scroll'){
      const el = document.scrollingElement || document.documentElement;
      const ratio = el.scrollHeight>el.clientHeight ? (el.scrollTop/(el.scrollHeight-el.clientHeight)) : 0;
      const pct = Math.max(0, Math.min(100, Math.round(ratio*100)));
      if(progressBar) progressBar.style.width = pct + '%';
      if(progressLabel) progressLabel.textContent = pct + '%';
    } else {
      const pct = total>1 ? Math.round((currentIndex/(total-1))*100) : (total>0?100:0);
      if(progressBar) progressBar.style.width = pct + '%';
      if(progressLabel) progressLabel.textContent = pct + '%';
    }
  }

  // Mode switching
  function applyMode(){
    reader.classList.toggle('mode-scroll', mode==='scroll');
    reader.classList.toggle('mode-page', mode==='page');
    if(pageModeNav) pageModeNav.hidden = (mode!=='page');
    if(modeLabel) modeLabel.textContent = (mode==='scroll' ? 'Scroll' : 'Page');
    if(mode==='page'){
      // Center on current page
      showCurrentPage();
    }
    updateProgress();
  }

  function showCurrentPage(){
    wraps.forEach((w,i)=>{
      if(mode==='page'){
        w.classList.toggle('is-current', i===currentIndex);
      } else {
        w.classList.remove('is-current');
      }
    });
    if(pageCounter){ pageCounter.textContent = (Math.min(total, currentIndex+1)) + ' / ' + total; }
  }

  function clamp(n, min, max){ return Math.min(max, Math.max(min, n)); }

  function go(delta){
    currentIndex = clamp(currentIndex + delta, 0, Math.max(0, total-1));
    showCurrentPage();
    updateProgress();
  }

  // Init
  if(modeToggle){ modeToggle.addEventListener('click', ()=>{ mode = (mode==='scroll' ? 'page' : 'scroll'); applyMode(); }); }
  if(goPrev){ goPrev.addEventListener('click', ()=> go(-1)); }
  if(goNext){ goNext.addEventListener('click', ()=> go(1)); }
  document.addEventListener('keydown', (e)=>{
    if(mode==='page'){
      if(e.key==='ArrowLeft') go(-1);
      if(e.key==='ArrowRight') go(1);
    }
  });

  window.addEventListener('scroll', ()=>{ if(mode==='scroll') updateProgress(); }, {passive:true});
  window.addEventListener('resize', ()=>{ updateProgress(); });

  // Activate initial mode
  applyMode();
  showCurrentPage();
  updateProgress();
})();
