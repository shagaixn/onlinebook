@extends('layouts.sidebar') 

@section('content')
<div class="p-8 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-8">📈 Номын статистик</h1>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Books Card --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-2">Нийт номын тоо</p>
                    <h2 class="text-4xl font-bold">{{ $totalBooks ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-4 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Authors Card --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-2">Нийт зохиолчдын тоо</p>
                    <h2 class="text-4xl font-bold">{{ $totalAuthors ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-4 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Users Card --}}
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-2">Нийт хэрэглэгчдийн тоо</p>
                    <h2 class="text-4xl font-bold">{{ $totalUsers ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-4 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Most Read Books Section --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">📚 Хамгийн их уншигдсан номууд</h2>
        
        @if($mostReadBooks->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($mostReadBooks as $book)
                    <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start space-x-4">
                            @if($book['cover_image'])
                                <img src="{{ asset('storage/' . $book['cover_image']) }}" 
                                     alt="{{ $book['title'] }}" 
                                     class="w-16 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-20 bg-gray-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 text-sm mb-1">{{ $book['title'] }}</h3>
                                <p class="text-gray-600 text-xs mb-2">{{ $book['author'] }}</p>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="text-blue-600 text-sm font-medium">{{ $book['readers_count'] }} уншигч</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-gray-500">Одоогоор уншигдсан ном байхгүй байна</p>
            </div>
        @endif
    </div>
</div>

@endsection
