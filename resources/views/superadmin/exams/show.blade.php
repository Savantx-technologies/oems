@extends('layouts.superadmin')

@section('title', 'Exam Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $exam->title }}</h1>
            <p class="text-sm text-gray-500">
                Created by School: <span class="font-medium text-gray-700">{{ $exam->school->name ?? 'Unknown' }}</span>
            </p>
        </div>
        <a href="{{ route('superadmin.exams.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Info Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Exam Information</h3>
                <div class="grid grid-cols-2 gap-y-4 gap-x-8 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs">Class</span>
                        <span class="font-medium text-gray-900">{{ $exam->class }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Subject</span>
                        <span class="font-medium text-gray-900">{{ $exam->subject }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Exam Type</span>
                        <span class="font-medium text-gray-900 capitalize">{{ $exam->exam_type }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Academic Session</span>
                        <span class="font-medium text-gray-900">{{ $exam->academic_session }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                            {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' : ($exam->status === 'closed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $exam->status }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">Created At</span>
                        <span class="font-medium text-gray-900">{{ $exam->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Questions</h3>
                    <span class="text-xs font-medium bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{ $questions->count() }} Questions</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                            <tr>
                                <th class="px-6 py-3 w-16">Ques No.</th>
                                <th class="px-6 py-3">Question Text</th>
                                <th class="px-6 py-3 w-24">Type</th>
                                <th class="px-6 py-3 w-24 text-right">Marks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($questions as $index => $question)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-3">
                                    <div class="text-gray-900 line-clamp-2" title="{{ $question->question_text }}">{{ $question->question_text }}</div>
                                </td>
                                <td class="px-6 py-3 capitalize text-gray-600">{{ $question->type }}</td>
                                <td class="px-6 py-3 text-right font-medium">{{ $question->marks }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No questions attached to this exam.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Scoring & Rules -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Scoring & Rules</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between">
                        <span class="text-gray-500">Total Marks</span>
                        <span class="font-bold text-gray-900">{{ $exam->total_marks }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Pass Marks</span>
                        <span class="font-medium text-gray-900">{{ $exam->pass_marks ?? '-' }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Duration</span>
                        <span class="font-medium text-gray-900">
                            @php
                            $minutes = $exam->duration_minutes;
                            $hours = intdiv($minutes, 60);
                            $mins = $minutes % 60;
                            $durationString = '';
                            if($hours > 0) {
                            $durationString .= $hours . ' hr' . ($hours > 1 ? 's' : '');
                            }
                            if($hours > 0 && $mins > 0) {
                            $durationString .= ' ';
                            }
                            if($mins > 0) {
                            $durationString .= $mins . ' min' . ($mins > 1 ? 's' : '');
                            }
                            if($durationString === '') {
                            $durationString = '0 min';
                            }
                            @endphp
                            {{ $durationString }}
                        </span>
                    </li>
                    <li class="pt-2 border-t border-gray-100"></li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Negative Marking</span>
                        <span class="{{ $exam->negative_marking ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                            {{ $exam->negative_marking ? 'Yes (-'.$exam->negative_marks.')' : 'No' }}
                        </span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-500">Shuffle Questions</span>
                        <span class="text-gray-900">{{ $exam->shuffle_questions ? 'Yes' : 'No' }}</span>
                    </li>
                </ul>
            </div>

            <!-- Schedule -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Schedule</h3>
                @if($exam->schedule)
                <div class="space-y-4 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs">Start Time</span>
                        <span class="font-medium text-gray-900">{{ $exam->schedule->start_at ? $exam->schedule->start_at->format('M d, Y h:i A') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs">End Time</span>
                        <span class="font-medium text-gray-900">{{ $exam->schedule->end_at ? $exam->schedule->end_at->format('M d, Y h:i A') : '-' }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100"></div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Max Attempts</span>
                        <span class="font-medium text-gray-900">{{ $exam->schedule->max_attempts }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Late Entry</span>
                        <span class="font-medium text-gray-900">{{ $exam->schedule->late_entry_allowed ? $exam->schedule->late_entry_minutes . ' min' : 'Not Allowed' }}</span>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">No schedule set for this exam.</p>
                @endif
            </div>
        </div>

        <!-- Student Attempts / Results -->
        <div class="lg:col-span-3">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Student Attempts</h3>
                    <span class="text-xs font-medium bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Latest 20</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                            <tr>
                                <th class="px-6 py-3">Student</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Score</th>
                                <th class="px-6 py-3">Submitted At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($attempts as $attempt)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-900">{{ $attempt->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">{{ $attempt->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-3 capitalize">{{ str_replace('_', ' ', $attempt->status) }}</td>
                                    <td class="px-6 py-3 font-medium">
                                        {{ $attempt->score !== null ? $attempt->score . '%' : '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-500">
                                        {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No attempts recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">
                    {{ $attempts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection