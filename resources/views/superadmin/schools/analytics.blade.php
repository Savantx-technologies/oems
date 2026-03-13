@extends('layouts.superadmin')

@section('title', 'School Analytics')

@section('content')
<div class="min-h-screen bg-gradient-to-tr from-slate-50 to-blue-50 pt-12 pb-16">
    <div class="max-w-7xl mx-auto px-2 md:px-6">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <i class="bi bi-bar-chart-line text-blue-600 text-3xl"></i>
                <div>
                    <div class="text-xs font-semibold text-slate-500 mb-1">Super Admin Panel</div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">School Analytics</h2>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full"><i class="bi bi-building text-2xl"></i></div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</div>
                    <div class="text-slate-500 font-medium">Total Schools</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-full"><i class="bi bi-check-circle text-2xl"></i></div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ $stats['active'] }}</div>
                    <div class="text-slate-500 font-medium">Active Schools</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4">
                <div class="bg-slate-100 text-slate-600 p-3 rounded-full"><i class="bi bi-pause-circle text-2xl"></i></div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ $stats['inactive'] }}</div>
                    <div class="text-slate-500 font-medium">Inactive Schools</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4">
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full"><i class="bi bi-file-earmark-text text-2xl"></i></div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ $stats['draft'] }}</div>
                    <div class="text-slate-500 font-medium">Drafts</div>
                </div>
            </div>
        </div>

        <!-- Charts & Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Breakdown by Board -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-lg text-slate-800 mb-4">Breakdown by Board</h3>
                <div class="space-y-3">
                    @forelse($schoolsByBoard as $item)
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">{{ $item->board }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-4">No data available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Breakdown by Type -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-lg text-slate-800 mb-4">Breakdown by Type</h3>
                <div class="space-y-3">
                    @forelse($schoolsByType as $item)
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">{{ $item->type }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-4">No data available.</p>
                    @endforelse
                </div>
            </div>

            <!-- Top 5 Schools by Students -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-lg text-slate-800 mb-4">Top 5 Schools by Student Count</h3>
                <ul class="divide-y divide-slate-100">
                    @forelse($topSchoolsByStudents as $school)
                        <li class="py-3 flex items-center justify-between">
                            <span class="text-blue-700 font-medium">{{ $school->name }}</span>
                            <span class="bg-blue-50 text-blue-700 font-semibold px-3 py-1 rounded-full text-sm">{{ $school->students_count }} students</span>
                        </li>
                    @empty
                        <p class="text-slate-400 text-center py-4">No schools with students found.</p>
                    @endforelse
                </ul>
            </div>

            <!-- Top 5 Schools by Exams -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="font-semibold text-lg text-slate-800 mb-4">Top 5 Schools by Exam Count</h3>
                <ul class="divide-y divide-slate-100">
                    @forelse($topSchoolsByExams as $school)
                        <li class="py-3 flex items-center justify-between">
                            <span class="text-blue-700 font-medium">{{ $school->name }}</span>
                            <span class="bg-blue-50 text-blue-700 font-semibold px-3 py-1 rounded-full text-sm">{{ $school->exams_count }} exams</span>
                        </li>
                    @empty
                        <p class="text-slate-400 text-center py-4">No schools with exams found.</p>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
