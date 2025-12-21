@include('include.header')
@php
  $plans = $plans ?? [
    (object)['id'=>1,'name'=>'Basic','price'=>0,'interval'=>'Сар бүр','features'=>['Үндсэн номууд','Хязгаарлагдмал уншлага']],
    (object)['id'=>2,'name'=>'Pro','price'=>9900,'interval'=>'Сар бүр','features'=>['Бүх ном','Offline уншлага','Wishlist синк']],
    (object)['id'=>3,'name'=>'Premium','price'=>19900,'interval'=>'Жил бүр','features'=>['Pro бүх','Эртийн нэвтрэх','Priority support']]
  ];
@endphp

<main class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-4 py-12">
  <h1 class="text-3xl sm:text-4xl font-extrabold text-center mb-2">Subscription төлөвлөгөө</h1>
  <p class="text-center text-sm text-gray-600 dark:text-gray-400 mb-10">Өөрт тохирох багцыг сонгоно уу.</p>

  <section aria-label="Subscription төлөвлөгөө" class="max-w-6xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse($plans as $plan)
      <article class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition p-6 flex flex-col">
        <header class="mb-4">
          <h2 class="text-xl font-bold">{{ $plan->name }}</h2>
          <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm">{{ $plan->interval }}</p>
        </header>
        <div class="text-3xl font-extrabold mb-4">
          @if($plan->price === 0)
            <span class="text-green-600 dark:text-green-400">Free</span>
          @else
            <span class="text-blue-600 dark:text-blue-300">{{ number_format($plan->price) }}₮</span>
            <span class="text-base font-medium text-gray-500 dark:text-gray-400">/{{ $plan->interval }}</span>
          @endif
        </div>
        <ul class="text-sm flex-1 space-y-2 mb-6">
          @foreach($plan->features as $f)
            <li class="flex items-start gap-2">
              <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
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
                {{ $plan->price === 0 ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white' }}
                focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500 transition">
              Сонгох
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
             class="mt-auto w-full inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 transition"
             aria-label="Нэвтрэх">
            Нэвтэрч сонгох
            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </a>
        @endauth
      </article>
    @empty
      <div class="col-span-3 text-center text-gray-500 dark:text-gray-400">
        Төлөвлөгөө одоогоор байхгүй байна.
      </div>
    @endforelse
  </section>

  <div class="max-w-3xl mx-auto mt-12 text-center text-xs text-gray-500 dark:text-gray-400">
    Санал хүсэлт байвал холбогдоно уу.
  </div>
</main>

@include('include.footer')
