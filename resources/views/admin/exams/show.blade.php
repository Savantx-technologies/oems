@extends('layouts.admin')

@section('title','Exam Details')
@php
$hasQuestions = !empty($exam->selected_questions) && count($exam->selected_questions) > 0;
$hasSchedule = !is_null($exam->schedule);
$readyToPublish = $hasQuestions && $hasSchedule;
@endphp


@section('content')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ $exam->title }}
            </h1>
            <p class="text-sm text-gray-500">
                Class {{ $exam->class }} • {{ $exam->subject }}
            </p>
        </div>

        <a href="{{ route('admin.exams.index') }}"
            class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50">
            Back to exams
        </a>
    </div>

    {{-- Warning box --}}
    @if(
    $exam->status === 'draft'
    && (
    !$hasQuestions
    || ($exam->exam_type !== 'mock' && !$hasSchedule)
    )
    )

    <div class="rounded-lg border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-800">
        <p class="font-semibold mb-1">Cannot publish this exam yet</p>

        <ul class="list-disc list-inside space-y-1">

            @if(!$hasQuestions)
            <li>No questions are attached to this exam.</li>
            @endif

            {{-- FIX : schedule warning only for non-mock --}}
            @if($exam->exam_type !== 'mock' && !$hasSchedule)
            <li>Exam schedule is not set.</li>
            @endif

        </ul>

        <div class="mt-3 flex gap-2">

            @if(!$hasQuestions)
            <a href="{{ route('admin.exams.questions',$exam->id) }}"
                class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs font-medium">
                Add Questions
            </a>
            @endif

            {{-- FIX : schedule button only for non-mock --}}
            @if($exam->exam_type !== 'mock' && !$hasSchedule)
            <a href="{{ route('admin.exams.schedule',$exam->id) }}"
                class="px-3 py-1.5 rounded bg-gray-800 text-white text-xs font-medium">
                Set Schedule
            </a>
            @endif

        </div>
    </div>

    @endif


    <!-- Basic info -->
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="p-6 grid md:grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-gray-500">Academic session</p>
                <p class="font-medium">{{ $exam->academic_session }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Exam type</p>
                <p class="font-medium">{{ ucfirst($exam->exam_type) }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Duration</p>
                <p class="font-medium">{{ $exam->duration_minutes }} minutes</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Total marks</p>
                <p class="font-medium">{{ $exam->total_marks }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Passing marks</p>
                <p class="font-medium">{{ $exam->pass_marks ?? '-' }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Status</p>
                <p class="font-medium">{{ ucfirst($exam->status) }}</p>
            </div>

        </div>
    </div>


    {{-- =========================
    Schedule section
    (hidden for mock)
    ========================= --}}

    @if($exam->exam_type !== 'mock')

    <div class="bg-white rounded-xl border shadow-sm">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Schedule</h3>
        </div>

        <div class="p-6 grid md:grid-cols-3 gap-6">

            @if($exam->schedule)

            <div>
                <p class="text-xs text-gray-500">Start</p>
                <p class="font-medium">
                    {{ $exam->schedule->start_at->format('d M Y H:i') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">End</p>
                <p class="font-medium">
                    {{ $exam->schedule->end_at->format('d M Y H:i') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Max attempts</p>
                <p class="font-medium">
                    {{ $exam->schedule->max_attempts }}
                </p>
            </div>

            @else

            <div class="text-sm text-red-600">
                Exam is not scheduled yet.
            </div>

            @endif

        </div>
    </div>

    @endif


    <!-- Questions -->
    <div class="bg-white rounded-xl border shadow-sm">

        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">
                Attached Questions
            </h3>
            <span class="text-sm text-gray-500">
                {{ count($exam->selected_questions ?? []) }} questions
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Question</th>
                        <th class="px-5 py-3 text-left">Marks</th>
                        <th class="px-5 py-3 text-left">Set</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($questions as $q)

                    <tr>
                        <td class="px-5 py-3">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3">{{ $q->question_text}}</td>
                        <td class="px-5 py-3">{{ $q->marks }}</td>
                        <td class="px-5 py-3">A</td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                            No questions attached.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@endsection