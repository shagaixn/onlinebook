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

    {{-- Category Statistics --}}
    <div class="bg-white p-6 rounded-2xl shadow max-w-2xl mx-auto">
        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
            <span class="text-2xl">📚</span>
            Категорийн жагсаалт
        </h3>
        <div class="space-y-3">
            @if(!empty($categories) && !empty($bookCounts))
                @foreach($categories as $index => $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#2563eb', '#16a34a', '#eab308', '#9333ea', '#ec4899', '#06b6d4'][$index % 6] }}"></div>
                            <span class="font-medium text-gray-700">{{ $category }}</span>
                        </div>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                            {{ $bookCounts[$index] ?? 0 }}
                        </span>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 text-sm">Категори байхгүй байна</p>
            @endif
        </div>
    </div>
</div>

@endsection
