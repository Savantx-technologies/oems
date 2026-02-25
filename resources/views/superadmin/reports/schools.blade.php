@extends('layouts.superadmin')

@section('title', 'School-wise Reports')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">School-wise Reports</h1>
            <p class="text-sm text-gray-500">Overview of schools and their activity statistics.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                    <tr>
                        <th class="px-6 py-3">School Name</th>
                        <th class="px-6 py-3">Code</th>
                        <th class="px-6 py-3 text-center">Students</th>
                        <th class="px-6 py-3 text-center">Exams</th>
                        <th class="px-6 py-3 text-center">Attempts</th>
                        <th class="px-6 py-3 text-center">Avg Score</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Joined</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schools as $school)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">
                                {{ $school->name }}
                                <div class="text-xs text-gray-500">{{ $school->city ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $school->code }}</td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $school->students_count }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $school->exams_count }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $school->attempts_count }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="font-medium text-gray-700">
                                    {{ number_format($school->attempts_avg_score, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if($school->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($school->status === 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($school->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">
                                {{ $school->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('superadmin.reports.exams', ['school_id' => $school->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium mr-2">
                                    View Exams
                                </a>
                                <a href="{{ route('superadmin.schools.edit', $school->id) }}" class="text-gray-500 hover:text-gray-900 text-xs font-medium">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                No schools found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $schools->links() }}
        </div>
    </div>
</div>
@endsection