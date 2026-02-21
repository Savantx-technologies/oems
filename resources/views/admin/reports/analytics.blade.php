@extends('layouts.admin')

@section('title', 'Performance Analytics')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Performance Analytics</h1>
        <p class="text-sm text-gray-500">An overview of academic performance across the school.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Total Exams</div>
            <div class="text-3xl font-bold text-gray-800">{{ $totalExams }}</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Total Students</div>
            <div class="text-3xl font-bold text-indigo-600">{{ $totalStudents }}</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Total Attempts</div>
            <div class="text-3xl font-bold text-green-600">{{ $totalAttempts }}</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Avg. Score (Overall)</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($overallAvgScore, 1) }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance by Subject -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-4">Performance by Subject (Avg. %)</h3>
            @if($subjectPerformance->count() > 0)
                <canvas id="subjectChart"></canvas>
            @else
                <div class="text-center py-8 text-gray-500">No data available for subjects.</div>
            @endif
        </div>

        <!-- Performance by Class -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-semibold text-gray-800 mb-4">Performance by Class (Avg. %)</h3>
            @if($classPerformance->count() > 0)
                <canvas id="classChart"></canvas>
            @else
                <div class="text-center py-8 text-gray-500">No data available for classes.</div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Subject Performance Chart
    const subjectData = @json($subjectPerformance);
    if (subjectData.length > 0) {
        const subjectCtx = document.getElementById('subjectChart').getContext('2d');
        new Chart(subjectCtx, {
            type: 'bar',
            data: {
                labels: subjectData.map(d => d.subject),
                datasets: [{
                    label: 'Average Score (%)',
                    data: subjectData.map(d => d.average_percentage.toFixed(2)),
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Class Performance Chart
    const classData = @json($classPerformance);
    if (classData.length > 0) {
        const classCtx = document.getElementById('classChart').getContext('2d');
        new Chart(classCtx, {
            type: 'line',
            data: {
                labels: classData.map(d => `Class ${d.class}`),
                datasets: [{
                    label: 'Average Score (%)',
                    data: classData.map(d => d.average_percentage.toFixed(2)),
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    borderColor: 'rgba(20, 184, 166, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>
@endsection
