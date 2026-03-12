@extends('layouts.admin')
@section('title','Manual Exam Checking')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Manual Exam Checking</h1>
            <p class="text-sm text-gray-500">Review student exam submissions and approve results.</p>
        </div>
    </div>


    <!-- Table Card -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Student</th>
                        <th class="px-6 py-4 text-left">Exam</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach($attempts as $attempt)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- Student -->
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $attempt->user->name }}
                        </td>

                        <!-- Exam -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $attempt->exam->title }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">

                            @if($attempt->approval_status == 'pending')
                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                Pending
                            </span>

                            @elseif($attempt->approval_status == 'approved')
                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                Approved
                            </span>

                            @else
                            <span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                                Rejected
                            </span>
                            @endif

                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-right">

                            <a href="{{ route('admin.results.viewAttempt',$attempt->id) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 transition">
                                Check Paper
                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    <!-- Pagination -->
    <div class="mt-6">
        {{ $attempts->links() }}
    </div>

</div>

@endsection