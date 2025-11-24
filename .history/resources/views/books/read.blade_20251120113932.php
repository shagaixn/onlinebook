@include('include.header')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/book-reader.css') }}">
@endpush

<section class="reader-shell relative overflow-hidden min-h-[calc(100vh-140px)]">
	<!-- Ambient blobs -->
	<div aria-hidden="true" class="pointer-events-none absolute inset-0">
		<div class="ambient-blob blob-a"></div>
		<div class="ambient-blob blob-b"></div>
	</div>

	<div class="max-w-5xl mx-auto px-6 py-8">
		<!-- Toolbar -->
		<div id="toolbar"
			 class="reader-toolbar backdrop-blur-xl border rounded-2xl shadow-lg flex items-center justify-between gap-4">
			<div class="min-w-0">
				<h1 class="truncate reader-title">{{ $book->title ?? 'Ном' }}</h1>
				<p class="truncate reader-author">{{ $book->author ?? ($book->authorModel->name ?? 'Зохиолч тодорхойгүй') }}</p>
			</div>
			<div class="flex items-center gap-3">
				<label class="text-xs font-medium opacity-70">Фонт</label>
				<button id="font-dec" class="ui-btn ui-btn-sm" aria-label="Фонт жижигрүүлэх">A-</button>
				<button id="font-inc" class="ui-btn ui-btn-sm" aria-label="Фонт томруулах">A+</button>
				<div class="hidden sm:flex items-center gap-2 pl-3 ml-2 border-l border-slate-200/70 dark:border-slate-700/60">
					<label class="text-xs font-medium opacity-70">Өргөн</label>
					<input id="width-range" type="range" min="640" max="960" step="10"
						   class="w-36 accent-blue-500 reader-range" aria-label="Уншигчийн өргөнийг тохируулах" />
				</div>
			</div>
		</div>

		<!-- Reader card -->
		<div id="readerWrap" class="mx-auto mt-6 transition-[max-width] duration-300 will-change-[max-width]" style="max-width: 800px;">
			<article id="reader"
				class="reader-surface prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl relative">

				<!-- Pagination nav buttons -->
				<button id="prevPage"
					class="page-nav-btn left-btn"
					aria-label="Өмнөх хуудас" title="Өмнөх (←)">
					<span class="sr-only">Өмнөх</span>
					&larr;
				</button>
				<button id="nextPage"
					class="page-nav-btn right-btn"
					aria-label="Дараах хуудас" title="Дараах (→)">
					<span class="sr-only">Дараах</span>
					&rarr;
				</button>

				<!-- Paginated content area -->
				<div class="reader-padding">
					@php
						$content = trim($book->description ?? '') !== '' ? $book->description : null;
					@endphp

					@if($content)
						<div id="pager"
							 class="paged-columns relative overflow-x-auto overflow-y-hidden no-scrollbar isolate"
							 style="column-gap: 46px;">
							<div class="pager-fade left"></div>
							<div class="pager-fade right"></div>
							{!! nl2br(e($content)) !!}
						</div>

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
			<div id="page-indicator" class="mt-3 text-center text-xs tracking-wide text-slate-500 dark:text-slate-400 font-medium"></div>
		</div>

		<!-- Progress bar -->
		<div class="fixed left-0 right-0 bottom-0 h-2 progress-shell">
			<div id="read-progress" class="progress-bar"></div>
		</div>
	</div>
</section>

@push('page-scripts')
<script>
(function(){
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

	const store = {
		get(k, d){ try { const v = localStorage.getItem(k); return v===null? d : JSON.parse(v); } catch(_) { return d; } },
		set(k, v){ try { localStorage.setItem(k, JSON.stringify(v)); } catch(_) {} }
	};

	let base = store.get('reader:font', 1.0);
	function applyFont(){ reader.style.fontSize = (base).toFixed(2)+'rem'; }

	let maxW = store.get('reader:maxWidth', 800);
	function applyWidth(){ if(wrap){ wrap.style.maxWidth = maxW + 'px'; } }

	let gap = 46; // match inline style column-gap
	let pageW = 0;
	let pageH = 0;
	let pageCount = 1;
	let currentPage = store.get('reader:page', 0);

	function clamp(x,min,max){ return Math.min(max, Math.max(min, x)); }
	function isContentAvailable(){ return !!pager; }

	function layoutPagination(){
		if(!isContentAvailable()) return;

		const toolbarH = toolbar ? toolbar.getBoundingClientRect().height : 64;
		const verticalMargins = 160;
		pageH = Math.max(340, Math.floor(window.innerHeight - toolbarH - verticalMargins));
		pager.style.height = pageH + 'px';

		pageW = Math.floor(pager.clientWidth);

		pager.style.columnWidth = pageW + 'px';
		pager.style.columnGap = gap + 'px';
		pager.style.columnFill = 'auto';

		const total = pager.scrollWidth;
		pageCount = Math.max(1, Math.round((total + gap) / (pageW + gap)));

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
		prevBtn.classList.toggle('disabled', prevBtn.disabled);
		nextBtn.classList.toggle('disabled', nextBtn.disabled);
	}

	function updateIndicator(){
		if(!indicator) return;
		if(pageCount <= 1) {
			indicator.textContent = '';
		} else {
			indicator.textContent = (currentPage + 1) + ' / ' + pageCount;
		}
	}

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

	applyFont();
	applyWidth();
	if(widthRange){ widthRange.value = maxW; }

	if(inc) inc.addEventListener('click', function(){
		base = clamp(base + 0.05, 0.75, 1.65);
		store.set('reader:font', base);
		applyFont();
		requestAnimationFrame(layoutPagination);
	});
	if(dec) dec.addEventListener('click', function(){
		base = clamp(base - 0.05, 0.75, 1.65);
		store.set('reader:font', base);
		applyFont();
		requestAnimationFrame(layoutPagination);
	});
	if(widthRange) widthRange.addEventListener('input', function(e){
		maxW = clamp(parseInt(e.target.value||800,10), 580, 1100);
		store.set('reader:maxWidth', maxW);
		applyWidth();
		requestAnimationFrame(layoutPagination);
	});

	if(prevBtn) prevBtn.addEventListener('click', ()=> scrollToPage(currentPage - 1));
	if(nextBtn) nextBtn.addEventListener('click', ()=> scrollToPage(currentPage + 1));

	window.addEventListener('keydown', (e)=>{
		if(!isContentAvailable()) return;
		if(e.key === 'ArrowLeft'){ e.preventDefault(); scrollToPage(currentPage - 1); }
		if(e.key === 'ArrowRight'){ e.preventDefault(); scrollToPage(currentPage + 1); }
		if(e.key === 'PageUp'){ e.preventDefault(); scrollToPage(currentPage - 1); }
		if(e.key === 'PageDown'){ e.preventDefault(); scrollToPage(currentPage + 1); }
	});

	let touchStartX = null;
	let touchStartY = null;
	const SWIPE_MIN = 42;
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
			if(Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > SWIPE_MIN){
				if(dx < 0) scrollToPage(currentPage + 1);
				else scrollToPage(currentPage - 1);
			}
			touchStartX = touchStartY = null;
		});
	}

	if(pager){
		pager.addEventListener('wheel', (e)=>{
			if(Math.abs(e.deltaY) > Math.abs(e.deltaX)){
				e.preventDefault();
				pager.scrollLeft += e.deltaY;
			}
		}, {passive:false});

		pager.addEventListener('scroll', throttle(()=>{
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

	window.addEventListener('resize', throttle(layoutPagination, 140));
	window.addEventListener('orientationchange', ()=> setTimeout(layoutPagination, 160));

	requestAnimationFrame(layoutPagination);
})();
</script>
@endpush

@include('include.footer')