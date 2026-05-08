@extends('layouts.admin')
@section('title','Manual Exam Checking')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">

    @if(!isset($class))
        <div class="rounded-3xl border bg-white p-4 shadow-sm sm:p-6">
            <h2 class="mb-6 text-center text-lg font-semibold text-gray-800">Select Class For Manual Checking</h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">
                @forelse($classes as $className => $classAttempts)
                    <a href="{{ route('admin.results.attempts',['class'=>$className]) }}"
                        class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5 text-center transition hover:shadow-md">
                        <div class="text-xl font-bold text-indigo-600">Class {{ $className }}</div>
                        <div class="mt-2 text-sm text-gray-500">{{ $classAttempts->count() }} Attempts</div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No attempts found.</div>
                @endforelse
            </div>
        </div>
    @else
        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Class {{ $class }} Student Attempts</h2>
            <a href="{{ route('admin.results.attempts') }}" class="text-sm text-indigo-600 hover:underline">Back to Classes</a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg">
            <div class="space-y-4 p-4 sm:hidden">
                @forelse($students as $studentAttempts)
                    @foreach($studentAttempts as $attempt)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-base font-semibold text-gray-800">{{ $attempt->user->name }}</div>
                                    <div class="mt-1 text-sm text-gray-500">{{ $attempt->exam->title }}</div>
                                </div>
                                @if($attempt->approval_status == 'pending')
                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                                @elseif($attempt->approval_status == 'approved')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Approved</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Rejected</span>
                                @endif
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('admin.results.viewAttempt',$attempt->id) }}"
                                    class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-blue-700">
                                    Check Paper
                                </a>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">No attempts found for this class.</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-600">
                        <tr>
                            <th class="px-6 py-4 text-left">Student</th>
                            <th class="px-6 py-4 text-left">Exam</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $studentAttempts)
                            @foreach($studentAttempts as $attempt)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-800">{{ $attempt->user->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $attempt->exam->title }}</td>
                                    <td class="px-6 py-4">
                                        @if($attempt->approval_status == 'pending')
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs text-yellow-700">Pending</span>
                                        @elseif($attempt->approval_status == 'approved')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs text-green-700">Approved</span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs text-red-700">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.results.viewAttempt',$attempt->id) }}"
                                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-blue-700">
                                            Check Paper
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">No attempts found for this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@endsection
