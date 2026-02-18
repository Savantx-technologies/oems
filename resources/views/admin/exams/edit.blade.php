@extends('layouts.admin')

@section('title','Edit Exam')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Edit Exam
            </h2>
            <p class="text-sm text-gray-500">
                Update basic exam information
            </p>
        </div>

        <form method="POST" action="{{ route('admin.exams.update',$exam->id) }}">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="px-6 py-4 bg-red-50 border-b border-red-200 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @php
            $instructions = old(
            'instructions',
            $exam->instructions ? json_decode($exam->instructions,true) : []
            );
            @endphp

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Exam title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam title
                    </label>
                    <input type="text" name="title" value="{{ old('title',$exam->title) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Academic session -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Academic session
                    </label>
                    <input type="text" name="academic_session"
                        value="{{ old('academic_session',$exam->academic_session) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Exam type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam type
                    </label>
                    <select name="exam_type"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="term" @selected($exam->exam_type=='term')>Term</option>
                        <option value="mock" @selected($exam->exam_type=='mock')>Mock</option>
                        <option value="final" @selected($exam->exam_type=='final')>Final</option>
                    </select>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Duration (minutes)
                    </label>
                    <input type="number" min="1" name="duration_minutes"
                        value="{{ old('duration_minutes',$exam->duration_minutes) }}" required
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Pass marks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Passing marks
                    </label>
                    <input type="number" min="0" name="pass_marks" value="{{ old('pass_marks',$exam->pass_marks) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Negative marking -->
                <div class="flex items-center gap-3 mt-6">
                    <input type="checkbox" name="negative_marking" value="1" @checked($exam->negative_marking)
                    class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700">
                        Enable negative marking
                    </span>
                </div>

                <!-- Negative marks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Negative marks (per question)
                    </label>
                    <input type="number" step="0.25" name="negative_marks"
                        value="{{ old('negative_marks',$exam->negative_marks) }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Shuffle questions -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="shuffle_questions" value="1" @checked($exam->shuffle_questions)
                    class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700">
                        Shuffle questions
                    </span>
                </div>

                <!-- Shuffle options -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="shuffle_options" value="1" @checked($exam->shuffle_options)
                    class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700">
                        Shuffle options
                    </span>
                </div>

                <!-- Instructions -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Instructions
                    </label>

                    <div id="instructionWrapper" class="space-y-2">

                        @forelse($instructions as $inst)
                        <input type="text" name="instructions[]" value="{{ $inst }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @empty
                        <input type="text" name="instructions[]"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @endforelse

                    </div>

                    <button type="button" onclick="addInstruction()" class="text-sm text-indigo-600 mt-2">
                        + Add instruction
                    </button>
                </div>

                <!-- Read only info -->
                <div class="md:col-span-2 text-sm text-gray-500 pt-2">
                    Class: <strong>{{ $exam->class }}</strong> |
                    Subject: <strong>{{ $exam->subject }}</strong>
                </div>

            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">

                <a href="{{ route('admin.exams.show',$exam->id) }}"
                    class="px-5 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>

                <button class="px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    Update Exam
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function addInstruction()
{
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'instructions[]';
    input.className =
        'w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500';

    document.getElementById('instructionWrapper').appendChild(input);
}
</script>


@endsection