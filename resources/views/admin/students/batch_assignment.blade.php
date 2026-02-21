@extends('layouts.admin')

@section('title', 'Batch Assignment')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Batch Assignment</h1>
            <p class="text-gray-500 text-sm">Assign students to specific grades/batches in bulk.</p>
        </div>
        
        <!-- Search -->
        <form method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..." 
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </form>
    </div>

    <form action="{{ route('admin.students.batch.update') }}" method="POST" x-data="{ selected: [] }">
        @csrf
        
        <!-- Action Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex items-center gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Selected To:</label>
                <div class="flex gap-2">
                    <input type="text" name="grade" placeholder="Enter Grade / Batch (e.g. Class 10-A)" required
                           class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" :disabled="selected.length === 0"
                            class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                        Update Batch
                    </button>
                </div>
            </div>
            <div class="text-sm text-gray-500 border-l pl-4">
                <span x-text="selected.length">0</span> students selected
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-800 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 w-10">
                                <input type="checkbox" 
                                       @change="selected = $el.checked ? [{{ $students->pluck('id')->implode(',') }}] : []"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Admission No</th>
                            <th class="px-6 py-3">Current Grade/Batch</th>
                            <th class="px-6 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" x-model="selected"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/'.$student->photo) }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                    {{ $student->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $student->admission_number ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $student->grade ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $student->grade ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $student->email }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No students found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $students->withQueryString()->links() }}
            </div>
        </div>
    </form>
</div>
@endsection
