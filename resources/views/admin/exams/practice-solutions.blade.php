@extends('layouts.admin')

@section('title','Practice Solutions')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            Practice Exam Solutions
        </h1>
        <p class="text-sm text-gray-500">
            View solutions for all practice exams
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Exam</th>
                        <th class="px-5 py-3 text-left">Class</th>
                        <th class="px-5 py-3 text-left">Subject</th>
                        <th class="px-5 py-3 text-left">Total Questions</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($exams as $exam)

                    <tr class="hover:bg-gray-50">

                        <td class="px-5 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-3 font-medium text-gray-800">
                            {{ $exam->title }}
                        </td>

                        <td class="px-5 py-3">
                            {{ $exam->class }}
                        </td>

                        <td class="px-5 py-3">
                            {{ $exam->subject }}
                        </td>

                        <td class="px-5 py-3">
                            {{ is_array($exam->selected_questions) 
                                ? count($exam->selected_questions) 
                                : 0 }}
                        </td>

                        <td class="px-5 py-3 text-right">

                            <a href="{{ route('admin.exams.solution', $exam->id) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                View Solution
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No practice exams available.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t">
            {{ $exams->links() }}
        </div>

    </div>

</div>

@endsection
