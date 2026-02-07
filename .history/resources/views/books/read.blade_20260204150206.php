@include('include.header')
@vite('resources/css/book-reader.css')

<section class="portfolio-slider max-w-6xl mx-auto px-6 py-10" id="portfolioSlider">
    <a href="{{ route('book') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-300 hover:underline mb-6 px-6 pt-6">
        ← Буцах
    </a>
    <div class="slider-viewport-card">
                @php
                    $raw = trim((string)($book->description ?? ''));
                    // Split by blank lines or double newlines; fall back to single newline
                    $paragraphs = $raw !== '' ? preg_split('/\r?\n{2,}|\r?\n\r?\n+/u', $raw) : [];
                    $paragraphs = array_filter(array_map('trim', $paragraphs));
                    // Chunk paragraphs: reduced to 2 for lighter reading density
                    $chunkSize = 2;
                    $slides = [];
                    if(count($paragraphs)) {
                        $buffer = [];
                        foreach($paragraphs as $p) {
                            $buffer[] = $p;
                            if(count($buffer) >= $chunkSize) { $slides[] = $buffer; $buffer = []; }
                        }
                        if(count($buffer)) { $slides[] = $buffer; }
                    }
                @endphp

                @if(empty($slides))
                    <div class="text-center py-16 opacity-70 text-sm">Контент байхгүй байна.</div>
                @else
                    <div class="slides-track" id="profileSlides">
                        @foreach($slides as $i => $group)
                            <div class="portfolio-slide" data-slide="{{ $i }}">
                                <div class="slide-main">
                                    <h2 class="text-xl font-semibold mb-4">{{ $book->title }}</h2>
                                    @foreach($group as $para)
                                        <p>{!! nl2br(e($para)) !!}</p>
                                    @endforeach
                                </div>
                                <div class="slide-timeline">
                                    <div class="timeline-item {{ $i===0 ? 'active' : '' }}">Хуудас {{ $i+1 }}</div>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button id="sliderPrev" class="slider-arrow prev" aria-label="Previous">❮</button>
                    <button id="sliderNext" class="slider-arrow next" aria-label="Next">❯</button>
                    <div class="slider-progress" aria-label="Progress"><div id="sliderProgressBar" class="bar"></div></div>
                @endif
    </div>
</section>




@include('include.footer')

<script>
(function(){
    const track = document.getElementById('profileSlides');
    if(!track) return;
    const slides = Array.from(track.querySelectorAll('.portfolio-slide'));
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    const bar = document.getElementById('sliderProgressBar');
    
    let index = 0;
    const total = slides.length;

    if(total <= 1){
        if(prevBtn) prevBtn.style.display='none';
        if(nextBtn) nextBtn.style.display='none';
        if(bar) bar.style.width='100%';
        return;
    }

    function update(){
        track.style.transform = `translateX(-${index*100}%)`;
        slides.forEach((s,i)=>{
            s.classList.toggle('is-fading-out', i !== index);
            const tl = s.querySelector('.timeline-item');
            if(tl) tl.classList.toggle('active', i === index);
        });
        if(prevBtn) prevBtn.disabled = index === 0;
        if(nextBtn) nextBtn.disabled = index === total - 1;
        if(bar){
            const pct = ((index+1)/total)*100;
            bar.style.width = pct.toFixed(2)+'%';
        }
    }
    function go(delta){
        const next = index + delta;
        if(next < 0 || next >= total) return;
        index = next;
        update();

        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => saveProgress(index), 1000);
    }
    prevBtn && prevBtn.addEventListener('click', ()=>go(-1));
    nextBtn && nextBtn.addEventListener('click', ()=>go(1));
    // Allow keyboard navigation
    track.setAttribute('tabindex','0');
    track.addEventListener('keydown', e=>{
        if(e.key === 'ArrowRight') go(1);
        else if(e.key === 'ArrowLeft') go(-1);
    });
    update();
})();
</script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/profile-slider.js') }}"></script>