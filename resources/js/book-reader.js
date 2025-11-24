(function(){
	'use strict';

	const contentEl = document.getElementById('bookContent');
	const reader = document.getElementById('reader');
	const container = document.getElementById('pageContainer');
	const prevBtn = document.getElementById('prevPage');
	const nextBtn = document.getElementById('nextPage');
	const indicator = document.getElementById('pageIndicator');
	const progressBar = document.getElementById('readProgress');
	const widthRange = document.getElementById('width-range');
	const inc = document.getElementById('font-inc');
	const dec = document.getElementById('font-dec');
	const themeToggle = document.getElementById('theme-toggle');
	const openTocBtn = document.getElementById('open-toc');
	const tocNav = document.getElementById('toc');
	const tocDrawer = document.getElementById('tocDrawer');
	const bookmarkList = document.getElementById('bookmarkList');
	const bookmarkItems = document.getElementById('bookmarkItems');
	const mobileBookmark = document.getElementById('mobileBookmark');
	const mobileToc = document.getElementById('mobileToc');
	const searchInput = document.getElementById('search-input');
	const searchClear = document.getElementById('search-clear');
	const wrap = document.getElementById('readerWrap');

	if(!reader || !container) return;

	// Persist helpers
	const store = {
		get(k, d){ try { const v = localStorage.getItem(k); return v===null? d : JSON.parse(v); } catch(_) { return d; } },
		set(k, v){ try { localStorage.setItem(k, JSON.stringify(v)); } catch(_) {} },
		del(k){ try { localStorage.removeItem(k); } catch(_) {} }
	};

	// Parse content
	let bookData = null;
	try { bookData = JSON.parse(contentEl?.textContent || '{}'); } catch(_) {}
	const bookId = bookData?.id || 0;
	const rawContent = bookData?.raw || '';

	// Config
	const PAGE_TARGET_CHARS = 2200; // approximate chars per page (will refine)
	const MIN_PAGE = 1;
	const MAX_FONT = 1.6;
	const MIN_FONT = 0.7;

	// State
	let pages = [];
	let currentPage = store.get(`book:${bookId}:page`, 0);
	let fontSize = store.get('reader:font', 1.0);
	let maxW = store.get('reader:maxWidth', 840);
	let bookmarks = store.get(`book:${bookId}:bookmarks`, []);
	let searchMatches = [];
	let activeSearchIndex = -1;

	// Utils
	const clamp = (x, a, b)=> Math.min(b, Math.max(a, x));
	const debounce = (fn, wait=250)=>{
		let t;
		return (...args)=>{
			clearTimeout(t);
			t = setTimeout(()=> fn(...args), wait);
		};
	};

	function applyFont(){
		reader.style.fontSize = fontSize.toFixed(2)+'rem';
	}
	function applyWidth() {
		if(wrap) wrap.style.maxWidth = maxW + 'px';
	}

	// Split content -> pages heuristically on paragraph boundaries
	function buildPages(raw){
		const paragraphs = raw
			.replace(/\r\n/g, '\n')
			.split(/\n{2,}/)
			.map(p => p.trim())
			.filter(Boolean);

		const tmp = [];
		let buffer = '';

		for(const p of paragraphs){
			// Add paragraph with double newline for readability
			const candidate = buffer.length ? buffer + '\n\n' + p : p;
			if(candidate.length >= PAGE_TARGET_CHARS * 1.15){ // big paragraph itself
				if(buffer) { tmp.push(buffer); buffer=''; }
				tmp.push(p);
			}else if(candidate.length > PAGE_TARGET_CHARS){
				tmp.push(candidate);
				buffer = '';
			}else{
				buffer = candidate;
				if(buffer.length >= PAGE_TARGET_CHARS){
					tmp.push(buffer);
					buffer='';
				}
			}
		}
		if(buffer) tmp.push(buffer);
		if(tmp.length === 0) tmp.push(raw);

		return tmp;
	}

	function renderPages(){
		container.innerHTML = '';
		pages.forEach((pg, i)=>{
			const pageEl = document.createElement('section');
			pageEl.className = 'page prose prose-slate dark:prose-invert max-w-none';
			pageEl.dataset.index = i;
			pageEl.tabIndex = 0;
			pageEl.innerHTML = sanitizeAndFormat(pg);
			container.appendChild(pageEl);
		});
		highlightSearch(); // restore search highlight if any
		generateTOC();
		goToPage(clamp(currentPage, 0, pages.length-1), false);
		updateIndicator();
		setupIntersection();
	}

	// Basic sanitization/format: convert plain text newlines to <p> or <br>
	function sanitizeAndFormat(txt){
		// Escape
		const esc = (s)=> s
			.replace(/&/g,'&amp;')
			.replace(/</g,'&lt;')
			.replace(/>/g,'&gt;');

		// If content already has markup (heuristic: presence of <), don't escape fully.
		if(/<\/?[a-z][\s\S]*>/i.test(txt)){
			// Convert single newlines inside paragraphs to <br>
			return txt.replace(/(?<!\n)\n(?!\n)/g,'<br>');
		}
		const paraBlocks = txt.split(/\n{2,}/).map(b=>{
			const lines = b.split(/\n/).map(l=> esc(l));
			return `<p>${lines.join('<br>')}</p>`;
		});
		return paraBlocks.join('\n');
	}

	function goToPage(i, smooth=true){
		currentPage = clamp(i, 0, pages.length-1);
		store.set(`book:${bookId}:page`, currentPage);
		const pageEl = container.querySelector(`.page[data-index="${currentPage}"]`);
		if(pageEl){
			pageEl.scrollIntoView({behavior: smooth? 'smooth':'auto', block:'start'});
			pageEl.focus({preventScroll:true});
		}
		updateIndicator();
		updateNav();
		updateProgressByRatio(currentPage / Math.max(1, pages.length-1));
	}

	function updateIndicator(){
		if(indicator){
			indicator.textContent = pages.length
				? `${currentPage+1} / ${pages.length}`
				: '';
		}
	}

	function updateNav(){
		if(!prevBtn || !nextBtn) return;
		prevBtn.disabled = currentPage <= 0;
		nextBtn.disabled = currentPage >= pages.length -1;
	}

	function updateProgressByRatio(r){
		if(progressBar){
			progressBar.style.width = (r*100).toFixed(2)+'%';
		}
	}

	// IntersectionObserver for dynamic progress (smooth when manual scroll)
	let observer = null;
	function setupIntersection(){
		if(observer) observer.disconnect();
		const items = [...container.querySelectorAll('.page')];
		if(!items.length) return;

		observer = new IntersectionObserver(entries=>{
			// Find the topmost fully visible page
			let visible = entries
				.filter(e => e.isIntersecting)
				.sort((a,b)=> a.target.dataset.index - b.target.dataset.index);
			if(visible.length){
				const idx = parseInt(visible[0].target.dataset.index,10);
				currentPage = idx;
				store.set(`book:${bookId}:page`, currentPage);
				updateIndicator();
				updateNav();
				updateProgressByRatio(currentPage / Math.max(1, pages.length-1));
			}
		},{
			root: container,
			threshold: 0.6
		});
		items.forEach(i => observer.observe(i));
	}

	// TOC generation (from headings inside pages)
	function generateTOC(){
		if(!tocNav) return;
		tocNav.innerHTML = '';
		const headings = container.querySelectorAll('.page h1, .page h2, .page h3, .page h4');
		if(!headings.length){
			tocNav.innerHTML = '<p class="text-slate-400 dark:text-slate-500 text-xs">Гарчиг алга</p>';
			return;
		}
		headings.forEach((h, idx)=>{
			const id = h.id || `h_${idx}_${Date.now()}`;
			h.id = id;
			const level = parseInt(h.tagName.substring(1),10);
			const a = document.createElement('a');
			a.href = '#'+id;
			a.textContent = h.textContent.trim();
			a.className = `block rounded px-2 py-1 hover:bg-blue-50 dark:hover:bg-slate-800 transition text-slate-700 dark:text-slate-200 text-xs ${
				level===1? 'font-semibold':
				level===2? 'ml-2':
				level===3? 'ml-4':
				'ml-6'
			}`;
			a.addEventListener('click', e=>{
				e.preventDefault();
				document.getElementById(id)?.scrollIntoView({behavior:'smooth', block:'start'});
			});
			tocNav.appendChild(a);
		});
	}

	// Toggle TOC drawer (mobile / manual)
	function toggleToc(vis){
		if(!tocDrawer) return;
		const show = vis !== undefined ? !!vis : tocDrawer.classList.contains('hidden');
		tocDrawer.classList.toggle('hidden', !show);
		openTocBtn?.setAttribute('aria-expanded', String(show));
	}

	// Theme toggle (assumes dark class on html)
	function toggleTheme(){
		const html = document.documentElement;
		html.classList.toggle('dark');
		store.set('pref:dark', html.classList.contains('dark'));
	}

	function restoreTheme(){
		const prefDark = store.get('pref:dark', null);
		if(prefDark === true){
			document.documentElement.classList.add('dark');
		} else if(prefDark === false){
			document.documentElement.classList.remove('dark');
		}
	}

	// Bookmarks: store page + optional snippet
	function addBookmark(){
		const sel = window.getSelection();
		let snippet = '';
		if(sel && sel.toString().trim()){
			snippet = sel.toString().trim().slice(0, 140) + (sel.toString().length > 140 ? '…' : '');
		} else {
			// If no selection, take first 100 chars of page
			const pgHtml = container.querySelector(`.page[data-index="${currentPage}"]`)?.innerText || '';
			snippet = pgHtml.trim().slice(0, 100) + (pgHtml.length > 100 ? '…' : '');
		}
		const bm = { page: currentPage, snippet, ts: Date.now() };
		bookmarks.push(bm);
		store.set(`book:${bookId}:bookmarks`, bookmarks);
		renderBookmarks();
	}

	function renderBookmarks(){
		if(!bookmarkItems || !bookmarkList) return;
		if(!bookmarks.length){
			bookmarkList.classList.add('hidden');
			return;
		}
		bookmarkList.classList.remove('hidden');
		bookmarkItems.innerHTML = '';
		bookmarks
			.sort((a,b)=> b.ts - a.ts)
			.forEach((b, i)=>{
				const li = document.createElement('li');
				const date = new Date(b.ts).toLocaleString();
				li.innerHTML = `
					<div class="group flex items-start gap-2 p-2 rounded hover:bg-slate-50 dark:hover:bg-slate-800/60">
						<button class="text-blue-600 dark:text-blue-400 text-xs underline" data-go="${b.page}">Page ${b.page+1}</button>
						<span class="flex-1 text-slate-600 dark:text-slate-300 text-xs">${escapeHtml(b.snippet)}</span>
						<button class="opacity-60 group-hover:opacity-100 text-xs text-rose-500" data-del="${i}">✕</button>
						<span class="text-[10px] text-slate-400 ml-2">${date}</span>
					</div>
				`;
				bookmarkItems.appendChild(li);
			});
		bookmarkItems.querySelectorAll('[data-go]').forEach(btn=>{
			btn.addEventListener('click', ()=>{
				goToPage(parseInt(btn.dataset.go,10));
			});
		});
		bookmarkItems.querySelectorAll('[data-del]').forEach(btn=>{
			btn.addEventListener('click', ()=>{
				const idx = parseInt(btn.dataset.del,10);
				bookmarks.splice(idx,1);
				store.set(`book:${bookId}:bookmarks`, bookmarks);
				renderBookmarks();
			});
		});
	}
	function escapeHtml(s){
		return s.replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
	}

	// Search + highlight
	function clearHighlights(){
		searchMatches.forEach(m=>{
			const parent = m.el.parentNode;
			if(parent){
				parent.replaceChild(document.createTextNode(m.text), m.el);
			}
		});
		searchMatches = [];
		activeSearchIndex = -1;
	}

	function highlightSearch(){
		if(!searchMatches.length) return;
		searchMatches.forEach(m=>{
			m.el.classList.add('bg-yellow-300','dark:bg-yellow-600','text-black','dark:text-black');
		});
	}

	function performSearch(term){
		clearHighlights();
		if(!term || term.trim()==='') {
			searchClear.classList.add('hidden');
			return;
		}
		searchClear.classList.remove('hidden');

		const regex = new RegExp(term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
		const walker = document.createTreeWalker(container, NodeFilter.SHOW_TEXT, null);
		while(walker.nextNode()){
			const node = walker.currentNode;
			if(!node.nodeValue.trim()) continue;
			const original = node.nodeValue;
			let match;
			let lastIndex = 0;
			const frag = document.createDocumentFragment();
			let found = false;
			while((match = regex.exec(original)) !== null){
				found = true;
				const before = original.slice(lastIndex, match.index);
				if(before) frag.appendChild(document.createTextNode(before));
				const span = document.createElement('mark');
				span.textContent = match[0];
				frag.appendChild(span);
				searchMatches.push({ el: span, text: match[0], idx: searchMatches.length });
				lastIndex = match.index + match[0].length;
			}
			if(found){
				const after = original.slice(lastIndex);
				if(after) frag.appendChild(document.createTextNode(after));
				node.parentNode.replaceChild(frag, node);
			}
		}
		if(searchMatches.length){
			activeSearchIndex = 0;
			focusSearch(activeSearchIndex);
		}
	}

	function focusSearch(i){
		if(i < 0 || i >= searchMatches.length) return;
		searchMatches.forEach((m, idx)=>{
			m.el.classList.toggle('ring-2', idx===i);
			m.el.classList.toggle('ring-blue-500', idx===i);
		});
		searchMatches[i].el.scrollIntoView({behavior:'smooth', block:'center'});
	}

	function nextSearch(){
		if(!searchMatches.length) return;
		activeSearchIndex = (activeSearchIndex + 1) % searchMatches.length;
		focusSearch(activeSearchIndex);
	}

	function prevSearch(){
		if(!searchMatches.length) return;
		activeSearchIndex = (activeSearchIndex - 1 + searchMatches.length) % searchMatches.length;
		focusSearch(activeSearchIndex);
	}

	// Events
	prevBtn?.addEventListener('click', ()=> goToPage(currentPage - 1));
	nextBtn?.addEventListener('click', ()=> goToPage(currentPage + 1));
	inc?.addEventListener('click', ()=>{
		fontSize = clamp(fontSize + 0.05, MIN_FONT, MAX_FONT);
		store.set('reader:font', fontSize);
		applyFont();
		// Re-render for line reflow if needed (optional)
	});
	dec?.addEventListener('click', ()=>{
		fontSize = clamp(fontSize - 0.05, MIN_FONT, MAX_FONT);
		store.set('reader:font', fontSize);
		applyFont();
	});
	widthRange?.addEventListener('input', e=>{
		maxW = clamp(parseInt(e.target.value||840,10), 580, 1400);
		store.set('reader:maxWidth', maxW);
		applyWidth();
	});

	themeToggle?.addEventListener('click', toggleTheme);
	openTocBtn?.addEventListener('click', ()=> toggleToc());

	mobileToc?.addEventListener('click', ()=> toggleToc());
	mobileBookmark?.addEventListener('click', addBookmark);

	// Keyboard
	window.addEventListener('keydown', (e)=>{
		if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
		if(e.key === 'ArrowLeft'){ goToPage(currentPage - 1); }
		if(e.key === 'ArrowRight'){ goToPage(currentPage + 1); }
		if(e.key === 'b' && e.metaKey){ addBookmark(); }
		if(e.key === 'f' && e.metaKey){ e.preventDefault(); searchInput?.focus(); }
		if(e.key === 'Enter' && searchMatches.length && document.activeElement === searchInput){
			e.preventDefault(); nextSearch();
		}
		if(e.key === 'Escape' && searchMatches.length){
			clearHighlights();
			searchInput.value = '';
			searchClear.classList.add('hidden');
		}
	});

	// Search events
	searchInput?.addEventListener('input', debounce(()=>{
		const term = searchInput.value.trim();
		clearHighlights();
		performSearch(term);
	}, 300));

	searchClear?.addEventListener('click', ()=>{
		searchInput.value = '';
		clearHighlights();
		searchClear.classList.add('hidden');
	});

	// Navigate search results with shift+enter or alt+arrow
	window.addEventListener('keydown', (e)=>{
		if(!searchMatches.length) return;
		if(e.key === 'Enter' && e.shiftKey){
			e.preventDefault(); prevSearch();
		}
		if(e.altKey && e.key === 'ArrowDown'){
			e.preventDefault(); nextSearch();
		}
		if(e.altKey && e.key === 'ArrowUp'){
			e.preventDefault(); prevSearch();
		}
	});

	// Initialization
	restoreTheme();
	applyFont();
	applyWidth();
	renderBookmarks();

	pages = buildPages(rawContent);
	renderPages();

	// Rebuild pages on window resize only if width changed drastically (optional)
	let lastW = window.innerWidth;
	window.addEventListener('resize', debounce(()=>{
		if(Math.abs(window.innerWidth - lastW) > 160){
			lastW = window.innerWidth;
			// Not rebuilding pages to preserve highlights/bookmarks; could rebuild if you want adaptive page length.
			setupIntersection();
		}
	}, 400));

})();