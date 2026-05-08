@extends('layouts.student')

@section('title', 'Exam History')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div class="flex flex-col gap-4 rounded-3xl bg-white/80 p-5 shadow-sm ring-1 ring-gray-100 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:p-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-indigo-500/80">Completed Schedule</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-800">Exam History</h1>
        </div>
        <a href="{{ route('student.exams.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
            <i class="bi bi-arrow-left mr-2"></i> Back to Upcoming
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
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
                            <th class="px-6 py-3 text-right">Status</th>
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
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded border border-gray-200">
                                    Expired
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-clock-history text-4xl mb-2 text-gray-300"></i>
                                    <p>No past exams found.</p>
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
            <div class="space-y-3 border-b border-gray-100 px-4 py-4">
                <div class="flex items-center justify-between">
                    <span class="pr-3 text-base font-semibold text-gray-800">{{ $exam->title }}</span>
                    <span class="ml-2 inline-flex items-center rounded-full border border-gray-200 bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">
                        Expired
                    </span>
                </div>
                <div class="grid grid-cols-1 gap-3 rounded-2xl bg-gray-50 p-4 text-sm">
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
                    <i class="bi bi-clock-history text-4xl mb-2 text-gray-300"></i>
                    <p>No past exams found.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($exams->hasPages())
        <div class="border-t border-gray-100 px-3 py-4 sm:px-6">
            {{ $exams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
