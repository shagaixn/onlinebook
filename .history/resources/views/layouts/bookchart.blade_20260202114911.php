@extends('layouts.sidebar') 

@section('content')
<div class="p-4 md:p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">📈 Номын статистик</h1>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Total Books Card --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl shadow text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium mb-1">Нийт номын тоо</p>
                    <h2 class="text-3xl font-bold">{{ $totalBooks ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Authors Card --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl shadow text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-xs font-medium mb-1">Нийт зохиолчдын тоо</p>
                    <h2 class="text-3xl font-bold">{{ $totalAuthors ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Users Card --}}
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-xs font-medium mb-1">Нийт хэрэглэгчдийн тоо</p>
                    <h2 class="text-3xl font-bold">{{ $totalUsers ?? 0 }}</h2>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Books Chart --}}
        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="text-base font-bold mb-3 text-center">📚 Номын тархалт</h3>
            @if(!empty($categories) && !empty($bookCounts) && array_sum($bookCounts ?? []) > 0)
                <div class="max-w-xs mx-auto">
                    <canvas id="booksChart" height="250"></canvas>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-sm mb-1">📊</p>
                    <p class="text-gray-600 text-sm">Категоритой ном байхгүй</p>
                </div>
            @endif
        </div>

        {{-- Authors Chart --}}
        <div class="bg-white p-4 rounded-xl shadow">
            <h3 class="text-base font-bold mb-3 text-center">✍️ Зохиолчдын номын тоо</h3>
            @if(!empty($topAuthors) && count($topAuthors) > 0)
                <div class="max-w-xs mx-auto">
                    <canvas id="authorsChart" height="250"></canvas>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-sm mb-1">✍️</p>
                    <p class="text-gray-600 text-sm">Номтой зохиолч байхгүй</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    console.log('📊 Chart Data:', {
        categories: @json($categories ?? []),
        bookCounts: @json($bookCounts ?? []),
        topAuthors: @json($topAuthors ?? [])
    });

    // Books by Category Chart
    @if(!empty($categories) && !empty($bookCounts) && array_sum($bookCounts ?? []) > 0)
    const booksCtx = document.getElementById('booksChart');
    if (booksCtx) {
        const categories = @json($categories);
        const bookCounts = @json($bookCounts);

        const colors = [
            '#2563eb', '#16a34a', '#eab308', '#9333ea', 
            '#ec4899', '#06b6d4', '#f97316', '#14b8a6'
        ];

        new Chart(booksCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: bookCounts,
                    backgroundColor: colors.slice(0, categories.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return ` ${context.label}: ${context.parsed} ном (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        console.log('✅ Books chart үүсгэгдлээ');
    }
    @endif

    // Authors Chart
    @if(!empty($topAuthors) && count($topAuthors) > 0)
    const authorsCtx = document.getElementById('authorsChart');
    if (authorsCtx) {
        const authorsData = @json($topAuthors);

        new Chart(authorsCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: authorsData.map(a => a.name),
                datasets: [{
                    label: 'Номын тоо',
                    data: authorsData.map(a => a.books_count),
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} ном`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
        console.log('✅ Authors chart үүсгэгдлээ');
    }
    @endif
</script>
@endpush

@endsection
