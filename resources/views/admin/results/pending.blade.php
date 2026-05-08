@extends('layouts.admin')
@section('title','Result Approvals')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex justify-center border-b border-gray-200 pb-6">
        <p class="text-center text-sm text-gray-500">Review and approve submitted exam results.</p>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-6 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="space-y-4 p-4 sm:hidden">
            @forelse($attempts as $attempt)
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-base font-semibold text-gray-800">{{ $attempt->user->name }}</div>
                            <div class="mt-1 text-sm text-gray-500">{{ $attempt->exam->title }}</div>
                        </div>
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $attempt->score }}</span>
                    </div>

                    <div class="mt-4">
                        @if($attempt->approval_status === 'approved')
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Approved</span>
                        @elseif($attempt->approval_status === 'rejected')
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">Rejected</span>
                        @else
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">Pending</span>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.results.approve',$attempt->id) }}">
                            @csrf
                            <button
                                @if($attempt->approval_status !== 'pending') disabled @endif
                                class="rounded-xl px-4 py-2 text-xs font-medium shadow-sm
                                    @if($attempt->approval_status === 'approved')
                                        bg-green-100 text-green-700
                                    @elseif($attempt->approval_status === 'rejected')
                                        bg-gray-100 text-gray-400
                                    @else
                                        bg-green-600 text-white hover:bg-green-700
                                    @endif">
                                @if($attempt->approval_status === 'approved') Approved @else Approve @endif
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.results.reject',$attempt->id) }}">
                            @csrf
                            <button
                                @if($attempt->approval_status !== 'pending') disabled @endif
                                class="rounded-xl px-4 py-2 text-xs font-medium shadow-sm
                                    @if($attempt->approval_status === 'rejected')
                                        bg-red-100 text-red-700
                                    @elseif($attempt->approval_status === 'approved')
                                        bg-gray-100 text-gray-400
                                    @else
                                        bg-red-600 text-white hover:bg-red-700
                                    @endif">
                                @if($attempt->approval_status === 'rejected') Rejected @else Reject @endif
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-400">No results found.</div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto sm:block">
            <table class="min-w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr class="text-xs uppercase tracking-wider text-gray-600">
                        <th class="px-6 py-4 text-left font-semibold">Student</th>
                        <th class="px-6 py-4 text-left font-semibold">Exam</th>
                        <th class="px-6 py-4 text-left font-semibold">Score</th>
                        <th class="px-6 py-4 text-left font-semibold">Approval Status</th>
                        <th class="px-6 py-4 text-left font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attempts as $attempt)
                        <tr class="transition duration-150 hover:bg-gray-50">
                            <td class="px-6 py-5 font-medium text-gray-800">{{ $attempt->user->name }}</td>
                            <td class="px-6 py-5 text-gray-600">{{ $attempt->exam->title }}</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">{{ $attempt->score }}</span>
                            </td>
                            <td class="px-6 py-5">
                                @if($attempt->approval_status === 'approved')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Approved</span>
                                @elseif($attempt->approval_status === 'rejected')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">Rejected</span>
                                @else
                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">Pending</span>
                                @endif
                            </td>
                            <td class="space-x-2 px-6 py-5">
                                <form method="POST" action="{{ route('admin.results.approve',$attempt->id) }}" class="inline">
                                    @csrf
                                    <button
                                        @if($attempt->approval_status !== 'pending') disabled @endif
                                        class="px-4 py-2 text-xs font-medium rounded-lg transition shadow-sm
                                            @if($attempt->approval_status === 'approved')
                                                bg-green-100 text-green-700 cursor-not-allowed
                                            @elseif($attempt->approval_status === 'rejected')
                                                bg-gray-100 text-gray-400 cursor-not-allowed
                                            @else
                                                bg-green-600 text-white hover:bg-green-700
                                            @endif">
                                        @if($attempt->approval_status === 'approved') Approved @else Approve @endif
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.results.reject',$attempt->id) }}" class="inline">
                                    @csrf
                                    <button
                                        @if($attempt->approval_status !== 'pending') disabled @endif
                                        class="px-4 py-2 text-xs font-medium rounded-lg transition shadow-sm
                                            @if($attempt->approval_status === 'rejected')
                                                bg-red-100 text-red-700 cursor-not-allowed
                                            @elseif($attempt->approval_status === 'approved')
                                                bg-gray-100 text-gray-400 cursor-not-allowed
                                            @else
                                                bg-red-600 text-white hover:bg-red-700
                                            @endif">
                                        @if($attempt->approval_status === 'rejected') Rejected @else Reject @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">No results found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
