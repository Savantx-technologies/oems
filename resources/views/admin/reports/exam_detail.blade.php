@extends('layouts.admin')

@section('title', 'Report: ' . $exam->title)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $exam->title }}</h1>
            <p class="text-sm text-gray-500">Class {{ $exam->class }} • {{ $exam->subject }} • Total Marks: {{ $exam->total_marks }}</p>
        </div>
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center">
            <a href="{{ route('admin.reports.exams.detail.export', $exam->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-green-300 bg-green-50 px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="{{ route('admin.reports.exams') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Back to Reports
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-1 text-sm text-gray-500">Total Attempts</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalAttempts }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-1 text-sm text-gray-500">Average Score</div>
            <div class="text-2xl font-bold text-indigo-600">{{ number_format($avgScore, 1) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-1 text-sm text-gray-500">Highest Score</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($maxScore, 1) }}</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-1 text-sm text-gray-500">Pass Rate</div>
            <div class="text-2xl font-bold text-blue-600">{{ $totalAttempts > 0 ? round(($passed / $totalAttempts) * 100) : 0 }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5 shadow-sm">
            <div class="text-sm text-indigo-700">Ranked Students</div>
            <div class="mt-2 text-2xl font-bold text-indigo-900">{{ $rankingMeta['rankedCount'] }}</div>
            <p class="mt-2 text-xs text-indigo-700/80">Students with submitted results available for ranking.</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5 shadow-sm">
            <div class="text-sm text-emerald-700">Levels Found</div>
            <div class="mt-2 text-2xl font-bold text-emerald-900">{{ $rankingMeta['levelGroups']->count() }}</div>
            <p class="mt-2 text-xs text-emerald-700/80">Grouped using each student's `grade` value.</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-5 shadow-sm">
            <div class="text-sm text-amber-700">Overall Topper</div>
            @if($rankingMeta['overallTopper'])
                <div class="mt-2 text-lg font-bold text-amber-900">{{ $rankingMeta['overallTopper']['attempt']->user->name ?? 'Unknown Student' }}</div>
                <p class="mt-1 text-sm text-amber-800">
                    {{ $rankingMeta['overallTopper']['attempt']->score ?? 0 }} / {{ $exam->total_marks }}
                    ({{ round($rankingMeta['overallTopper']['percentage']) }}%)
                </p>
            @else
                <div class="mt-2 text-lg font-bold text-amber-900">No ranked data</div>
            @endif
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b px-4 py-4 sm:px-6">
            <h3 class="font-semibold text-gray-800">Student Performance</h3>
        </div>

        <div class="space-y-4 p-4 sm:hidden">
            @forelse($attempts as $attempt)
                @php $percentage = $exam->total_marks > 0 ? (($attempt->score ?? 0) / $exam->total_marks) * 100 : 0; @endphp
                @php $attemptRank = $rankingMeta['attemptRanks'][$attempt->id] ?? null; @endphp
                @php
                    $statusColors = [
                        'completed' => 'bg-green-100 text-green-800',
                        'submitted' => 'bg-green-100 text-green-800',
                        'in_progress' => 'bg-blue-100 text-blue-800',
                        'terminated' => 'bg-red-100 text-red-800',
                    ];
                    $color = $statusColors[$attempt->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-base font-semibold text-gray-800">{{ $attempt->user->name ?? 'Unknown Student' }}</div>
                            <div class="mt-1 text-xs text-gray-500">{{ $attempt->user->admission_number ?? '-' }}</div>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $color }}">{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}</span>
                    </div>
                    <div class="mt-4">
                        @if($attemptRank)
                            <div class="mb-3 grid grid-cols-2 gap-2 text-xs">
                                <div class="rounded-xl bg-indigo-50 px-3 py-2 text-indigo-700">
                                    Overall Position: <span class="font-bold">#{{ $attemptRank['overall_rank'] }}</span>
                                </div>
                                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-emerald-700">
                                    {{ $attemptRank['level'] }} Position: <span class="font-bold">#{{ $attemptRank['level_rank'] }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-medium text-gray-700">{{ $attempt->score ?? 0 }} / {{ $exam->total_marks }}</span>
                            <span class="text-xs text-gray-500">{{ round($percentage) }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-200">
                            <div class="h-2 rounded-full bg-indigo-600" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="mt-3 text-xs text-gray-500">{{ $attempt->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">No attempts recorded yet.</div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto sm:block">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Student</th>
                        <th class="px-5 py-3 text-left">Level</th>
                        <th class="px-5 py-3 text-left">Overall Pos.</th>
                        <th class="px-5 py-3 text-left">Level Pos.</th>
                        <th class="px-5 py-3 text-left">Admission No</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Score</th>
                        <th class="px-5 py-3 text-left">Percentage</th>
                        <th class="px-5 py-3 text-left">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($attempts as $attempt)
                        @php $attemptRank = $rankingMeta['attemptRanks'][$attempt->id] ?? null; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $attempt->user->name ?? 'Unknown Student' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $attemptRank['level'] ?? ($attempt->user->grade ?? 'Unassigned') }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $attemptRank ? '#' . $attemptRank['overall_rank'] : '-' }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $attemptRank ? '#' . $attemptRank['level_rank'] : '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $attempt->user->admission_number ?? '-' }}</td>
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
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $color }}">{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}</span>
                            </td>
                            <td class="px-5 py-3 font-bold">{{ $attempt->score ?? 0 }} / {{ $exam->total_marks }}</td>
                            <td class="px-5 py-3">
                                @php $percentage = $exam->total_marks > 0 ? (($attempt->score ?? 0) / $exam->total_marks) * 100 : 0; @endphp
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 w-16 rounded-full bg-gray-200">
                                        <div class="h-1.5 rounded-full bg-indigo-600" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-xs">{{ round($percentage) }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $attempt->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-gray-500">No attempts recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="border-t px-4 py-3">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Level-wise Results</h3>
            <span class="text-xs text-gray-500">Grouped by student grade / level</span>
        </div>

        @if($rankingMeta['levelGroups']->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center text-gray-500">
                No submitted results available for level-wise ranking yet.
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                @foreach($rankingMeta['levelGroups'] as $level => $levelAttempts)
                    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 bg-gray-50 px-5 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-800">{{ $level }}</h4>
                                    <p class="mt-1 text-xs text-gray-500">{{ $levelAttempts->count() }} ranked student{{ $levelAttempts->count() !== 1 ? 's' : '' }}</p>
                                </div>
                                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    Top: {{ $levelAttempts->first()['attempt']->score ?? 0 }}/{{ $exam->total_marks }}
                                </span>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach($levelAttempts->take(10) as $levelAttempt)
                                <div class="flex items-center justify-between gap-4 px-5 py-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">#{{ $levelAttempt['level_rank'] }}</span>
                                            <div class="min-w-0">
                                                <div class="truncate font-medium text-gray-800">{{ $levelAttempt['attempt']->user->name ?? 'Unknown Student' }}</div>
                                                <div class="text-xs text-gray-500">{{ $levelAttempt['attempt']->user->admission_number ?? '-' }} • Overall #{{ $levelAttempt['overall_rank'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-800">{{ $levelAttempt['attempt']->score ?? 0 }} / {{ $exam->total_marks }}</div>
                                        <div class="text-xs text-gray-500">{{ round($levelAttempt['percentage']) }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($levelAttempts->count() > 10)
                            <div class="border-t border-gray-100 bg-gray-50 px-5 py-3 text-xs text-gray-500">
                                Showing top 10 of {{ $levelAttempts->count() }} students in {{ $level }}.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
