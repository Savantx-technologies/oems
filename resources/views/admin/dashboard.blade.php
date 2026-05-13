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
    class="mx-auto max-w-7xl"
>
    <div class="mb-6 rounded-3xl bg-gradient-to-r from-slate-900 via-blue-900 to-cyan-800 px-5 py-6 text-white shadow-xl sm:px-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-200">School Overview</p>
                <h4 class="mt-2 text-2xl font-bold sm:text-3xl">Welcome back, manage your campus at a glance.</h4>
                <span class="mt-2 block max-w-2xl text-sm text-blue-100/90">Track students, live exams, evaluation load, and recent activity from one responsive control center.</span>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:w-auto">
                <div class="rounded-2xl border border-cyan-300/30 bg-cyan-100/15 backdrop-blur px-4 py-3 shadow-cyan-700/10 shadow-lg">
                    <p class="text-xs text-cyan-100">Students</p>
                    <p class="mt-1 text-xl font-semibold">{{ $totalStudents }}</p>
                </div>
                <div class="rounded-2xl border border-cyan-300/30 bg-cyan-100/15 backdrop-blur px-4 py-3 shadow-cyan-700/10 shadow-lg">
                    <p class="text-xs text-cyan-100">Live Now</p>
                    <p class="mt-1 text-xl font-semibold">{{ $liveExams }}</p>
                </div>
            </div>
        </div>
    </div>

    <div 
        class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5"
        x-data="{}"
    >
        <!-- Total Students -->
        <a 
            href="{{ route('admin.students.index') }}" 
            class="block rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-700 hover:-translate-y-0.5 hover:shadow-lg"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 250)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600">
                    <i class="bi bi-people text-2xl"></i>
                </span>
                <span class="rounded-full bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Students</span>
            </div>
            <h5 class="mb-2 text-sm font-medium text-gray-500">Total Students</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</h2>
        </a>
        <!-- Upcoming Exams -->
        <a 
            href="{{ route('admin.exams.index') }}" 
            class="block rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-700 hover:-translate-y-0.5 hover:shadow-lg"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 350)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="bi bi-calendar-event text-2xl"></i>
                </span>
                <span class="rounded-full bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Schedule</span>
            </div>
            <h5 class="mb-2 text-sm font-medium text-gray-500">Upcoming Exams</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $upcomingExams }}</h2>
        </a>
        <!-- Live Exams -->
        <a 
            href="{{ route('admin.exams.index') }}" 
            class="block rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-700 hover:-translate-y-0.5 hover:shadow-lg"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 450)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                    <i class="bi bi-camera-video text-2xl"></i>
                </span>
                <span class="rounded-full bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Monitoring</span>
            </div>
            <h5 class="mb-2 text-sm font-medium text-gray-500">Live Exams</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $liveExams }}</h2>
        </a>
        <!-- Pending Evaluations -->
        <a 
            href="{{ route('admin.reports.exams') }}" 
            class="block rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-700 hover:-translate-y-0.5 hover:shadow-lg"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 550)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                    <i class="bi bi-hourglass-split text-2xl"></i>
                </span>
                <span class="rounded-full bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Workflow</span>
            </div>
            <h5 class="mb-2 text-sm font-medium text-gray-500">Pending Evaluations</h5>
            <h2 class="text-3xl font-bold text-gray-900">{{ $pendingEvaluations }}</h2>
        </a>
        <!-- Violation Alerts -->
        <a 
            href="{{ route('admin.reports.exams') }}" 
            class="block rounded-3xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-all duration-700 hover:-translate-y-0.5 hover:shadow-lg"
            x-data="{ showCard: false }"
            x-init="setTimeout(() => showCard = true, 650)"
            x-show="showCard"
            x-transition:enter="transition duration-1000 ease-out"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <i class="bi bi-exclamation-octagon text-2xl"></i>
                </span>
                <span class="rounded-full bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Alerts</span>
            </div>
            <h5 class="mb-2 text-sm font-medium text-gray-500">Violation Alerts</h5>
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
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
    >
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
            <h6 class="flex items-center gap-2 text-lg font-bold text-gray-800">
                <i class="bi bi-clock-history text-gray-600 animate-pulse"></i>
                Recent Activity
            </h6>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentActivities as $activity)
                <div class="flex flex-col gap-4 p-4 transition-all duration-500 ease-out hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600 transition-transform duration-700 hover:scale-105">
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
                    <a href="{{ route('admin.reports.exams.detail', $activity->exam_id) }}" class="inline-flex items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 transition-all duration-500 hover:bg-indigo-100 sm:justify-start">
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
