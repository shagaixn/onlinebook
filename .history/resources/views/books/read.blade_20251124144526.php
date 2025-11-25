@include('include.header')
<link rel="stylesheet" href="{{ asset('rescss/book-reader.css') }}">

<section class="portfolio-slider max-w-6xl mx-auto px-6 py-10" id="portfolioSlider">
    <div class="slider-viewport-card">
                @php
                    $raw = trim((string)($book->description ?? ''));
                    // Split by blank lines or double newlines; fall back to single newline
                    $paragraphs = $raw !== '' ? preg_split('/\r?\n{2,}|\r?\n\r?\n+/u', $raw) : [];
                    $paragraphs = array_filter(array_map('trim', $paragraphs));
                    // Chunk paragraphs ~4 per slide
                    $chunkSize = 4;
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
                                    <h2 class="text-xl font-semibold mb-4">{{ $book->title }} — Хэсэг {{ $i+1 }}</h2>
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

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/profile-slider.js') }}"></script>