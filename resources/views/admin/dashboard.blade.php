@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 100)" 
    x-show="show" 
    x-transition:enter="transition duration-1000 ease-out"
    x-transition:enter-start="opacity-0 translate-y-6 scale-98"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    class="max-w-7xl mx-auto"
>
    <div class="mb-6">
        <h4 class="text-2xl font-bold text-blue-600 mb-1">School Overview</h4>
        <span class="text-gray-500 text-sm">Welcome back! Here's a quick summary of your school's activity.</span>
    </div>

    <div 
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6"
        x-data="{}"
    >
        <!-- Total Students -->
        <a 
            href="{{ route('admin.students.index') }}" 
            class="bg-white rounded-lg shadow-sm border border-gray-200 text-center p-6 hover:shadow-md transition-all duration-700 block transform hover:scale-105 hover:-translate-y-0.5"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 250)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex justify-center mb-3">
                <i class="bi bi-people text-4xl text-green-600"></i>
            </div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Total Students</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</h2>
        </a>
        <!-- Upcoming Exams -->
        <a 
            href="{{ route('admin.exams.index') }}" 
            class="bg-white rounded-lg shadow-sm border border-gray-200 text-center p-6 hover:shadow-md transition-all duration-700 block transform hover:scale-105 hover:-translate-y-0.5"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 350)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex justify-center mb-3">
                <i class="bi bi-calendar-event text-4xl text-blue-600"></i>
            </div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Upcoming Exams</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $upcomingExams }}</h2>
        </a>
        <!-- Live Exams -->
        <a 
            href="{{ route('admin.exams.index') }}" 
            class="bg-white rounded-lg shadow-sm border border-gray-200 text-center p-6 hover:shadow-md transition-all duration-700 block transform hover:scale-105 hover:-translate-y-0.5"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 450)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex justify-center mb-3">
                <i class="bi bi-camera-video text-4xl text-red-600"></i>
            </div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Live Exams</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $liveExams }}</h2>
        </a>
        <!-- Pending Evaluations -->
        <a 
            href="{{ route('admin.reports.exams') }}" 
            class="bg-white rounded-lg shadow-sm border border-gray-200 text-center p-6 hover:shadow-md transition-all duration-700 block transform hover:scale-105 hover:-translate-y-0.5"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 550)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex justify-center mb-3">
                <i class="bi bi-hourglass-split text-4xl text-yellow-600"></i>
            </div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Pending Evaluations</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $pendingEvaluations }}</h2>
        </a>
        <!-- Violation Alerts -->
        <a 
            href="{{ route('admin.reports.exams') }}" 
            class="bg-white rounded-lg shadow-sm border border-gray-200 text-center p-6 hover:shadow-md transition-all duration-700 block transform hover:scale-105 hover:-translate-y-0.5"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 650)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex justify-center mb-3">
                <i class="bi bi-exclamation-octagon text-4xl text-red-600"></i>
            </div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Violation Alerts</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $violationAlerts }}</h2>
        </a>
    </div>

    <!-- Recent Activity -->
    <div
        x-data="{ showRecent: false }"
        x-init="setTimeout(() => showRecent = true, 900)"
        x-show="showRecent"
        x-transition:enter="transition duration-1000 ease-out"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        class="bg-white rounded-lg shadow-sm border border-gray-200"
    >
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="bi bi-clock-history text-gray-600 animate-pulse"></i>
                Recent Activity
            </h6>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentActivities as $activity)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-all duration-500 ease-out">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold transition-transform duration-700 hover:scale-105">
                            {{ substr($activity->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">
                                <span class="font-bold">{{ $activity->user->name ?? 'Unknown Student' }}</span>
                                @if($activity->status === 'completed' || $activity->status === 'submitted')
                                    submitted the exam
                                @else
                                    started the exam
                                @endif
                                <span class="font-bold">{{ $activity->exam->title ?? 'N/A' }}</span>.
                            </p>
                            <p class="text-xs text-gray-500">{{ $activity->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.exams.detail', $activity->exam_id) }}" class="text-sm text-indigo-600 hover:underline transition-all duration-500">
                        View Report
                    </a>
                </div>
            @empty
                <div class="p-6 text-center transition-opacity duration-700 opacity-70">
                    <p class="text-gray-500">No recent activities to display.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection