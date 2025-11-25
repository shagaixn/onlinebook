(function(){
  const contentScript = document.getElementById('bookContent');
  if(!contentScript) return;
  let data; try { data = JSON.parse(contentScript.textContent); } catch(_) { return; }
  const rawHtml = data.html || '';
  const track = document.getElementById('slidesTrack');
  const prevBtn = document.getElementById('slidePrev');
  const nextBtn = document.getElementById('slideNext');
  const progressBar = document.getElementById('slidesProgressBar');
  if(!track) return;

  // Break content into logical paragraphs
  const paragraphs = rawHtml
    .replace(/\r/g,'')
    .split(/\n{2,}|<br\s*\/?>(?:\s*<br\s*\/?>)*/i)
    .map(p=>p.trim())
    .filter(p=>p.length>0);

  // Group paragraphs per slide (heuristic length)
  const slides = [];
  let current = [];
  let chars = 0;
  const LIMIT = 1200; // adjust for density
  paragraphs.forEach(p=>{
    chars += p.length;
    current.push(p);
    if(chars >= LIMIT){
      slides.push(current); current=[]; chars=0;
    }
  });
  if(current.length) slides.push(current);
  if(slides.length===0) slides.push(['Контент хоосон байна.']);

  // Build timeline items (one per paragraph start of each slide)
  function buildSlide(arr, index){
    const slide = document.createElement('div');
    slide.className='slide';
    const main = document.createElement('div');
    main.className='slide-main';
    arr.forEach(seg=>{
      const p = document.createElement('p');
      p.innerHTML = seg;
      main.appendChild(p);
    });
    const timeline = document.createElement('div');
    timeline.className='slide-timeline';
    const item = document.createElement('div');
    item.className='timeline-item';
    item.textContent='Хуудас ' + (index+1);
    timeline.appendChild(item);
    slide.appendChild(main);
    slide.appendChild(timeline);
    return slide;
  }

  slides.forEach((arr,i)=> track.appendChild(buildSlide(arr,i)) );

  let idx = 0;
  function clamp(n,min,max){ return Math.min(max, Math.max(min,n)); }
  function update(){
    track.style.transform = 'translateX(' + (-idx*100) + '%)';
    if(prevBtn) prevBtn.disabled = idx<=0;
    if(nextBtn) nextBtn.disabled = idx>=slides.length-1;
    if(progressBar){
      const ratio = slides.length>1 ? idx/(slides.length-1) : 1;
      progressBar.style.width = (ratio*100).toFixed(2)+'%';
    }
    // activate timeline item of current slide
    document.querySelectorAll('.timeline-item').forEach((el,i)=>{
      el.classList.toggle('active', i===idx);
    });
  }

  function go(delta){ idx = clamp(idx+delta, 0, slides.length-1); update(); }
  if(prevBtn) prevBtn.addEventListener('click',()=> go(-1));
  if(nextBtn) nextBtn.addEventListener('click',()=> go(1));
  document.addEventListener('keydown', e=>{ if(e.key==='ArrowLeft') go(-1); if(e.key==='ArrowRight') go(1); });

  // Resize: rebuild grouping if width changes significantly
  let lastWidth = window.innerWidth;
  window.addEventListener('resize', ()=>{
    if(Math.abs(window.innerWidth - lastWidth) > 160){
      // optional: rebuild slides for different density
      lastWidth = window.innerWidth;
    }
  });

  update();
})();
