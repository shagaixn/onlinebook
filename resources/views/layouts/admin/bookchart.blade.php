@extends('layouts.sidebar') 

@section('content')
<div class="p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-8">📈 Номын статистик</h1>

    <div class="bg-white p-6 rounded-2xl shadow w-full md:w-2/3 mx-auto">
        <canvas id="bookChart" height="150"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById("bookChart").getContext("2d");

    const chart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: @json($categories),
            datasets: [{
                label: "Номын тоо",
                data: @json($bookCounts),
                backgroundColor: [
                    "rgba(37, 99, 235, 0.6)",
                    "rgba(22, 163, 74, 0.6)",
                    "rgba(234, 179, 8, 0.6)",
                    "rgba(147, 51, 234, 0.6)"
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: "Номын төрөл тус бүрийн статистик",
                    font: { size: 18 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });
</script>
@endpush
@endsection
