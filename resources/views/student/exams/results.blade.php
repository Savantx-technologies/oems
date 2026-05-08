@extends('layouts.student')
@section('title', 'My Results')

@section('content')
@php
    $total = $grouped->count();
    $approved = 0;
    $pending = 0;
    $rejected = 0;

    foreach ($grouped as $examGroup) {
        if ($examGroup->contains('approval_status', 'pending')) {
            $pending++;
        } elseif ($examGroup->contains('approval_status', 'rejected')) {
            $rejected++;
        } else {
            $approved++;
        }
    }
@endphp

<div class="mx-auto max-w-7xl space-y-6">
    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-900 to-blue-700 px-5 py-6 text-white shadow-xl sm:px-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Performance Tracker</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">My Results</h1>
                <p class="mt-2 max-w-2xl text-sm text-blue-100/85">Track approved scorecards, pending checks, and every published exam result in one place.</p>
            </div>
            <div class="rounded-2xl bg-white/10 px-4 py-3 text-sm backdrop-blur">
                <span class="text-blue-100/80">Total exam groups</span>
                <div class="mt-1 text-2xl font-bold">{{ $total }}</div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Total Exams</p>
            <h3 class="mt-3 text-2xl font-bold text-gray-800">{{ $total }}</h3>
        </div>
        <div class="rounded-2xl border border-green-100 bg-green-50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-700">Approved</p>
            <h3 class="mt-3 text-2xl font-bold text-green-800">{{ $approved }}</h3>
        </div>
        <div class="rounded-2xl border border-yellow-100 bg-yellow-50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-yellow-700">Pending</p>
            <h3 class="mt-3 text-2xl font-bold text-yellow-800">{{ $pending }}</h3>
        </div>
        <div class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-red-700">Rejected</p>
            <h3 class="mt-3 text-2xl font-bold text-red-800">{{ $rejected }}</h3>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Exam Results</h2>
                <p class="text-sm text-gray-500">Updated automatically as reviews are completed.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500">Live status</span>
        </div>

        <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Exam</th>
                        <th class="px-6 py-4 text-left font-semibold">Score</th>
                        <th class="px-6 py-4 text-left font-semibold">Status</th>
                        <th class="px-6 py-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($grouped as $key => $examGroup)
                        @php
                            [$title, $session] = explode('|', $key);
                            $firstAttempt = $examGroup->first();

                            if ($examGroup->contains('approval_status', 'pending')) {
                                $status = 'pending';
                            } elseif ($examGroup->contains('approval_status', 'rejected')) {
                                $status = 'rejected';
                            } else {
                                $status = 'approved';
                            }

                            $totalObtained = $examGroup->sum('score');
                            $totalMax = $examGroup->sum(function ($item) {
                                return $item->exam->total_marks;
                            });

                            $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                        @endphp
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-5">
                                <div class="font-semibold text-gray-800">{{ $title }}</div>
                                <div class="mt-1 text-xs text-gray-400">Session: {{ $session }}</div>
                            </td>
                            <td class="px-6 py-5">
                                @if($status === 'approved')
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $totalObtained }} / {{ $totalMax }} ({{ $percentage }}%)
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                                        Not Published
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                @if($status === 'approved')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Approved</span>
                                @elseif($status === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                @if($status === 'approved')
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('student.result', $firstAttempt->id) }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-indigo-700">
                                            View Report
                                        </a>
                                        <a href="{{ route('student.marksheet.download', $firstAttempt->id) }}" class="inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-green-700">
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs italic text-gray-400">Awaiting Approval</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center text-gray-400">No results available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-gray-100 lg:hidden">
            @forelse($grouped as $key => $examGroup)
                @php
                    [$title, $session] = explode('|', $key);
                    $firstAttempt = $examGroup->first();

                    if ($examGroup->contains('approval_status', 'pending')) {
                        $status = 'pending';
                    } elseif ($examGroup->contains('approval_status', 'rejected')) {
                        $status = 'rejected';
                    } else {
                        $status = 'approved';
                    }

                    $totalObtained = $examGroup->sum('score');
                    $totalMax = $examGroup->sum(function ($item) {
                        return $item->exam->total_marks;
                    });

                    $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                @endphp
                <article class="space-y-4 px-4 py-5 sm:px-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
                            <p class="mt-1 text-sm text-gray-500">Session: {{ $session }}</p>
                        </div>
                        @if($status === 'approved')
                            <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Approved</span>
                        @elseif($status === 'pending')
                            <span class="inline-flex w-fit items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                        @else
                            <span class="inline-flex w-fit items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Rejected</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-3 rounded-2xl bg-gray-50 p-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Score</p>
                            @if($status === 'approved')
                                <p class="mt-1 text-sm font-semibold text-indigo-700">{{ $totalObtained }} / {{ $totalMax }} ({{ $percentage }}%)</p>
                            @else
                                <p class="mt-1 text-sm font-medium text-gray-500">Not Published</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Review</p>
                            <p class="mt-1 text-sm font-medium text-gray-600">{{ $status === 'approved' ? 'Ready to view and download' : 'Awaiting approval' }}</p>
                        </div>
                    </div>

                    @if($status === 'approved')
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <a href="{{ route('student.result', $firstAttempt->id) }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                                View Report
                            </a>
                            <a href="{{ route('student.marksheet.download', $firstAttempt->id) }}" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                                Download Marksheet
                            </a>
                        </div>
                    @endif
                </article>
            @empty
                <div class="px-6 py-14 text-center text-gray-400">No results available.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
