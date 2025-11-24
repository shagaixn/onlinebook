
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ ($bookTitle ?? 'Ном') }} — {{ ($chapterTitle ?? 'Бүлэг') }}</title>

  <!-- Хэрэв танай төсөл Vite хэрэглэдэг бол дараах мөрүүдийг ашиглаарай
  @vite('resources/css/reader.css')
  @vite('resources/js/reader.js')
  -->

  <link rel="stylesheet" href="/resources/css/reader.css"><!-- Альтернатив: шууд линк -->
</head>
<body class="reader-root">
  <header class="topbar" id="readerTopbar">
    <div class="breadcrumbs">
      <a href="/" class="home">M book</a>
      <span> / </span>
      <span class="muted">{{ $bookTitle ?? 'Ном' }}</span>
      <span> / </span>
      <strong>{{ $chapterTitle ?? 'Бүлэг' }}</strong>
    </div>

    <div class="controls">
      <button id="modeToggle" class="chip" type="button" aria-label="Унших горим солих">
        Горим: <span data-mode-label>Scroll</span>
      </button>
    </div>

    <div class="progress" aria-label="Уншлагын явц">
      <div class="bar" id="readerProgress"></div>
      <div class="label" id="readerProgressLabel">0%</div>
    </div>
  </header>

  @php
    // $pages нь массив гэж үзэв. Объект эсвэл массив алинд нь ч нийцүүлэхээр нийтлэг түлхүүрүүдийг шалгана.
    $normalizedPages = [];
    if (!empty($pages)) {
      foreach ($pages as $i => $p) {
        $url = is_array($p)
          ? ($p['imageUrl'] ?? $p['url'] ?? $p['src'] ?? null)
          : (is_object($p) ? ($p->imageUrl ?? $p->url ?? $p->src ?? null) : null);
        if ($url) {
          $normalizedPages[] = ['index' => $i, 'url' => $url];
        }
      }
    }
    $total = count($normalizedPages);
  @endphp

  <main id="reader"
        class="reader-container mode-scroll"
        data-total="{{ $total }}"
        data-initial-mode="{{ request()->get('mode', 'scroll') }}"
        tabindex="-1"
        aria-label="Уншигч">
    <!-- Page mode үед ашиглагдах навигацийн товчууд -->
    <div class="page-mode" id="pageModeNav" hidden>
      <button class="nav left" id="goPrev" aria-label="Өмнөх хуудас">❮</button>
      <div class="page-counter" id="pageCounter">1 / {{ $total }}</div>
      <button class="nav right" id="goNext" aria-label="Дараах хуудас">❯</button>
    </div>

    @if ($total === 0)
      <div class="empty">Энэ бүлэгт агуулга алга байна.</div>
    @else
      @foreach ($normalizedPages as $p)
        <div class="page-wrap" data-index="{{ $p['index'] }}">
          <div class="skeleton page" aria-hidden="true"></div>
          <img
            class="page"
            data-src="{{ $p['url'] }}"
            alt="Page {{ $p['index'] + 1 }}"
            loading="lazy"
            decoding="async"
          />
        </div>
      @endforeach
    @endif

    <nav class="chapter-nav">
      @if (!empty($prevChapterUrl ?? null))
        <a class="btn" href="{{ $prevChapterUrl }}">Өмнөх бүлэг</a>
      @else
        <span></span>
      @endif

      @if (!empty($nextChapterUrl ?? null))
        <a class="btn primary" href="{{ $nextChapterUrl }}">Дараах бүлэг</a>
      @else
        <span></span>
      @endif
    </nav>
  </main>

  <script src="/resources/js/reader.js"></script><!-- Альтернатив: Vite ашиглаж болно -->
</body>
</html>