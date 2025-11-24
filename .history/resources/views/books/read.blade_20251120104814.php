@include('include.header')

<section class="relative overflow-hidden night-sky min-h-[calc(100vh-140px)]">
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
				class="prose prose-slate dark:prose-invert prose-headings:scroll-mt-20 leading-relaxed text-base sm:text-lg md:text-xl
							 bg-white/80 dark:bg-slate-900/60 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/40
							 border border-white/40 dark:border-slate-800 shadow-xl rounded-2xl p-6 sm:p-8">
				@php
					$content = trim($book->description ?? '') !== '' ? $book->description : null;
				@endphp
				@if($content)
					{!! nl2br(e($content)) !!}
				@else
					<h2 class="text-2xl font-semibold mb-4">Танилцуулга алга</h2>
					<p class="text-slate-600 dark:text-slate-300">Энэ номын дэлгэрэнгүй контент бүртгэлгүй байна. Админ хэсгээс тайлбар эсвэл контент нэмснээр уншигчид эндээс унших боломжтой болно.</p>
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
(function(){
	const root = document.documentElement;
	const wrap = document.getElementById('readerWrap');
	const reader = document.getElementById('reader');
	const progress = document.getElementById('read-progress');
	const inc = document.getElementById('font-inc');
	const dec = document.getElementById('font-dec');
	const widthRange = document.getElementById('width-range');

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

	// Progress
	function updateProgress(){
		const rect = reader.getBoundingClientRect();
		const scrollTop = window.scrollY || document.documentElement.scrollTop;
		const docH = document.documentElement.scrollHeight - window.innerHeight;
		const ratio = docH>0 ? clamp((scrollTop)/docH, 0, 1) : 0;
		if(progress) progress.style.width = (ratio*100).toFixed(2)+'%';
	}

	// Init controls
	applyFont();
	applyWidth();
	if(widthRange){ widthRange.value = maxW; }
	updateProgress();

	// Events
	if(inc) inc.addEventListener('click', function(){ base = clamp(base + 0.05, 0.7, 1.6); store.set('reader:font', base); applyFont(); });
	if(dec) dec.addEventListener('click', function(){ base = clamp(base - 0.05, 0.7, 1.6); store.set('reader:font', base); applyFont(); });
	if(widthRange) widthRange.addEventListener('input', function(e){ maxW = clamp(parseInt(e.target.value||800,10), 580, 1200); store.set('reader:maxWidth', maxW); applyWidth(); });
	window.addEventListener('scroll', updateProgress, {passive:true});
	window.addEventListener('resize', updateProgress);
})();
</script>
@endpush

@include('include.footer')