(function(){
  'use strict';

  const contentJSON = document.getElementById('bookContent');
  if(!contentJSON) return;

  // Elements
  const wrap = document.getElementById('readerWrap');
  const reader = document.getElementById('reader');
  const container = document.getElementById('pageContainer');
  const prevBtn = document.getElementById('prevPage');
  const nextBtn = document.getElementById('nextPage');
  const indicator = document.getElementById('pageIndicator');
  const progressBar = document.getElementById('readProgress');
  const inc = document.getElementById('font-inc');
  const dec = document.getElementById('font-dec');
  const widthRange = document.getElementById('width-range');

  // Data
  let data = null;
  try { data = JSON.parse(contentJSON.textContent || '{}'); } catch(e){ data = {id:0, raw:''}; }

  const bookId = data.id || 0;
  const raw = data.raw || '';

  // Config
  const PAGE_TARGET_CHARS = 2300; // нэг хуудсанд ойролцоо тэмдэгт
  const MIN_FONT = 0.7, MAX_FONT = 1.6;
  const MIN_WIDTH = 580, MAX_WIDTH = 1200;

  // State
  let pages = [];
  let currentPage = getStore(`book:${bookId}:page`, 0);
  let fontSize = getStore('reader:font', 1.0);
  let maxW = getStore('reader:maxWidth', 800);

  // LocalStorage helpers
  function getStore(k, d){ try{ const v = localStorage.getItem(k); return v === null ? d : JSON.parse(v);}catch(_){ return d; } }
  function setStore(k,v){ try{ localStorage.setItem(k, JSON.stringify(v)); }catch(_){} }

  function clamp(x,a,b){ return Math.min(b, Math.max(a,x)); }

  // Build pages (paragraph aware heuristic)
  function buildPages(text){
    const paragraphs = text
      .replace(/\r\n/g,'\n')
      .split(/\n{2,}/)
      .map(p=>p.trim())
      .filter(Boolean);

    const result = [];
    let buffer = '';
    for(const p of paragraphs){
      const add = buffer.length ? buffer + '\n\n' + p : p;
      if(add.length > PAGE_TARGET_CHARS * 1.2){ // нэг том paragraph дангаараа
        if(buffer) { result.push(buffer); buffer=''; }
        result.push(p);
      } else if(add.length > PAGE_TARGET_CHARS){
        result.push(add);
        buffer = '';
      } else {
        buffer = add;
        if(buffer.length >= PAGE_TARGET_CHARS){
          result.push(buffer);
          buffer = '';
        }
      }
    }
    if(buffer) result.push(buffer);
    if(!result.length) result.push(text);
    return result;
  }

  function escapeHtml(s){
    return s
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;');
  }

  function formatBlock(block){
    // If HTML exists, keep it (basic heuristic)
    if(/<\/?[a-z][\s\S]*>/i.test(block)){
      return block.replace(/(?<!\n)\n(?!\n)/g,'<br>');
    }
    const parts = block.split(/\n{2,}/).map(seg=>{
      const lines = seg.split(/\n/).map(l=> escapeHtml(l));
      return `<p>${lines.join('<br>')}</p>`;
    });
    return parts.join('\n');
  }

  function renderPages(){
    container.innerHTML = '';
    pages.forEach((pg, i)=>{
      const el = document.createElement('section');
      el.className = 'page prose prose-slate dark:prose-invert max-w-none';
      el.dataset.index = i;
      el.innerHTML = formatBlock(pg);
      container.appendChild(el);
    });
    goToPage(clamp(currentPage,0,pages.length-1), false);
    setupObserver();
    updateNav();
    updateIndicator();
  }

  function goToPage(i, smooth=true){
    currentPage = clamp(i, 0, pages.length-1);
    setStore(`book:${bookId}:page`, currentPage);
    const target = container.querySelector(`.page[data-index="${currentPage}"]`);
    if(target){
      target.scrollIntoView({behavior: smooth?'smooth':'auto', block:'start'});
    }
    updateIndicator();
    updateNav();
    updateProgress(currentPage / Math.max(1, pages.length-1));
  }

  function updateIndicator(){
    if(indicator){
      indicator.textContent = pages.length ? `${currentPage+1} / ${pages.length}` : '';
    }
  }

  function updateNav(){
    if(prevBtn) prevBtn.disabled = currentPage <= 0;
    if(nextBtn) nextBtn.disabled = currentPage >= pages.length-1;
  }

  function updateProgress(ratio){
    if(progressBar){
      progressBar.style.width = (ratio*100).toFixed(2)+'%';
    }
  }

  // IntersectionObserver – page tracking
  let observer;
  function setupObserver(){
    if(observer) observer.disconnect();
    observer = new IntersectionObserver(entries=>{
      const vis = entries.filter(e=> e.isIntersecting)
        .sort((a,b)=> parseInt(a.target.dataset.index) - parseInt(b.target.dataset.index));
      if(vis.length){
        const idx = parseInt(vis[0].target.dataset.index,10);
        if(idx !== currentPage){
          currentPage = idx;
          setStore(`book:${bookId}:page`, currentPage);
          updateIndicator();
          updateNav();
          updateProgress(currentPage / Math.max(1, pages.length-1));
        }
      }
    }, { root: container, threshold: 0.6 });
    container.querySelectorAll('.page').forEach(p=> observer.observe(p));
  }

  // Font & width application
  function applyFont(){ reader.style.fontSize = fontSize.toFixed(2)+'rem'; }
  function applyWidth(){ if(wrap) wrap.style.maxWidth = maxW + 'px'; }

  // Events
  prevBtn?.addEventListener('click', ()=> goToPage(currentPage - 1));
  nextBtn?.addEventListener('click', ()=> goToPage(currentPage + 1));

  inc?.addEventListener('click', ()=>{
    fontSize = clamp(fontSize + 0.05, MIN_FONT, MAX_FONT);
    setStore('reader:font', fontSize);
    applyFont();
  });

  dec?.addEventListener('click', ()=>{
    fontSize = clamp(fontSize - 0.05, MIN_FONT, MAX_FONT);
    setStore('reader:font', fontSize);
    applyFont();
  });

  widthRange?.addEventListener('input', e=>{
    maxW = clamp(parseInt(e.target.value||800,10), MIN_WIDTH, MAX_WIDTH);
    setStore('reader:maxWidth', maxW);
    applyWidth();
  });

  // Keyboard navigation
  window.addEventListener('keydown', (e)=>{
    if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if(e.key === 'ArrowLeft'){ goToPage(currentPage - 1); }
    if(e.key === 'ArrowRight'){ goToPage(currentPage + 1); }
    if(e.key === 'Home'){ goToPage(0); }
    if(e.key === 'End'){ goToPage(pages.length-1); }
  });

  // Resize: зөвхөн progress дахин тооцоол
  window.addEventListener('resize', ()=>{
    // Хэрэв та resize үед хуудсуудад дахин хуваахыг хүсвэл:
    // pages = buildPages(raw); renderPages();
    setupObserver();
  });

  // Init
  applyFont();
  applyWidth();
  if(widthRange) widthRange.value = maxW;

  pages = buildPages(raw);
  renderPages();

})();