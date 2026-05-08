@extends('layouts.admin')

@section('title', 'Violations: ' . $exam->title)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Violation Report</h1>
            <p class="text-sm text-gray-500">{{ $exam->title }} • Class {{ $exam->class }} • {{ $exam->subject }}</p>
        </div>
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center">
            <a href="{{ route('admin.reports.exams.violations.export', $exam->id) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-green-300 bg-green-50 px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="{{ route('admin.reports.exams') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Back to Reports
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="space-y-4 p-4 sm:hidden">
            @forelse($violations as $violation)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-base font-semibold text-gray-800">{{ $violation->student_name }}</div>
                            <div class="mt-1 text-xs text-gray-500">{{ $violation->admission_number }}</div>
                        </div>
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold capitalize text-red-800">{{ str_replace('_', ' ', $violation->type) }}</span>
                    </div>
                    <div class="mt-4 space-y-2 text-sm text-gray-600">
                        <div><span class="text-gray-400">Time:</span> {{ \Carbon\Carbon::parse($violation->occurred_at)->format('d M Y H:i:s') }}</div>
                        <div><span class="text-gray-400">IP:</span> {{ $violation->ip_address }}</div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">No violations recorded for this exam.</div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto sm:block">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">Student</th>
                        <th class="px-5 py-3 text-left">Violation Type</th>
                        <th class="px-5 py-3 text-left">Time</th>
                        <th class="px-5 py-3 text-left">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($violations as $violation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-800">{{ $violation->student_name }}</div>
                                <div class="text-xs text-gray-500">{{ $violation->admission_number }}</div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium capitalize text-red-800">{{ str_replace('_', ' ', $violation->type) }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($violation->occurred_at)->format('d M Y H:i:s') }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $violation->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">No violations recorded for this exam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($violations->hasPages())
            <div class="border-t px-4 py-3">
                {{ $violations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
