@extends('layouts.student')

@section('title', 'Upcoming Exams')

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-2">
        <h1 class="text-2xl font-bold text-gray-800">Upcoming Exams</h1>
        <a href="{{ route('student.exams.history') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
            <i class="bi bi-clock-history mr-2"></i> Exam History
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Responsive Table: Hidden on small, used on md+ --}}
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                        <tr>
                            <th class="px-6 py-3">Exam Title</th>
                            <th class="px-6 py-3">Subject</th>
                            <th class="px-6 py-3">Schedule</th>
                            <th class="px-6 py-3">Duration</th>
                            <th class="px-6 py-3">Total Marks</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $exam->title }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $exam->subject }}
                            </td>
                            <td class="px-6 py-4">
                                @if($exam->schedule)
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 font-medium">
                                            {{ $exam->schedule->start_at->format('d M Y, h:i A') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            to {{ $exam->schedule->end_at->format('d M Y, h:i A') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Not Scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $duration = (int) $exam->duration_minutes;
                                    $hours = intdiv($duration, 60);
                                    $minutes = $duration % 60;
                                    $formattedDuration = '';
                                    if ($hours > 0) {
                                        $formattedDuration .= $hours . ' ' . Str::plural('hour', $hours);
                                    }
                                    if ($minutes > 0) {
                                        if ($formattedDuration) $formattedDuration .= ' ';
                                        $formattedDuration .= $minutes . ' ' . Str::plural('minute', $minutes);
                                    }
                                    if ($formattedDuration === '') {
                                        $formattedDuration = '0 minute';
                                    }
                                @endphp
                                <span>
                                    {{ $formattedDuration }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $exam->total_marks }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $isAttempted = in_array($exam->id, $attemptedExamIds ?? []);
                                    $now = now();
                                    $start = $exam->schedule?->start_at;
                                    $end = $exam->schedule?->end_at;
                                    $isLive = $start && $end && $now->between($start, $end);
                                    $isUpcoming = $start && $now->lessThan($start);
                                    $isExpired = $end && $now->greaterThan($end);
                                @endphp

                                @if($isAttempted)
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded border border-green-100">
                                        Submitted
                                    </span>
                                @elseif($isLive)
                                    <a href="{{ route('student.exams.live', $exam->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition">
                                        Start Exam
                                    </a>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded border border-blue-100">
                                        Upcoming
                                    </span>
                                @elseif($isExpired)
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200">
                                        Expired
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-clipboard-x text-4xl mb-2 text-gray-300"></i>
                                    <p>No upcoming exams found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards: Visible on sm and below --}}
        <div class="md:hidden">
            @forelse($exams as $exam)
            <div class="border-b border-gray-100 px-4 py-2 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <span class="text-base font-semibold text-gray-800">{{ $exam->title }}</span>
                    @php
                        $isAttempted = in_array($exam->id, $attemptedExamIds ?? []);
                        $now = now();
                        $start = $exam->schedule?->start_at;
                        $end = $exam->schedule?->end_at;
                        $isLive = $start && $end && $now->between($start, $end);
                        $isUpcoming = $start && $now->lessThan($start);
                        $isExpired = $end && $now->greaterThan($end);
                    @endphp

                    @if($isAttempted)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded border border-green-100">
                            Submitted
                        </span>
                    @elseif($isLive)
                        <a href="{{ route('student.exams.live', $exam->id) }}" class="ml-2 inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition whitespace-nowrap">
                            Start Exam
                        </a>
                    @elseif($isUpcoming)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-medium rounded border border-blue-100">
                            Upcoming
                        </span>
                    @elseif($isExpired)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200">
                            Expired
                        </span>
                    @else
                        <span class="ml-2 text-gray-400 text-xs">-</span>
                    @endif
                </div>
                <div class="flex flex-col gap-1 text-sm">
                    <div>
                        <span class="font-medium text-gray-600">Subject:</span>
                        <span class="text-gray-800">{{ $exam->subject }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Schedule:</span>
                        @if($exam->schedule)
                            <span class="text-gray-800">
                                {{ $exam->schedule->start_at->format('d M Y, h:i A') }}
                                <span class="text-xs text-gray-500"> - {{ $exam->schedule->end_at->format('d M Y, h:i A') }}</span>
                            </span>
                        @else
                            <span class="text-gray-400 italic">Not Scheduled</span>
                        @endif
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Duration:</span>
                        @php
                            $duration = (int) $exam->duration_minutes;
                            $hours = intdiv($duration, 60);
                            $minutes = $duration % 60;
                            $formattedDuration = '';
                            if ($hours > 0) {
                                $formattedDuration .= $hours . ' ' . Str::plural('hour', $hours);
                            }
                            if ($minutes > 0) {
                                if ($formattedDuration) $formattedDuration .= ' ';
                                $formattedDuration .= $minutes . ' ' . Str::plural('minute', $minutes);
                            }
                            if ($formattedDuration === '') {
                                $formattedDuration = '0 minute';
                            }
                        @endphp
                        <span class="text-gray-800">{{ $formattedDuration }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Total Marks:</span>
                        <span class="text-gray-800">{{ $exam->total_marks }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-4 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <i class="bi bi-clipboard-x text-4xl mb-2 text-gray-300"></i>
                    <p>No upcoming exams found.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($exams->hasPages())
        <div class="px-2 sm:px-6 py-4 border-t border-gray-100">
            {{ $exams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
