@include('include.header')

<section class="relative overflow-hidden night-sky min-h-[calc(100vh-140px)]">
	<!-- Animated background orbs -->
	<div aria-hidden="true" class="pointer-events-none absolute inset-0">
		<div class="absolute -top-24 -left-24 w-80 h-80 rounded-full blur-3xl bg-blue-400/20 animate-pulse"></div>
		<div class="absolute top-1/3 -right-24 w-96 h-96 rounded-full blur-3xl bg-fuchsia-400/10 animate-[pulse_5s_ease-in-out_infinite]"></div>
	</div>

	<div class="max-w-5xl mx-auto px-6 py-8">
		<!-- Toolbar -->
		<div id="toolbar" class="backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/40 bg-white/80 dark:bg-slate-900/60 border border-white/40 dark:border-slate-800 rounded-2xl shadow-lg p-4 flex items-center justify-between gap-4">
			<div class="min-w-0">
				<h1 class="truncate text-lg sm:text-xl font-semibold text-slate-800 dark:text-slate-100">{{ $book->title ?? 'Ном' }}</h1>
				<p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ $book->author ?? ($book->authorModel->name ?? 'Зохиолч тодорхойгүй') }}</p>
			</div>
			<div class="flex items-center gap-3">
				<label class="text-xs text-slate-500 dark:text-slate-400">Фонт</label>
				<button id="font-dec" class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">A-</button>
				<button id="font-inc" class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">A+</button>
				<div class="hidden sm:flex items-center gap-2 pl-2 ml-2 border-l border-slate-200 dark:border-slate-800">
					<label class="text-xs text-slate-500 dark:text-slate-400">Өргөн</label>
					<input id="width-range" type="range" min="640" max="960" step="10" class="w-32 accent-blue-500" />
				</div>
			</div>
		</div>

		<!-- Reader card -->
		<div id="readerWrap" class="mx-auto mt-6 transition-[max-width] duration-300" style="max-width: 800px;">
			<article id="reader"
				class="prose prose-slate dark:prose-invert prose-headings:scroll-mt-20 leading-relaxed text-base sm:text-lg md:text-xl
							 bg-white/80 dark:bg-slate-900/60 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/40
							 border border-white/40 dark:border-slate-800 shadow-xl rounded-2xl p-0 sm:p-0 relative">

				<!-- Pagination nav buttons -->
				<button id="prevPage"
					class="absolute left-2 top-1/2 -translate-y-1/2 z-20 rounded-full bg-white/80 dark:bg-slate-800/70 border border-white/40 dark:border-slate-700 shadow px-3 py-2 text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-800"
					aria-label="Өмнөх хуудас" title="Өмнөх (←)">
					&larr;
				</button>
				<button id="nextPage"
					class="absolute right-2 top-1/2 -translate-y-1/2 z-20 rounded-full bg-white/80 dark:bg-slate-800/70 border border-white/40 dark:border-slate-700 shadow px-3 py-2 text-slate-700 dark:text-slate-200 hover:bg-white dark:hover:bg-slate-800"
					aria-label="Дараах хуудас" title="Дараах (→)">
					&rarr;
				</button>

				<!-- Paginated content area -->
				<div class="p-6 sm:p-8">
					@php
						$content = trim($book->description ?? '') !== '' ? $book->description : null;
					@endphp

					@if($content)
						<!-- This element is turned into a horizontal, paged, multi-column reader via JS -->
						<div id="pager"
								 class="relative overflow-x-auto overflow-y-hidden no-scrollbar"
								 style="column-gap: 40px;">
							{!! nl2br(e($content)) !!}
						</div>

						<!-- Small helper to hide scrollbars in WebKit -->
						<style>
							.no-scrollbar::-webkit-scrollbar { display: none; }
							.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
						</style>
					@else
						<h2 class="text-2xl font-semibold mb-4">Танилцуулга алга</h2>
						<p class="text-slate-600 dark:text-slate-300">Энэ номын дэлгэрэнгүй контент бүртгэлгүй байна. Админ хэсгээс тайлбар эсвэл контент нэмснээр уншигчид эндээс унших боломжтой болно.</p>
					@endif
				</div>
			</article>

			<!-- Page indicator -->
			<div id="page-indicator" class="mt-2 text-center text-xs text-slate-500 dark:text-slate-400"></div>
		</div>

		<!-- Progress bar -->
		<div class="fixed left-0 right-0 bottom-0 h-1.5 bg-slate-200/70 dark:bg-slate-800">
			<div id="read-progress" class="h-full w-0 bg-gradient-to-r from-blue-500 via-indigo-500 to-fuchsia-500 transition-[width] duration-150"></div>
		</div>
	</div>
</section>

@push('page-scripts')
<script>
(function(){
	const root = document.documentElement;
	const wrap = document.getElementById('readerWrap');
	const reader = document.getElementById('reader');
	const pager = document.getElementById('pager');
	const progress = document.getElementById('read-progress');
	const inc = document.getElementById('font-inc');
	const dec = document.getElementById('font-dec');
	const widthRange = document.getElementById('width-range');
	const prevBtn = document.getElementById('prevPage');
	const nextBtn = document.getElementById('nextPage');
	const toolbar = document.getElementById('toolbar');
	const indicator = document.getElementById('page-indicator');

	if (!reader) return;

	// State persistence
	const store = {
		get(k, d){ try { const v = localStorage.getItem(k); return v===null? d : JSON.parse(v); } catch(_) { return d; } },
		set(k, v){ try { localStorage.setItem(k, JSON.stringify(v)); } catch(_) {} }
	};

	// Font size (rem-based on reader)
	let base = store.get('reader:font', 1.0); // multiplier
	function applyFont(){ reader.style.fontSize = (base).toFixed(2)+'rem'; }

	// Reader width control
	let maxW = store.get('reader:maxWidth', 800);
	function applyWidth(){ if(wrap){ wrap.style.maxWidth = maxW + 'px'; } }

	// Pagination state
	let gap = 40; // must match inline style column-gap
	let pageW = 0;
	let pageH = 0;
	let pageCount = 1;
	let currentPage = store.get('reader:page', 0);

	function clamp(x,min,max){ return Math.min(max, Math.max(min, x)); }

	function isContentAvailable(){ return !!pager; }

	// Layout columns like book pages
	function layoutPagination(){
		if(!isContentAvailable()) return;

		// Calculate available height for the reader content area
		const toolbarH = toolbar ? toolbar.getBoundingClientRect().height : 64;
		const verticalMargins = 140; // header/footer and spacing allowance
		pageH = Math.max(320, Math.floor(window.innerHeight - toolbarH - verticalMargins));

		// Set the container height for column pagination
		pager.style.height = pageH + 'px';

		// Use the inner width of the reader content area as one "page" width
		// The pager already sits inside padded article, so just take its clientWidth.
		pageW = Math.floor(pager.clientWidth);

		// Apply column layout styles
		pager.style.columnWidth = pageW + 'px';
		pager.style.columnGap = gap + 'px';
		pager.style.columnFill = 'auto';

		// Compute page count using scrollWidth
		// scrollWidth ~= pageCount*pageW + (pageCount-1)*gap
		const total = pager.scrollWidth;
		pageCount = Math.max(1, Math.round((total + gap) / (pageW + gap)));

		// Clamp and restore current page, then jump without animation
		currentPage = clamp(currentPage, 0, pageCount - 1);
		scrollToPage(currentPage, false);

		updateProgress();
		updateNav();
		updateIndicator();
	}

	function scrollToPage(i, smooth=true){
		if(!isContentAvailable()) return;
		currentPage = clamp(i, 0, pageCount - 1);
		const x = currentPage * (pageW + gap);
		pager.scrollTo({ left: x, behavior: smooth ? 'smooth' : 'auto' });
		store.set('reader:page', currentPage);
		updateProgress();
		updateNav();
		updateIndicator();
	}

	function updateProgress(){
		if(!isContentAvailable()) { if(progress) progress.style.width = '0%'; return; }
		const ratio = pageCount > 1 ? currentPage / (pageCount - 1) : 0;
		if(progress) progress.style.width = (ratio * 100).toFixed(2) + '%';
	}

	function updateNav(){
		if(!prevBtn || !nextBtn) return;
		const hasMulti = pageCount > 1;
		prevBtn.style.display = hasMulti ? '' : 'none';
		nextBtn.style.display = hasMulti ? '' : 'none';
		prevBtn.disabled = currentPage <= 0;
		nextBtn.disabled = currentPage >= pageCount - 1;
	}

	function updateIndicator(){
		if(!indicator) return;
		if(pageCount <= 1) {
			indicator.textContent = '';
		} else {
			indicator.textContent = (currentPage + 1) + ' / ' + pageCount;
		}
	}

	// Throttle helper
	function throttle(fn, wait){
		let t=0, pending=null;
		return function(...args){
			const now = Date.now();
			if(!t || now - t >= wait){
				t = now; fn.apply(this, args);
			}else{
				clearTimeout(pending);
				pending = setTimeout(()=>{ t = Date.now(); fn.apply(this, args); }, wait - (now - t));
			}
		};
	}

	// Init controls
	applyFont();
	applyWidth();
	if(widthRange){ widthRange.value = maxW; }

	// Event handlers
	if(inc) inc.addEventListener('click', function(){
		base = clamp(base + 0.05, 0.7, 1.6);
		store.set('reader:font', base);
		applyFont();
		// Re-layout after font change
		requestAnimationFrame(layoutPagination);
	});

	if(dec) dec.addEventListener('click', function(){
		base = clamp(base - 0.05, 0.7, 1.6);
		store.set('reader:font', base);
		applyFont();
		// Re-layout after font change
		requestAnimationFrame(layoutPagination);
	});

	if(widthRange) widthRange.addEventListener('input', function(e){
		maxW = clamp(parseInt(e.target.value||800,10), 580, 1200);
		store.set('reader:maxWidth', maxW);
		applyWidth();
		// Re-layout after width change
		requestAnimationFrame(layoutPagination);
	});

	// Nav buttons
	if(prevBtn) prevBtn.addEventListener('click', ()=> scrollToPage(currentPage - 1));
	if(nextBtn) nextBtn.addEventListener('click', ()=> scrollToPage(currentPage + 1));

	// Keyboard arrows
	window.addEventListener('keydown', (e)=>{
		if(!isContentAvailable()) return;
		if(e.key === 'ArrowLeft'){ e.preventDefault(); scrollToPage(currentPage - 1); }
		if(e.key === 'ArrowRight'){ e.preventDefault(); scrollToPage(currentPage + 1); }
	});

	// Touch swipe
	let touchStartX = null;
	let touchStartY = null;
	const SWIPE_MIN = 40;
	if(pager){
		pager.addEventListener('touchstart', (e)=>{
			const t = e.touches[0];
			touchStartX = t.clientX;
			touchStartY = t.clientY;
		}, {passive:true});
		pager.addEventListener('touchend', (e)=>{
			if(touchStartX===null) return;
			const t = e.changedTouches[0];
			const dx = t.clientX - touchStartX;
			const dy = t.clientY - touchStartY;
			// Horizontal swipe dominant
			if(Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > SWIPE_MIN){
				if(dx < 0) scrollToPage(currentPage + 1);
				else scrollToPage(currentPage - 1);
			}
			touchStartX = touchStartY = null;
		});
	}

	// Wheel support (convert vertical wheel to horizontal page scroll)
	if(pager){
		pager.addEventListener('wheel', (e)=>{
			// If user scrolls vertically, translate into horizontal scroll
			if(Math.abs(e.deltaY) > Math.abs(e.deltaX)){
				e.preventDefault();
				pager.scrollLeft += e.deltaY;
			}
		}, {passive:false});

		// Snap current page on manual scroll
		pager.addEventListener('scroll', throttle(()=>{
			// Estimate current page based on scrollLeft
			const x = pager.scrollLeft;
			const est = Math.round(x / (pageW + gap));
			if(est !== currentPage){
				currentPage = clamp(est, 0, pageCount - 1);
				store.set('reader:page', currentPage);
				updateProgress();
				updateNav();
				updateIndicator();
			}
		}, 80));
	}

	// Recalculate on resize or orientation change
	window.addEventListener('resize', throttle(layoutPagination, 120));
	window.addEventListener('orientationchange', ()=> setTimeout(layoutPagination, 150));

	// Initial layout
	requestAnimationFrame(layoutPagination);
})();
</script>
@endpush

@include('include.footer')