@extends('layouts.superadmin')

@section('title', 'Exam Violation Summary')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Exam Violation Summary</h1>
            <p class="text-sm text-gray-500">List of all exam violations across all schools and exams.</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-4 mb-3 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium mb-1 text-gray-600" for="school_id">School</label>
            <select id="school_id" name="school_id" class="form-select rounded-lg border-gray-300 text-sm w-48">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" @if(request('school_id') == $school->id) selected @endif>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium mb-1 text-gray-600" for="search">Search</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-input rounded-lg border-gray-300 text-sm w-52" placeholder="Student name, admission no, exam...">
        </div>
        <div>
            <button type="submit" class="inline-flex items-center text-sm px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                <i class="bi bi-search mr-2"></i> Filter
            </button>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-x-auto">
        <table class="min-w-full table-auto text-sm text-left">
            <thead class="bg-gray-50 border-b text-gray-700">
                <tr>
                    <th class="px-6 py-3">Student</th>
                    <th class="px-6 py-3">Admission No</th>
                    <th class="px-6 py-3">Exam</th>
                    <th class="px-6 py-3">Violation Type</th>
                    <th class="px-6 py-3">Occurred At</th>
                    <th class="px-6 py-3">IP Address</th>
                    <th class="px-6 py-3">School</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($violations as $index => $violation)
                <tr>
                    <td class="px-6 py-3">{{ $violation->user->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $violation->user->admission_number ?? '—' }}</td>
                    <td class="px-6 py-3">
                        @if($violation->attempt && $violation->attempt->exam)
                            <div class="font-semibold text-gray-800">{{ $violation->attempt->exam->title }}</div>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 capitalize">
                        <span class="inline-flex px-2 py-1 text-xs rounded bg-red-100 text-red-800 font-semibold">{{ $violation->type }}</span>
                    </td>
                    <td class="px-6 py-3">{{ $violation->occurred_at ? $violation->occurred_at->format('M d, Y H:i:s') : '—' }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $violation->ip_address ?? '—' }}</td>
                    <td class="px-6 py-3">
                        {{ $violation->attempt && $violation->attempt->exam && $violation->attempt->exam->school ? $violation->attempt->exam->school->name : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">No violations found for the selected criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $violations->withQueryString()->links() }}
    </div>
</div>
@endsection