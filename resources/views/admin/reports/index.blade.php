@extends('layouts.admin')

@section('title', 'Reports Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">Reports Center</h1>
        <p class="text-sm text-gray-500">Access all exam reports, performance analytics, and violation logs from one place.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">Total Exams Created</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalExams }}</div>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xl">
                <i class="bi bi-file-earmark-text"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">Total Attempts</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalAttempts }}</div>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 text-xl">
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500 mb-1">Recorded Violations</div>
                <div class="text-2xl font-bold text-gray-800">{{ $totalViolations }}</div>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-600 text-xl">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Exam Reports -->
        <a href="{{ route('admin.reports.exams') }}" class="group block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="bi bi-journal-check text-2xl text-blue-600"></i>
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-blue-600 transition-colors">Exam Reports</h3>
                </div>
                <p class="text-sm text-gray-500">View detailed lists of exams, student attempts, scores, and individual result cards.</p>
            </div>
        </a>

        <!-- Performance Analytics -->
        <a href="{{ route('admin.reports.analytics') }}" class="group block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
            <div class="h-2 bg-indigo-500"></div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="bi bi-graph-up-arrow text-2xl text-indigo-600"></i>
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-indigo-600 transition-colors">Performance Analytics</h3>
                </div>
                <p class="text-sm text-gray-500">Analyze school-wide performance trends, subject-wise averages, and class comparisons.</p>
            </div>
        </a>

        <!-- Violation Reports (Links to Exams for now) -->
        <a href="{{ route('admin.reports.exams') }}" class="group block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
            <div class="h-2 bg-red-500"></div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-3">
                    <i class="bi bi-shield-exclamation text-2xl text-red-600"></i>
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-red-600 transition-colors">Violation Reports</h3>
                </div>
                <p class="text-sm text-gray-500">Review flagged incidents and academic integrity violations for specific exams.</p>
            </div>
        </a>
    </div>
</div>
@endsection
