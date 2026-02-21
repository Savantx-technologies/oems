@extends('layouts.admin')

@section('title', 'Violations: ' . $exam->title)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Violation Report</h1>
            <p class="text-sm text-gray-500">{{ $exam->title }} • Class {{ $exam->class }} • {{ $exam->subject }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.exams.violations.export', $exam->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-green-300 bg-green-50 text-sm text-green-700 hover:bg-green-100">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="{{ route('admin.reports.exams') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                Back to Reports
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize">
                                {{ str_replace('_', ' ', $violation->type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($violation->occurred_at)->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            {{ $violation->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            No violations recorded for this exam.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($violations->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $violations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
