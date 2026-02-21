@extends('layouts.student')
@section('title','My Results')

@section('content')

@php
$total = $attempts->count();
$approved = $attempts->where('approval_status','approved')->count();
$pending = $attempts->where('approval_status','pending')->count();
$rejected = $attempts->where('approval_status','rejected')->count();
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden pt-4">

    <!-- ================= Header ================= -->
    <div>
        <h1 class="text-3xl font-semibold text-gray-800 text-center mt-4">
            My Results
        </h1>
        <p class="text-sm text-gray-500 text-center mt-2">
            Track your exam performance and approval status.
        </p>
    </div>


    <!-- ================= Summary Cards ================= -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4 px-6">

        <div class="bg-gray-50 rounded-2xl border border-gray-200 shadow-sm p-6">
            <p class="text-sm text-gray-500">Total Exams</p>
            <h3 class="text-2xl font-semibold text-gray-700 mt-2">{{ $total }}</h3>
        </div>

        <div class="bg-green-50 rounded-2xl border border-green-100 shadow-sm p-6">
            <p class="text-sm text-green-600">Approved</p>
            <h3 class="text-2xl font-semibold text-green-700 mt-2">{{ $approved }}</h3>
        </div>

        <div class="bg-yellow-50 rounded-2xl border border-yellow-100 shadow-sm p-6">
            <p class="text-sm text-yellow-600">Pending</p>
            <h3 class="text-2xl font-semibold text-yellow-700 mt-2">{{ $pending }}</h3>
        </div>

        <div class="bg-red-50 rounded-2xl border border-red-100 shadow-sm p-6">
            <p class="text-sm text-red-600">Rejected</p>
            <h3 class="text-2xl font-semibold text-red-700 mt-2">{{ $rejected }}</h3>
        </div>

    </div>


    <!-- ================= Results Table ================= -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6 px-6">

        <div class="px-6 py-5 border-b bg-gray-50 flex justify-between items-center">
            <h2 class="text-base font-semibold text-gray-700">
                Exam Results
            </h2>
            <span class="text-xs text-gray-400">
                Updated automatically
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <!-- Table Head -->
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-8 py-4 text-left font-semibold">Exam</th>
                        <th class="px-8 py-4 text-left font-semibold">Score</th>
                        <th class="px-8 py-4 text-left font-semibold">Status</th>
                        <th class="px-8 py-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="divide-y divide-gray-100">

                    @forelse($attempts as $attempt)

                    <tr class="hover:bg-gray-50 transition duration-150">

                        <!-- Exam -->
                        <td class="px-8 py-6 font-medium text-gray-800">
                            {{ $attempt->exam->title }}
                        </td>

                        <!-- Score -->
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full">
                                {{ $attempt->score ?? '-' }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-8 py-6">

                            @if($attempt->approval_status === 'approved')
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                ● Approved
                            </span>

                            @elseif($attempt->approval_status === 'pending')
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">
                                ● Pending
                            </span>

                            @else
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                ● Rejected
                            </span>
                            @endif

                        </td>

                        <!-- Action -->
                        <td class="px-8 py-6 text-right">

                            <a href="{{ route('student.result',$attempt->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium rounded-xl
                                          bg-indigo-600 text-white hover:bg-indigo-700
                                          transition shadow-sm">
                                View Details →
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="px-8 py-14 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-lg">No results available</span>
                                <span class="text-xs text-gray-400">
                                    Once you complete exams, results will appear here.
                                </span>
                            </div>
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection