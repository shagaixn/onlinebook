@extends('layouts.admin')

@section('title', 'Номын жагсаалт')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📚 Номын жагсаалт</h1>
        <a href="{{ route('admin.books.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            + Ном нэмэх
        </a>
    </div>
    <div class="mb-8 flex justify-center">
       
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Зохиолчийн нэрээр хайх..." 
                class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:bg-slate-800 dark:text-white"
            >
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl transition">
                🔍 Хайх
            </button>
        </form>
    </div>
    <div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($books->count() > 0)
    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Зураг</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Номын нэр</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Зохиолч</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Ангилал</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Үнэ</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-semibold">Огноо</th>
                    <th class="px-4 py-2 text-center text-gray-600 font-semibold">Үйлдэл</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="cover" class="w-16 h-16 object-cover rounded shadow">
                    </td>
                    <td class="px-4 py-2 font-medium text-gray-800">{{ $book->title }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $book->author }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $book->category ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ number_format($book->price) }}₮</td>
                    <td class="px-4 py-2 text-gray-700">
                        {{ $book->published_date ? \Carbon\Carbon::parse($book->published_date)->format('Y.m.d') : '-' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('admin.books.edit', $book->id) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">Засах</a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                                  onsubmit="return confirm('Устгах уу?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                    Устгах
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t">
            {{ $books->links() }}
        </div>
    </div>
    @else
        <div class="text-gray-600 text-center py-10 bg-white rounded-xl shadow">
            📭 Одоогоор ном бүртгэгдээгүй байна.
        </div>
    @endif
</div>
@endsection
