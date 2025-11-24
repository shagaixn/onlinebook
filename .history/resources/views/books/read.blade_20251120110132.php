@include('include.header')

<section class="relative overflow-hidden night-sky min-h-[calc(100vh-140px)]">
	<style>
		.page-viewport { height: 65vh; overflow: hidden; position: relative; }
		.page { position: absolute; inset: 0; padding: 1rem; overflow: hidden; }
		.page-enter { opacity: 0; transform: translateX(20px) rotateY(4deg); }
		.page-enter-active { opacity: 1; transform: translateX(0) rotateY(0deg); transition: transform .35s ease, opacity .35s ease; }
		.page-exit { opacity: 1; transform: translateX(0) rotateY(0deg); }
		.page-exit-active-left { opacity: 0; transform: translateX(-20px) rotateY(-4deg); transition: transform .35s ease, opacity .35s ease; }
		.page-exit-active-right { opacity: 0; transform: translateX(20px) rotateY(4deg); transition: transform .35s ease, opacity .35s ease; }
	</style>
	<!-- Animated background orbs -->
	<div aria-hidden="true" class="pointer-events-none absolute inset-0">
		<div class="absolute -top-24 -left-24 w-80 h-80 rounded-full blur-3xl bg-blue-400/20 animate-pulse"></div>
		<div class="absolute top-1/3 -right-24 w-96 h-96 rounded-full blur-3xl bg-fuchsia-400/10 animate-[pulse_5s_ease-in-out_infinite]"></div>
	</div>

	<div class="max-w-5xl mx-auto px-6 py-8">
		<!-- Toolbar -->
		<div class="backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/40 bg-white/80 dark:bg-slate-900/60 border border-white/40 dark:border-slate-800 rounded-2xl shadow-lg p-4 flex items-center justify-between gap-4">
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
				class="bg-white/80 dark:bg-slate-900/60 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/40
							 border border-white/40 dark:border-slate-800 shadow-xl rounded-2xl p-0">
				@php
					$content = trim($book->description ?? '') !== '' ? $book->description : null;
					$plain = $content ? strip_tags($content) : null;
				@endphp
				@if(!$plain)
					<div class="p-8 prose prose-slate dark:prose-invert">
						<h2 class="text-2xl font-semibold mb-4">Танилцуулга алга</h2>
						<p class="text-slate-600 dark:text-slate-300">Энэ номын дэлгэрэнгүй контент бүртгэлгүй байна. Админ хэсгээс тайлбар эсвэл контент нэмснээр уншигчид эндээс унших боломжтой болно.</p>
					</div>
				@else
					<!-- Controls overlay inside card -->
					<div class="flex items-center justify-between px-6 pt-5 pb-2">
						<div class="text-sm text-slate-500 dark:text-slate-400"><span id="pageLabel">1</span>/<span id="pageTotal">1</span></div>
						<div class="flex items-center gap-2">
							<button id="prevBtn" class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 disabled:opacity-40">Өмнөх</button>
							<button id="nextBtn" class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 disabled:opacity-40">Дараах</button>
						</div>
					</div>
					<div id="pageViewport" class="page-viewport">
						<div id="pageA" class="page prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl"></div>
						<div id="pageB" class="page prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl" style="display:none;"></div>
					</div>
				@endif
			</article>
		</div>

		<!-- Progress bar -->
		<div class="fixed left-0 right-0 bottom-0 h-1.5 bg-slate-200/70 dark:bg-slate-800">
			<div id="read-progress" class="h-full w-0 bg-gradient-to-r from-blue-500 via-indigo-500 to-fuchsia-500 transition-[width] duration-150"></div>
		</div>
	</div>
</section>

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
(function(){
	const root = document.documentElement;
	const wrap = document.getElementById('readerWrap');
	const reader = document.getElementById('reader');
	const progress = document.getElementById('read-progress');
	const inc = document.getElementById('font-inc');
	const dec = document.getElementById('font-dec');
	const widthRange = document.getElementById('width-range');
	const viewport = document.getElementById('pageViewport');
	const pageA = document.getElementById('pageA');
	const pageB = document.getElementById('pageB');
	const prevBtn = document.getElementById('prevBtn');
	const nextBtn = document.getElementById('nextBtn');
	const pageLabel = document.getElementById('pageLabel');
	const pageTotalEl = document.getElementById('pageTotal');

	// State persistence
	const store = {
		get(k, d){ try { const v = localStorage.getItem(k); return v===null? d : JSON.parse(v); } catch(_) { return d; } },
		set(k, v){ try { localStorage.setItem(k, JSON.stringify(v)); } catch(_) {} }
	};

	// Font size (rem-based on reader)
	let base = store.get('reader:font', 1.0); // multiplier
	function applyFont(){ reader.style.fontSize = (base).toFixed(2)+'rem'; }
	function clamp(x,min,max){ return Math.min(max, Math.max(min, x)); }

	// Width control
	let maxW = store.get('reader:maxWidth', 800);
	function applyWidth(){ if(wrap){ wrap.style.maxWidth = maxW + 'px'; } }

	// Pagination state
	let pages = [];
	let pageIndex = 0;

	// Build pages by measuring content height in a hidden measurer
	function buildPages(text){
		if(!viewport || !reader) return [];
		// Prepare measurer
		const measurer = document.createElement('div');
		measurer.style.position = 'absolute';
		measurer.style.visibility = 'hidden';
		measurer.style.pointerEvents = 'none';
		measurer.style.inset = '0';
		measurer.style.padding = '1rem';
		measurer.style.width = viewport.clientWidth + 'px';
		measurer.className = 'prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl';
		reader.appendChild(measurer);

		const maxH = viewport.clientHeight - 2; // a bit of slack
		const paras = String(text).split(/\n{2,}|\r?\n/).filter(Boolean);

		const newPages = [];
		let current = '';
		function commit(){ if(current.trim().length){ newPages.push(current); current=''; } }

		for(const p of paras){
			const words = p.split(/\s+/);
			let line = '';
			for(let i=0;i<words.length;i++){
				const candidate = (current ? current + '\n\n' : '') + line + (line? ' ' : '') + words[i];
				measurer.innerText = candidate;
				if(measurer.scrollHeight > maxH){
					if(line===''){
						// single word too big; force break
						commit();
						current = words[i];
					} else {
						// commit current page, start new with this word
						commit();
						current = words[i];
					}
					measurer.innerText = current;
				} else {
					line = line ? (line + ' ' + words[i]) : words[i];
					// Apply to current without committing yet
					current = current ? (current + '\n\n' + line) : line;
				}
			}
			// paragraph finished; already appended inside loop
		}
		commit();

		reader.removeChild(measurer);
		return newPages.length ? newPages : [''];
	}

	function renderPage(target, htmlText){
		target.style.display = '';
		target.classList.remove('page-enter','page-enter-active');
		target.innerText = htmlText; // keep as plain text for safety
		// trigger enter animation
		requestAnimationFrame(()=>{
			target.classList.add('page-enter');
			requestAnimationFrame(()=>{
				target.classList.add('page-enter-active');
			});
		});
	}

	function swapPages(nextIndex, dir){
		if(!viewport) return;
		const incoming = pageA.style.display==='none' ? pageA : pageB;
		const outgoing = pageA.style.display==='none' ? pageB : pageA;
		renderPage(incoming, pages[nextIndex]);
		// exit anim on outgoing
		outgoing.classList.remove('page-exit-active-left','page-exit-active-right');
		outgoing.classList.add('page-exit');
		requestAnimationFrame(()=>{
			outgoing.classList.add(dir>0? 'page-exit-active-left' : 'page-exit-active-right');
			setTimeout(()=>{ outgoing.style.display='none'; }, 360);
		});
	}

	function updateUI(){
		if(pageLabel) pageLabel.textContent = String(pageIndex+1);
		if(pageTotalEl) pageTotalEl.textContent = String(pages.length);
		if(prevBtn) prevBtn.disabled = pageIndex<=0;
		if(nextBtn) nextBtn.disabled = pageIndex>=pages.length-1;
		// progress as pages
		if(progress){
			const ratio = pages.length>1 ? pageIndex/(pages.length-1) : 0;
			progress.style.width = (ratio*100).toFixed(2)+'%';
		}
	}

	function goTo(index, dir){
		if(index<0 || index>=pages.length) return;
		swapPages(index, dir);
		pageIndex = index;
		updateUI();
	}

	function reflow(text){
		if(!viewport) return;
		const keepRatio = pages.length? (pageIndex/(pages.length-1||1)) : 0;
		pages = buildPages(text);
		let newIndex = Math.round(keepRatio * (pages.length-1));
		newIndex = clamp(newIndex, 0, pages.length-1);
		// initial show: display pageA, hide pageB
		pageA.style.display='';
		pageB.style.display='none';
		pageA.className = 'page prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl';
		pageB.className = 'page prose prose-slate dark:prose-invert leading-relaxed text-base sm:text-lg md:text-xl';
		pageA.innerText = pages[newIndex];
		pageIndex = newIndex;
		updateUI();
	}

	// Inline content from server
	const SRC = (function(){
		try { return JSON.parse(document.getElementById('content-json').textContent); } catch(_) { return null; }
	})();

	// Init controls
	applyFont();
	applyWidth();
	if(widthRange){ widthRange.value = maxW; }
	if(viewport && SRC){ reflow(SRC); }

	// Events
	if(inc) inc.addEventListener('click', function(){ base = clamp(base + 0.05, 0.7, 1.6); store.set('reader:font', base); applyFont(); });
	if(dec) dec.addEventListener('click', function(){ base = clamp(base - 0.05, 0.7, 1.6); store.set('reader:font', base); applyFont(); if(viewport && SRC){ reflow(SRC); } });
	if(widthRange) widthRange.addEventListener('input', function(e){ maxW = clamp(parseInt(e.target.value||800,10), 580, 1200); store.set('reader:maxWidth', maxW); applyWidth(); if(viewport && SRC){ reflow(SRC); } });
	if(prevBtn) prevBtn.addEventListener('click', function(){ goTo(pageIndex-1, -1); });
	if(nextBtn) nextBtn.addEventListener('click', function(){ goTo(pageIndex+1, +1); });
	document.addEventListener('keydown', function(e){ if(e.key==='ArrowLeft') goTo(pageIndex-1,-1); if(e.key==='ArrowRight') goTo(pageIndex+1, +1); });
})();
});
</script>
@endpush

@if(isset($plain) && $plain)
	<script type="application/json" id="content-json">@json($plain)</script>
@endif

@include('include.footer')