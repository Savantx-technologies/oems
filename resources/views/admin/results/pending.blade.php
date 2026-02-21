@extends('layouts.admin')
@section('title','Result Approvals')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    <!-- ================= Header ================= -->
    <div class="flex justify-center items-start border-bottom border-gray-200 pb-6">
        <div>
            <p class="text-sm text-gray-500 mt-1 text-center">
                Review and approve submitted exam results.
            </p>
        </div>
    </div>

    <!-- ================= Success Message ================= -->
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-6 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    <!-- ================= Table ================= -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <table class="min-w-full text-sm">

            <!-- Table Head -->
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-xs uppercase tracking-wider text-gray-600">
                    <th class="px-6 py-4 text-left font-semibold">Student</th>
                    <th class="px-6 py-4 text-left font-semibold">Exam</th>
                    <th class="px-6 py-4 text-left font-semibold">Score</th>
                    <th class="px-6 py-4 text-left font-semibold">Approval Status</th>
                    <th class="px-6 py-4 text-left font-semibold">Action</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-gray-100">

                @forelse($attempts as $attempt)

                <tr class="hover:bg-gray-50 transition duration-150">

                    <!-- Student -->
                    <td class="px-6 py-5 font-medium text-gray-800">
                        {{ $attempt->user->name }}
                    </td>

                    <!-- Exam -->
                    <td class="px-6 py-5 text-gray-600">
                        {{ $attempt->exam->title }}
                    </td>

                    <!-- Score -->
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">
                            {{ $attempt->score }}
                        </span>
                    </td>

                    <!-- Approval Status -->
                    <td class="px-6 py-5">
                        @if($attempt->approval_status === 'approved')
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                Approved
                            </span>
                        @elseif($attempt->approval_status === 'rejected')
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                Rejected
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                                Pending
                            </span>
                        @endif
                    </td>

                    <!-- Action -->
                    <td class="px-6 py-5 space-x-2">

                        <!-- Approve Button -->
                        <form method="POST"
                              action="{{ route('admin.results.approve',$attempt->id) }}"
                              class="inline">
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
                                @if($attempt->approval_status === 'approved')
                                    Approved
                                @else
                                    Approve
                                @endif
                            </button>
                        </form>


                        <!-- Reject Button -->
                        <form method="POST"
                              action="{{ route('admin.results.reject',$attempt->id) }}"
                              class="inline">
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
                                @if($attempt->approval_status === 'rejected')
                                    Rejected
                                @else
                                    Reject
                                @endif
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No results found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection