@extends('layouts.admin')

@section('title', 'View Students')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('bulk_report'))
        @php $report = session('bulk_report'); @endphp
        <div class="bg-blue-100 border border-blue-300 text-blue-800 rounded-md px-6 py-4 mb-6 relative shadow">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.48-4.7l5-5a1 1 0 10-1.42-1.42l-3.3 3.29-1.3-1.3a1 1 0 00-1.42 1.42l2 2a1 1 0 001.42 0z" clip-rule="evenodd"/></svg>
                <span class="font-semibold">Bulk Upload Report</span>
            </div>
            <p class="mb-2">The bulk import process has completed. Here is the summary:</p>
            <ul class="list-disc ml-6 space-y-1 mb-2">
                <li><span class="font-bold text-green-700">{{ $report['imported'] }} students</span> were successfully imported.</li>
                <li><span class="font-bold text-yellow-700">{{ $report['skipped'] }} students</span> were skipped because their email already exists.</li>
                <li><span class="font-bold text-red-700">{{ $report['failed'] }} rows</span> failed to import due to missing required data.</li>
            </ul>
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-lg text-blue-500 hover:text-blue-700 transition-colors">&times;</button>
        </div>
    @endif
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="flex flex-wrap gap-2 justify-between items-center border-b px-6 py-4">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-blue-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5m6-6a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Students List
                </h2>
                <span class="ml-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-medium">{{ $students->total() }} Students</span>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="#" class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13.828 10.172a4 4 0 010 5.656m1.415-7.07A6 6 0 1018 12a5.97 5.97 0 00-2.757-4.992z" /></svg>
                    Sign Up Link
                </a>
                <a href="{{ route('admin.students.bulk_create') }}" class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/><path d="M9 12l3 3 3-3M12 15V3"/></svg>
                    Bulk Upload
                </a>
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-700 text-white hover:bg-blue-800 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4"/></svg>
                    Add Student
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            {{-- Filter or Search --}}
            <div class="mb-2">
                <form method="GET" action="" class="flex flex-wrap gap-3 items-end w-full" autocomplete="off">
                    <div class="flex-grow max-w-xs">
                        <label for="search" class="block mb-1 text-xs font-medium text-gray-500">Search by Name/Email</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="block w-full rounded-md border border-gray-300 text-gray-900 py-1.5 px-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-400" placeholder="Search student">
                    </div>
                    <div class="w-full sm:w-52">
                        <label for="grade" class="block mb-1 text-xs font-medium text-gray-500">Filter by Class</label>
                        <select name="grade" id="grade" class="block w-full rounded-md border border-gray-300 text-gray-900 py-1.5 px-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-400">
                            <option value="">All Classes</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white border border-blue-600 text-blue-700 hover:bg-blue-50 transition text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Apply
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="inline-flex items-center px-2 py-1.5 text-blue-500 rounded-md hover:bg-blue-50 text-sm transition">Reset</a>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white mt-3 divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="pl-6 py-3 text-left text-xs font-semibold text-gray-700">Name</th>
                            <th class="py-3 text-left text-xs font-semibold text-gray-700">Admission No</th>
                            <th class="py-3 text-left text-xs font-semibold text-gray-700">Grade</th>
                            <th class="py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                            <th class="pr-6 py-3 text-right text-xs font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                        <tr>
                            <td class="pl-6 py-3">
                                <div class="flex items-center">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" alt="" class="rounded-full shadow w-11 h-11 object-cover mr-3">
                                    @else
                                        <div class="rounded-full bg-blue-700 text-white font-bold flex items-center justify-center mr-3 shadow" style="width: 42px; height: 42px;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">{{ $student->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 12v1a4 4 0 01-8 0v-1"/><path d="M12 3a4 4 0 100 8 4 4 0 000-8zm2 10h-4m0 0a4 4 0 01-4-4V7a4 4 0 018 0v2a4 4 0 01-4 4z"/></svg>
                                            {{ $student->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($student->admission_number)
                                    <span class="inline-block px-2 py-1 rounded bg-cyan-100 text-cyan-800 border border-cyan-200 text-xs font-medium">{{ $student->admission_number }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td>
                                @if($student->grade)
                                    <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-800 border border-green-200 text-xs font-medium">{{ $student->grade }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td>
                                @if($student->status === 'active')
                                    <span class="inline-flex items-center px-3 py-2 rounded bg-green-600 text-white font-semibold border border-green-700 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path d="M16.707 7.293a1 1 0 00-1.414 0L9 13.586l-2.293-2.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"/></svg>
                                        Active
                                    </span>
                                @elseif($student->status === 'inactive')
                                    <span class="inline-flex items-center px-3 py-2 rounded bg-red-600 text-white font-semibold border border-red-700 text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 20 20" stroke="currentColor"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 112 0v4a1 1 0 01-2 0V7zm1 8a1 1 0 100-2 1 1 0 000 2z"/></svg>
                                        Inactive
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-2 rounded bg-gray-400 text-white font-semibold border border-gray-500 text-xs">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="pr-6 py-3 text-right">
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="inline-flex items-center p-1.5 text-blue-600 hover:text-blue-800 rounded hover:bg-blue-50 transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15.232 5.232l3.536 3.536M9 13.5V19h5.5L20.707 13.793a1 1 0 000-1.414l-6.293-6.293a1 1 0 00-1.414 0z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center py-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9.172 19.172a4 4 0 001.415-7.07M15.829 12.414A6 6 0 1118 12a5.97 5.97 0 00-2.171.414z"/></svg>
                                        <span>No students found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($students->hasPages())
            <div class="bg-gray-50 border-t px-6 py-4">
                <div class="flex justify-between items-center flex-wrap">
                    <div class="text-gray-500 text-sm mb-2 sm:mb-0">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                    </div>
                    <div>
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
