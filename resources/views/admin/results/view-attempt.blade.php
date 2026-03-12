@extends('layouts.admin')
@section('title','Manual Exam Checking')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manual Exam Checking</h1>
        <p class="text-sm text-gray-500">Review student's submitted answers before approving the result.</p>
    </div>

    <!-- Student Info Card -->
    <div class="bg-white shadow rounded-xl p-6 mb-6 border border-gray-100">

        <div class="grid grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-gray-500 uppercase">Student</p>
                <p class="text-lg font-semibold text-gray-800">{{ $attempt->user->name }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase">Exam</p>
                <p class="text-lg font-semibold text-gray-800">{{ $attempt->exam->title }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase">Status</p>

                @if($attempt->approval_status == 'pending')
                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                    Pending Review
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

            </div>

        </div>

    </div>


    @foreach($questions as $index => $question)

    <div class="bg-white shadow rounded-xl p-6 mb-6 border border-gray-100">


        <!-- Question -->
        <div class="flex justify-between mb-4">
            <h3 class="font-semibold text-gray-800">
                Q{{ $index + 1 }}. {{ $question->question_text }}
            </h3>

            <span class="text-sm text-gray-400">
                Marks: {{ $question->marks }}
            </span>
        </div>

        @php
        $studentAnswer = $attempt->answers[$question->id] ?? null;
        @endphp

        <!-- Options -->
        <div class="grid gap-3">

            @foreach(['A','B','C','D'] as $option)

            @php
            $optionText = $question->{'option_'.strtolower($option)};
            $isStudent = $studentAnswer == $option;
            $isCorrect = $question->correct_option == $option;
            @endphp

            @if($optionText)

            <div class="p-3 rounded-lg border

            @if($isStudent && $isCorrect)
                bg-green-100 border-green-500
            @elseif($isStudent)
                bg-red-100 border-red-500
            @elseif($isCorrect)
                bg-green-50 border-green-300
            @else
                bg-gray-50 border-gray-200
            @endif
            ">

                <div class="flex justify-between items-center">

                    <span class="font-medium text-gray-700">
                        {{ $option }}. {{ $optionText }}
                    </span>

                    <div class="flex gap-2">

                        @if($isStudent)
                        <span class="text-xs px-2 py-1 bg-blue-600 text-white rounded">
                            Student
                        </span>
                        @endif

                        @if($isCorrect)
                        <span class="text-xs px-2 py-1 bg-green-600 text-white rounded">
                            Correct
                        </span>
                        @endif

                    </div>

                </div>

            </div>

            @endif

            @endforeach

        </div>


    </div>

    @endforeach


    <!-- Action Buttons -->
    <div class="flex gap-3 mt-8">

        <a href="{{ route('admin.results.approve',$attempt->id) }}"
            class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
            Approve Result
        </a>

        <a href="{{ route('admin.results.reject',$attempt->id) }}"
            class="px-6 py-3 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
            Reject Result
        </a>

    </div>


</div>

@endsection