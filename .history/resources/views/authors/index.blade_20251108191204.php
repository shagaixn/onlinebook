@section('title', $author->name . ' — Author')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($author->bio), 160))

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
  <div class="flex items-center gap-6">
    <img src="{{ $author->avatar ? asset('storage/'.$author->avatar) : asset('images/default-avatar.png') }}"
         alt="{{ $author->name }}'s avatar" class="w-28 h-28 rounded-full object-cover shadow">
    <div>
      <h1 class="text-3xl font-bold">{{ $author->name }}</h1>
      <p class="text-sm text-gray-600 mt-2">{!! nl2br(e(Str::limit($author->bio, 200))) !!}</p>
      <div class="mt-3 flex gap-3">
        @if($author->website)<a href="{{ $author->website }}" target="_blank" class="text-sm underline">Website</a>@endif
        @if(!empty($author->social['twitter']))<a href="{{ $author->social['twitter'] }}" target="_blank" class="text-sm">@twitter</a>@endif
      </div>
    </div>
  </div>

  <section class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Бүтээлүүд</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($author->books as $book)
        <div class="bg-white p-4 rounded-lg shadow">
          <img src="{{ $book->cover_image ? asset('storage/'.$book->cover_image) : asset('images/no-cover.png') }}" alt="{{ $book->title }}" class="w-full h-40 object-cover rounded">
          <h3 class="mt-3 font-semibold">{{ $book->title }}</h3>
          <p class="text-sm text-gray-500">{{ $book->published_date ? $book->published_date->format('Y') : '' }}</p>
          <a href="{{ route('books.show', $book->slug) }}" class="mt-2 inline-block text-sm underline">Дэлгэрэнгүй</a>
        </div>
      @empty
        <p>Энэ авторын бүтээл ороогүй байна.</p>
      @endforelse
    </div>
  </section>

  {{-- JSON-LD schema --}}
  @push('head')
  <script type="application/ld+json">
  {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Person',
      'name' => $author->name,
      'url' => url()->current(),
      'image' => $author->avatar ? asset('storage/'.$author->avatar) : asset('images/default-avatar.png'),
      'description' => strip_tags($author->bio),
  ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
  </script>
  @endpush
</div>
@endsection