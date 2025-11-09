@include('include.header')

<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Авторууд</h1>
        <a href="{{ route('admin.authors.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Шинэ</a>
    </div>

    <form method="GET" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Хайх..." class="border px-3 py-2 rounded w-64">
        <button class="ml-2 bg-blue-600 text-white px-3 py-2 rounded">Хайх</button>
    </form>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @foreach($authors as $author)
            <div class="p-4 border rounded flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $author->avatar ? asset('storage/' . $author->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <div class="font-semibold">{{ $author->name }}</div>
                        <div class="text-sm text-gray-600">{{ $author->email }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.authors.edit', $author) }}" class="text-blue-600">Засах</a>
                    <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" onsubmit="return confirm('Устгах уу?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">Устгах</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $authors->links() }}
    </div>
</div>

@include('include.footer')
