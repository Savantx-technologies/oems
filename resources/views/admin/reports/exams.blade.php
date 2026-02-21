@extends('layouts.admin')

@section('title', 'Exam Reports')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Exam Reports</h1>
            <p class="text-sm text-gray-500">View performance analytics for exams</p>
        </div>
        
        <form method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exams..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-64">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
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
                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $exam->title }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-gray-800">Class {{ $exam->class }}</div>
                            <div class="text-xs text-gray-500">{{ $exam->subject }}</div>
                        </td>
                        <td class="px-5 py-3 capitalize">
                            {{ $exam->exam_type }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $exam->attempts_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            @if($exam->schedule)
                                {{ $exam->schedule->start_at->format('d M Y') }}
                            @else
                                <span class="text-xs text-gray-400">Unscheduled</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.reports.exams.detail', $exam->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                View Report
                            </a>
                            <a href="{{ route('admin.reports.exams.violations', $exam->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 ml-2">
                                Violations
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No exams found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($exams->hasPages())
        <div class="px-4 py-3 border-t">
            {{ $exams->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
