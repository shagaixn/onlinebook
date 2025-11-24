@include('include.header')
<section class=" night-sky relative min-h-[80vh] flex items-center justify-center px-4 py-12">
   


<main class=" max-w-9xl mx-auto py-15 px-4">
    <h1 class="text-4xl font-bold mb-2 text-blue-700 text-center">Номуудын жагсаалт</h1>
    @if(request('q'))
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-6">Хайлтын үг: <span class="font-semibold">"{{ request('q') }}"</span></p>
    @endif
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
                          <div class="flex gap-2">
                             <a href="{{ route('books.show', $book->id) }}"
                                 class="bg-[#5f31ff] hover:bg-[#4223b6] text-white px-4 py-1.5 rounded-lg font-semibold text-xs w-fit">Дэлгэрэнгүй</a>
                             
                          </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-400">
                @if(request('q'))
                    "{{ request('q') }}" -тэй тохирох ном олдсонгүй.
                @else
                    Номын мэдээлэл олдсонгүй.
                @endif
            </div>
        @endforelse
    </div>
</main>
</section>
@include('include.footer')