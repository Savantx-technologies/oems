@extends('layouts.superadmin')

@section('title', 'Exam Reports')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Exam Reports</h1>
            <p class="text-sm text-gray-500">Review and analyze all exams across all schools.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <form method="GET" class="flex items-center gap-4">
            <div class="flex-1">
                <label for="school_id" class="text-sm font-medium text-gray-700 sr-only">Filter by School</label>
                <select name="school_id" id="school_id" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="status" class="text-sm font-medium text-gray-700 sr-only">Filter by Status</label>
                <select name="status" id="status" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Filter</button>
            <a href="{{ route('superadmin.reports.exams') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                    <tr>
                        <th class="px-6 py-3">Exam Title</th>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Attempts</th>
                        <th class="px-6 py-3">Total Marks</th>
                        <th class="px-6 py-3">Created At</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">
                            {{ $exam->title }}
                            <div class="text-xs text-gray-500">Class {{ $exam->class }} - {{ $exam->subject }}</div>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $exam->school->name ?? 'N/A' }}</td>
                        <td class="px-6 py-3">
                            @php
                            $statusClasses = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'published' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-red-100 text-red-700',
                            ];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$exam->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($exam->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $exam->attempts->count() }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $exam->total_marks }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">
                            {{ $exam->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('superadmin.exams.show', $exam->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No exams found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $exams->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection