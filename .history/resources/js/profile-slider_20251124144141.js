(function(){
  const track = document.getElementById('profileSlides');
  const prev = document.getElementById('sliderPrev');
  const next = document.getElementById('sliderNext');
  const bar = document.getElementById('sliderProgressBar');
  if(!track) return;
  const slides = Array.from(track.children);
  let index = 0;
  function clamp(n,min,max){return Math.min(max,Math.max(min,n));}
  function update(){
    track.style.transform = 'translateX(' + (-index*100) + '%)';
    if(prev) prev.disabled = index<=0;
    if(next) next.disabled = index>=slides.length-1;
    if(bar){
      const ratio = slides.length>1 ? index/(slides.length-1) : 1;
      bar.style.width = (ratio*100).toFixed(2)+'%';
    }
    slides.forEach((s,i)=>{
      s.classList.toggle('is-active', i===index);
    });
    try { localStorage.setItem('portfolio:lastIndex', String(index)); } catch(_){ }
  }
  function go(delta){ index = clamp(index+delta,0,slides.length-1); update(); }
  if(prev) prev.addEventListener('click',()=>go(-1));
  if(next) next.addEventListener('click',()=>go(1));
  document.addEventListener('keydown', e=>{
    if(e.key==='ArrowLeft') go(-1);
    if(e.key==='ArrowRight') go(1);
  });
  // Restore last position
  try { const saved = parseInt(localStorage.getItem('portfolio:lastIndex')||'0',10); if(!isNaN(saved)) index = clamp(saved,0,slides.length-1); } catch(_){ }
  update();
})();
