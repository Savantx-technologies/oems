@extends('layouts.admin')

@section('title', 'Report: ' . $exam->title)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $exam->title }}</h1>
            <p class="text-sm text-gray-500">Class {{ $exam->class }} • {{ $exam->subject }} • Total Marks: {{ $exam->total_marks }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.exams.detail.export', $exam->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-green-300 bg-green-50 text-sm text-green-700 hover:bg-green-100">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="{{ route('admin.reports.exams') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Total Attempts</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalAttempts }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Average Score</div>
            <div class="text-2xl font-bold text-indigo-600">{{ number_format($avgScore, 1) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Highest Score</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($maxScore, 1) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Pass Rate</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ $totalAttempts > 0 ? round(($passed / $totalAttempts) * 100) : 0 }}%
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Student Performance</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Student</th>
                        <th class="px-5 py-3 text-left">Admission No</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Score</th>
                        <th class="px-5 py-3 text-left">Percentage</th>
                        <th class="px-5 py-3 text-left">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($attempts as $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $attempt->user->name ?? 'Unknown Student' }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $attempt->user->admission_number ?? '-' }}
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'submitted' => 'bg-green-100 text-green-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'terminated' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$attempt->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-bold">
                            {{ $attempt->score ?? 0 }} / {{ $exam->total_marks }}
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $percentage = $exam->total_marks > 0 ? (($attempt->score ?? 0) / $exam->total_marks) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-xs">{{ round($percentage) }}%</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $attempt->updated_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No attempts recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($attempts->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $attempts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
