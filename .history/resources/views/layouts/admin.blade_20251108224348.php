@extends('layouts.sidebar')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold mb-6">📊 Хяналтын самбар</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Нийт хэрэглэгчид --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 mb-2">Нийт хэрэглэгчид</p>
            @if(isset($totalUsers))
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        @endif
        </div>
        {{-- Нийт номнууд --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <a href="{{ route('admin.books.index') }}">
                <p class="text-gray-500 mb-2">Нийт номнууд</p>
                @if(isset($totalBooks))
                <p class="text-3xl font-bold text-green-600">{{ $totalBooks }}</p>
                @endif
            </a>
        </div>
        {{-- Подкаст --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 mb-2" >Зохиолч</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $totalAuthors }}</p>
            <p class="text-3xl font-bold text-yellow-600"></p>
        </div>
        {{-- Шинэ хэрэглэгч --}}
        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 mb-2">Шинэ хэрэглэгч</p>
            <p class="text-3xl font-bold text-purple-600">0</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">📈 Номын статистик</h2>
        <canvas id="bookChart" height="100"></canvas>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const labels = @json($categoryLabels ?? []);
    const dataCounts = @json($categoryCounts ?? []);

    const ctx = document.getElementById("bookChart").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Номын тоо",
                data: dataCounts,
                backgroundColor: [
                    "rgba(37, 99, 235, 0.6)",
                    "rgba(22, 163, 74, 0.6)",
                    "rgba(234, 179, 8, 0.6)",
                    "rgba(147, 51, 234, 0.6)",
                    // хэрвээ категори олон бол өнгийг автоматаар generate хийж болно
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: "Номын төрөл тус бүрийн статистик", font: { size: 18 } }
            },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endsection