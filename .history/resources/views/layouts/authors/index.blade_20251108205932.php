@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Авторууд</h1>
        <div class="flex items-center gap-3">
           
            <a href="{{ route('admin.authors.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Шинэ</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-slate-900 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Зураг</th>
                    <th class="px-4 py-2 text-left">Нэр</th>
                    <th class="px-4 py-2 text-left">Имэйл</th>
                    <th class="px-4 py-2 text-left">Идэвхтэй</th>
                    <th class="px-4 py-2 text-right">Үйлдэл</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($authors as $author)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $loop->iteration + ($authors->currentPage()-1)*$authors->perPage() }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $author->avatar ? asset('storage/'.$author->avatar) : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}" class="w-10 h-10 rounded-full object-cover">
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $author->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $author->email }}</td>
                        <td class="px-4 py-3">
                            @if($author->is_active)
                                <span class="text-green-600">Тийм</span>
                            @else
                                <span class="text-red-600">Үгүй</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.authors.edit', $author) }}" class="text-blue-600 mr-3">Засах</a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline" onsubmit="return confirm('Устгах уу?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Устгах</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-600">Авторууд олдсонгүй.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $authors->links() }}
    </div>
</div>
@endsection


