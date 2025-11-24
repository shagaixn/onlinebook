@include('include.header')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/book-reader.css') }}">
<style>
/* Dynamic paging нэмэлт стилүүд */
.reader-pager-shell {
  position: relative;
  max-width: 100%;
}
.page-stack {
  position: relative;
}
.reader-page {
  background: var(--surface-bg, rgba(255,255,255,.85));
  border: 1px solid rgba(0,0,0,.05);
  border-radius: 1rem;
  padding: 1.4rem 1.6rem 1.6rem;
  margin: 0 0 1.2rem;
  box-shadow: 0 4px 14px -6px rgba(0,0,0,.07);
  scroll-margin-top: 6.5rem;
  transition: background .3s;
  break-inside: avoid;
}
html.dark .reader-page {
  background: rgba(30,41,59,.72);
  border-color: rgba(255,255,255,.07);
  box-shadow: 0 4px 14px -6px rgba(0,0,0,.6);
}
.reader-page.active {
  outline: 2px solid #6366f1;
  outline-offset: 2px;
}
.reader-page h1,h2,h3,h4 {
  break-inside: avoid;
}

.page-nav-group {
  display:flex;
  justify-content:space-between;
  gap:.75rem;
  margin-top:.75rem;
}
.page-flat-btn {
  background: linear-gradient(180deg,#f8fafc,#eef2f7);
  border:1px solid rgba(100,116,139,.35);
  color:#1e293b;
  padding:.55rem .9rem;
  border-radius:.7rem;
  font-size:.75rem;
  font-weight:600;
  letter-spacing:.02em;
  box-shadow:0 2px 4px rgba(0,0,0,.05);
  transition:.16s;
}
.page-flat-btn:hover {
  background:#fff;
  transform:translateY(-2px);
  box-shadow:0 4px 10px -3px rgba(0,0,0,.12);
}
.page-flat-btn:active {
  transform:translateY(0);
  box-shadow:0 2px 4px rgba(0,0,0,.12) inset;
}
.page-flat-btn:disabled {
  opacity:.4;
  cursor:not-allowed;
  transform:none;
  box-shadow:none;
}
html.dark .page-flat-btn {
  background:linear-gradient(180deg,#1f2937,#0f172a);
  border-color:rgba(71,85,105,.55);
  color:#f1f5f9;
}
html.dark .page-flat-btn:hover {
  background:#243447;
}

.progress-shell {
  background:linear-gradient(90deg,#e2e8f0,#cbd5e1);
}
html.dark .progress-shell {
  background:linear-gradient(90deg,#1e293b,#0f172a);
}

.progress-bar {
  height:100%;
  width:0%;
  background:linear-gradient(90deg,#3b82f6,#6366f1,#8b5cf6,#d946ef);
  background-size:300% 100%;
  animation:progressShift 6s linear infinite;
  transition:width .18s;
}
@keyframes progressShift {
  0% {background-position:0 50%}
  100% {background-position:100% 50%}
}

</style>
@endpush

<section class="reader-shell min-h-[calc(100vh-140px)]">
  <div class="max-w-6xl mx-auto px-5 py-6">
    <!-- Toolbar -->
    <div id="toolbar" class="reader-toolbar rounded-2xl shadow-lg flex items-center justify-between gap-4">
      <div class="min-w-0">
        <h1 class="reader-title truncate">{{ $book->title ?? 'Ном' }}</h1>
        <p class="reader-author truncate">{{ $book->author ?? ($book->authorModel->name ?? 'Зохиолч тодорхойгүй') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <label class="text-xs opacity-70">Фонт</label>
        <button id="font-dec" class="ui-btn ui-btn-sm">A-</button>
        <button id="font-inc" class="ui-btn ui-btn-sm">A+</button>
        <div class="hidden sm:flex items-center gap-2 pl-3 ml-2 border-l border-slate-200/70 dark:border-slate-700/60">
          <label class="text-xs opacity-70">Өргөн</label>
          <input id="width-range" type="range" min="640" max="1200" step="20" class="w-40 accent-blue-500 reader-range" />
        </div>
      </div>
    </div>

    <!-- Reader -->
    <div id="readerWrap" class="mx-auto mt-6 transition-[max-width] duration-300" style="max-width:840px;">
      <article id="reader" class="reader-surface relative">
        @php
          $content = trim($book->description ?? '') !== '' ? $book->description : null;
        @endphp

        @if($content)
          <!-- Raw content -->
          <script id="bookContent" type="application/json">
            @json(['id'=>$book->id ?? 0,'html'=>$content])
          </script>

          <div id="dynamicPager" class="reader-pager-shell">
            <div id="pageStack" class="page-stack"></div>
          </div>

          <div class="page-nav-group">
            <button id="prevPage" class="page-flat-btn">← Өмнөх</button>
            <span id="pageIndicator" class="text-xs font-semibold tracking-wide text-slate-600 dark:text-slate-300"></span>
            <button id="nextPage" class="page-flat-btn">Дараах →</button>
          </div>
        @else
          <h2 class="text-xl font-semibold mb-3">Танилцуулга алга</h2>
          <p class="text-slate-600 dark:text-slate-300">Контент нэмээгүй байна.</p>
        @endif
      </article>
    </div>
  </div>

  <!-- Progress -->
  <div class="fixed left-0 right-0 bottom-0 h-2 progress-shell">
    <div id="readProgress" class="progress-bar"></div>
  </div>
</section>

@push('page-scripts')
@if($content)
<script src="{{ asset('js/dynamic-book-pager.js') }}"></script>
@endif
@endpush

@include('include.footer')