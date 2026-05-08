@extends('layouts.admin')

@section('title', 'Exam Reports')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Exam Reports</h1>
            <p class="text-sm text-gray-500">View performance analytics for exams</p>
        </div>

        <form method="GET" class="relative w-full lg:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exams..."
                class="w-full rounded-2xl border border-gray-300 py-2.5 pl-10 pr-4 focus:border-indigo-500 focus:ring-indigo-500 lg:w-64">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="space-y-4 p-4 sm:hidden">
            @forelse($exams as $exam)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="truncate text-base font-semibold text-gray-800">{{ $exam->title }}</div>
                            <div class="mt-1 text-sm text-gray-500">Class {{ $exam->class }} • {{ $exam->subject }}</div>
                        </div>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{{ $exam->attempts_count }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-400">Type</div>
                            <div class="mt-1 capitalize text-gray-700">{{ $exam->exam_type }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-400">Date</div>
                            <div class="mt-1 text-gray-700">@if($exam->schedule){{ $exam->schedule->start_at->format('d M Y') }}@else Unscheduled @endif</div>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('admin.reports.exams.detail', $exam->id) }}" class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700">View Report</a>
                        <a href="{{ route('admin.reports.exams.violations', $exam->id) }}" class="rounded-xl bg-red-50 px-3 py-2 text-xs font-medium text-red-700">Violations</a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">No exams found.</div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto sm:block">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Exam Title</th>
                        <th class="px-5 py-3 text-left">Class / Subject</th>
                        <th class="px-5 py-3 text-left">Type</th>
                        <th class="px-5 py-3 text-left">Attempts</th>
                        <th class="px-5 py-3 text-left">Date</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-800">{{ $exam->title }}</td>
                            <td class="px-5 py-3">
                                <div class="text-gray-800">Class {{ $exam->class }}</div>
                                <div class="text-xs text-gray-500">{{ $exam->subject }}</div>
                            </td>
                            <td class="px-5 py-3 capitalize">{{ $exam->exam_type }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ $exam->attempts_count }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                @if($exam->schedule)
                                    {{ $exam->schedule->start_at->format('d M Y') }}
                                @else
                                    <span class="text-xs text-gray-400">Unscheduled</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.reports.exams.detail', $exam->id) }}" class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-100">View Report</a>
                                <a href="{{ route('admin.reports.exams.violations', $exam->id) }}" class="ml-2 inline-flex items-center rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">Violations</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">No exams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
            <div class="border-t px-4 py-3">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
