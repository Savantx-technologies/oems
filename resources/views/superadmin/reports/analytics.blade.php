@extends('layouts.superadmin')

@section('title', 'Performance Analytics')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Performance Analytics</h1>
        <p class="text-sm text-gray-500">An overview of the entire platform's activity.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <i class="bi bi-building text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_schools'] }}</div>
                <div class="text-sm font-medium text-gray-500">Total Schools</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                <i class="bi bi-person-badge text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_admins'] }}</div>
                <div class="text-sm font-medium text-gray-500">Total Admins</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
                <i class="bi bi-people text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_students'] }}</div>
                <div class="text-sm font-medium text-gray-500">Total Students</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                <i class="bi bi-file-earmark-text text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_exams'] }}</div>
                <div class="text-sm font-medium text-gray-500">Total Exams Created</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-red-100 text-red-600 p-3 rounded-full">
                <i class="bi bi-broadcast text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['live_exams'] }}</div>
                <div class="text-sm font-medium text-gray-500">Live Exams Now</div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-5">
            <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                <i class="bi bi-pencil-square text-2xl"></i>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_attempts'] }}</div>
                <div class="text-sm font-medium text-gray-500">Total Exam Attempts</div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Schools -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Top Schools by Participation</h3>
            </div>
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium border-b">
                    <tr>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3 text-right">Attempts</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topSchools as $school)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $school->name }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $school->attempts_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr></tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t bg-gray-50 text-right">
                <a href="{{ route('superadmin.reports.schools') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">View All Schools &rarr;</a>
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-800">Recently Created Exams</h3>
            </div>
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium border-b">
                    <tr>
                        <th class="px-6 py-3">Exam</th>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3 text-right">Attempts</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentExams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-800">{{ $exam->title }}</div>
                                <div class="text-xs text-gray-500">{{ $exam->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $exam->school->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $exam->attempts_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No exams found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t bg-gray-50 text-right">
                <a href="{{ route('superadmin.reports.exams') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">View All Exams &rarr;</a>
            </div>
        </div>
    </div>

</div>
@endsection
