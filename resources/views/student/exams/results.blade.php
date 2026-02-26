@extends('layouts.student')
@section('title','My Results')

@section('content')

@php
$total = $grouped->count();

$approved = 0;
$pending = 0;
$rejected = 0;

foreach ($grouped as $examGroup) {

if ($examGroup->contains('approval_status','pending')) {
$pending++;
} elseif ($examGroup->contains('approval_status','rejected')) {
$rejected++;
} else {
$approved++;
}
}
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

                    @forelse($grouped as $key => $examGroup)

                    @php
                    [$title, $session] = explode('|', $key);
                    $firstAttempt = $examGroup->first();

                    // Status logic
                    if ($examGroup->contains('approval_status','pending')) {
                    $status = 'pending';
                    } elseif ($examGroup->contains('approval_status','rejected')) {
                    $status = 'rejected';
                    } else {
                    $status = 'approved';
                    }

                    // Calculate total obtained & total max
                    $totalObtained = $examGroup->sum('score');
                    $totalMax = $examGroup->sum(function($item){
                    return $item->exam->total_marks;
                    });

                    $percentage = $totalMax > 0
                    ? round(($totalObtained / $totalMax) * 100, 2)
                    : 0;
                    @endphp

                    <tr class="hover:bg-gray-50 transition">

                        <!-- Exam Title -->
                        <td class="px-8 py-6 font-medium text-gray-800">
                            {{ $title }}
                            <div class="text-xs text-gray-400 mt-1">
                                Session: {{ $session }}
                            </div>
                        </td>

                        <!-- Score Column -->
                        <td class="px-8 py-6">
                            @if($status === 'approved')
                            <span class="px-3 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full">
                                {{ $totalObtained }} / {{ $totalMax }}
                                ({{ $percentage }}%)
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-500 rounded-full">
                                Not Published
                            </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-8 py-6">
                            @if($status === 'approved')
                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">● Approved</span>
                            @elseif($status === 'pending')
                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">● Pending</span>
                            @else
                            <span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">● Rejected</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-8 py-6 text-right space-x-2">

                            @if($status === 'approved')

                            <a href="{{ route('student.result', $firstAttempt->id) }}" class="px-4 py-2 text-xs font-medium rounded-xl
               bg-indigo-600 text-white hover:bg-indigo-700">
                                View →
                            </a>

                            <a href="{{ route('student.marksheet.download', $firstAttempt->id) }}" class="px-4 py-2 text-xs font-medium rounded-xl
               bg-green-600 text-white hover:bg-green-700">
                                Download
                            </a>

                            @else
                            <span class="text-xs text-gray-400 italic">
                                Awaiting Approval
                            </span>
                            @endif

                        </td>

                    </tr>

                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-14 text-center text-gray-400">
                            No results available.
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection