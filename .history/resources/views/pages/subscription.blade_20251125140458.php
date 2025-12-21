@include('include.header')
@php
  $plans = $plans ?? [
    (object)['id'=>1,'name'=>'Basic','price'=>0,'interval'=>'Сар бүр','features'=>['Үндсэн номууд','Хязгаарлагдмал уншлага']],
    (object)['id'=>2,'name'=>'Pro','price'=>9900,'interval'=>'Сар бүр','features'=>['Бүх ном','Offline уншлага','Wishlist синк']],
    (object)['id'=>3,'name'=>'Premium','price'=>19900,'interval'=>'Жил бүр','features'=>['Pro бүх','Эртийн нэвтрэх','Priority support']]
  ];
@endphp

<main class="night-sky min-h-[100svh] max-w-9xl mx-auto px-4 py-15">
  <h1 class="text-3xl sm:text-4xl font-extrabold text-center mb-2 text-white">
    <span class="text-gradient drop-shadow-sm">Subscription</span> төлөвлөгөө
  </h1>
  <p class="text-center text-sm text-slate-300 mb-10">Өөрт тохирох багцыг сонгоно уу.</p>

  <section aria-label="Subscription төлөвлөгөө" class="max-w-6xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($plans as $plan)
      <article class="group rounded-2xl bg-white/5 backdrop-blur border border-white/10 overflow-hidden shadow-sm hover:shadow-lg hover:border-cyan-400/40 transition p-6 flex flex-col">
        <header class="mb-4">
          <h2 class="text-lg font-semibold text-white">{{ $plan->name }}</h2>
          <p class="mt-1 text-xs text-slate-400">{{ $plan->interval }}</p>
        </header>
        <div class="text-2xl font-bold mb-4 text-white">
          @if($plan->price === 0)
            <span class="text-green-400">Free</span>
          @else
            <span class="text-cyan-300">{{ number_format($plan->price) }}₮</span>
            <span class="text-xs font-medium text-slate-400">/{{ $plan->interval }}</span>
          @endif
        </div>
        <ul class="text-xs flex-1 space-y-2 mb-6">
          @foreach($plan->features as $f)
            <li class="flex items-start gap-2 text-slate-200">
              <svg class="w-4 h-4 text-cyan-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              <span>{{ $f }}</span>
            </li>
          @endforeach
        </ul>
        @auth
          <form method="POST" action="{{ route('subscription.select') }}" class="mt-auto">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <button
              type="submit"
              class="w-full inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold
                {{ $plan->price === 0 ? 'bg-green-600 hover:bg-green-700' : 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700' }}
                text-white shadow-lg shadow-indigo-700/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 transition">
              Сонгох
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
             class="mt-auto w-full inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 transition shadow-lg shadow-indigo-700/30"
             aria-label="Нэвтрэх">
            Нэвтэрч сонгох
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        @endauth
      </article>
    @empty
      <div class="col-span-3 text-center text-slate-400">
        Төлөвлөгөө одоогоор байхгүй байна.
      </div>
    @endforelse
  </section>

  <div class="max-w-3xl mx-auto mt-12 text-center text-[11px] text-slate-400">
    Санал хүсэлт байвал холбогдоно уу.
  </div>
</main>

@include('include.footer')
