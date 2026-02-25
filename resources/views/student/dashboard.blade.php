@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Welcome & Upcoming -->
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold mb-1">Welcome, {{ auth()->user()->name }}!</h2>
            <p class="text-blue-100 text-sm mb-4">You have <span class="font-bold text-white text-lg">{{ $upcomingExams->count() }}</span> exams scheduled.</p>
            <a href="{{ route('student.exams.index') }}" class="inline-block px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg text-sm font-medium transition-colors border border-white/10">
                View All Exams
            </a>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
            <i class="bi bi-mortarboard-fill text-9xl"></i>
        </div>
    </div>

    <!-- Performance Stats -->
    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Completed Exams -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Completed Exams</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $completedExamsCount }}</h3>
            </div>
        </div>

        <!-- Average Score -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Average Score</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ round($averageScore, 1) }}%</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Upcoming Exams -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
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
                        <div class="p-6 hover:bg-gray-50 transition-colors group">
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
                                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
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
                                        <a href="{{ route('student.exams.live', $exam->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all transform hover:scale-105">
                                            Start Exam <i class="bi bi-arrow-right ml-2"></i>
                                        </a>
                                    @else
                                        <button disabled class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
                                            Wait for Start
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-500"></i> Recent Results
                </h3>
            </div>
            
            @if($recentResults->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach($recentResults as $result)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
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
@endsection
