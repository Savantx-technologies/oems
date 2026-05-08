@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">
<!-- Stats Grid -->
<div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
    <!-- Welcome & Upcoming -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-700 to-cyan-600 p-6 text-white shadow-xl sm:p-7">
        <div class="relative z-10">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Student Dashboard</p>
            <h2 class="mt-2 text-2xl font-bold sm:text-3xl">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="mt-3 max-w-xl text-sm text-blue-100">You have <span class="text-lg font-bold text-white">{{ $upcomingExams->count() }}</span> scheduled exams and a live snapshot of your recent progress below.</p>
            <a href="{{ route('student.exams.index') }}" class="mt-5 inline-flex items-center rounded-xl border border-white/10 bg-white/20 px-4 py-2.5 text-sm font-medium backdrop-blur-sm transition-colors hover:bg-white/30">
                View All Exams
            </a>
        </div>
        <div class="absolute -bottom-6 right-0 translate-x-4 translate-y-4 opacity-10">
            <i class="bi bi-mortarboard-fill text-9xl"></i>
        </div>
    </div>

    <!-- Performance Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- Completed Exams -->
        <div class="flex items-center gap-4 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-xl text-green-600">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Completed Exams</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $completedExamsCount }}</h3>
            </div>
        </div>

        <!-- Average Score -->
        <div class="flex items-center gap-4 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-yellow-50 text-xl text-yellow-600">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Average Score</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ round($averageScore, 1) }}%</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-8 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,0.65fr)]">
    <!-- Left Column: Upcoming Exams -->
    <div class="lg:col-span-2 space-y-6">
        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-5 py-5 sm:px-6">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-calendar-event text-indigo-500"></i> Upcoming & Live Exams
                </h3>
            </div>
            
            @if($upcomingExams->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach($upcomingExams as $exam)
                        @php
                            $now = now();
                            $start = $exam->schedule?->start_at;
                            $end = $exam->schedule?->end_at;
                            $isLive = $start && $end && $now->between($start, $end);
                            $isFuture = $start && $start->isFuture();
                        @endphp
                        <div class="group p-5 transition-colors hover:bg-gray-50 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $isLive ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $isLive ? 'Live Now' : 'Upcoming' }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $exam->subject }}</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $exam->title }}
                                    </h4>
                                    <div class="mt-2 flex flex-col gap-2 text-sm text-gray-500 sm:flex-row sm:items-center sm:gap-4">
                                        <span class="flex items-center gap-1"><i class="bi bi-clock"></i> {{ $exam->duration_minutes }} mins</span>
                                        <span class="flex items-center gap-1"><i class="bi bi-journal-check"></i> {{ $exam->total_marks }} Marks</span>
                                    </div>
                                    @if($start)
                                        <p class="text-xs text-gray-400 mt-2">
                                            Scheduled: {{ $start->format('M d, h:i A') }} - {{ $end->format('h:i A') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="shrink-0">
                                    @if($isLive)
                                        <a href="{{ route('student.exams.live', $exam->id) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-200 transition-all hover:scale-[1.02] hover:bg-indigo-700 sm:w-auto">
                                            Start Exam <i class="bi bi-arrow-right ml-2"></i>
                                        </a>
                                    @else
                                        <button disabled class="inline-flex w-full items-center justify-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-400 cursor-not-allowed sm:w-auto">
                                            Wait for Start
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-10 text-center sm:p-12">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50">
                        <i class="bi bi-calendar-x text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-gray-900 font-medium mb-1">No Upcoming Exams</h3>
                    <p class="text-gray-500 text-sm">You're all caught up! Check back later for new schedules.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Right Column: Recent Results -->
    <div class="lg:col-span-1">
        <div class="h-full overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50/50 px-5 py-5 sm:px-6">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-500"></i> Recent Results
                </h3>
            </div>
            
            @if($recentResults->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach($recentResults as $result)
                        <div class="p-4 transition-colors hover:bg-gray-50 sm:p-5">
                            <div class="flex justify-between items-start mb-1">
                                <h5 class="text-sm font-semibold text-gray-800 line-clamp-1" title="{{ $result->exam->title }}">
                                    {{ $result->exam->title }}
                                </h5>
                                <span class="text-xs font-bold {{ $result->score >= ($result->exam->pass_marks ?? 33) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ round($result->score) }}%
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">{{ $result->exam->subject }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] text-gray-400">{{ $result->submitted_at->diffForHumans() }}</span>
                                <a href="{{ route('student.result', $result->id) }}" class="text-[10px] font-medium text-indigo-600 hover:text-indigo-700 hover:underline">
                                    View Report
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="{{ route('student.results.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-800 transition-colors">View All History</a>
                </div>
            @else
                <div class="p-8 text-center">
                    <p class="text-gray-400 text-sm">No recent exam history.</p>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
