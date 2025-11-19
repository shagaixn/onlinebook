@include('include.header')
<section class=" night-sky relative min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="text-center">
        <h1 class="text-5xl font-bold mb-6 text-white drop-shadow-lg">Манай Номын Сан руу тавтай морил!</h1>
        <p class="text-lg text-white mb-8 drop-shadow-md">Шинэ ном хайх эсвэл дуртай зохиолчийнхоо бүтээлүүдийг үзэх</p>
        <a href="{{ route('books.') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transition-shadow">
            Ном үзэх
        </a>
    </div>


<main class=" max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-4xl font-bold mb-8 text-blue-700 text-center">Номуудын жагсаалт</h1>
    <div class="flex flex-wrap justify-center gap-8 group">
        @forelse ($books as $book)
            <div class="relative w-48 h-72 rounded-2xl overflow-hidden shadow-xl bg-white cursor-pointer group/book transition-all duration-300">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover transition-all duration-500 group-hover:blur-sm group-hover:brightness-75 group-hover/book:!blur-none group-hover/book:!brightness-100" />
                @else
                    <div class="w-full h-full bg-blue-100 flex items-center justify-center rounded-2xl text-gray-400 text-xs">
                        No Image
                    </div>
                @endif
                <div class="absolute inset-0 flex flex-col justify-end opacity-0 group-hover/book:opacity-100 transition duration-300 bg-gradient-to-t from-[#0f0c24]/90 via-transparent to-transparent p-4">
                    <h2 class="text-xl font-bold mb-2 text-white">{{ $book->title }}</h2>
                    <p class="text-white mb-2 line-clamp-2 text-sm">Зохиолч: {{ $book->author }}</p>
                    <a href="{{ route('books.show', $book->id) }}"
                       class="bg-[#5f31ff] hover:bg-[#4223b6] text-white px-4 py-1.5 rounded-lg font-semibold text-xs w-fit">Дэлгэрэнгүй</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400">
                Номын мэдээлэл олдсонгүй.
            </div>
        @endforelse
    </div>
</main>
</section>
@include('include.footer')