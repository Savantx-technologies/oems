@extends('layouts.admin')

@section('title','Edit Exam')

@section('content')

<div class="max-w-5xl mx-auto py-0 space-y-8">

    <form method="POST" action="{{ route('admin.exams.update',$exam->id) }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- ================= Academic Information ================= -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl
                        flex items-center justify-between">

                <p class="text-base font-semibold text-gray-400">
                    Update exam configuration including duration, evaluation rules, and instructions.
                </p>

                <a href="{{ route('admin.exams.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg
                          border border-gray-300 bg-white
                          text-sm font-medium text-gray-700
                          hover:bg-gray-100 transition shadow-sm">
                    ← Back
                </a>

            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Class / Grade
                    </label>
                    <input type="text" value="{{ $exam->class }}" readonly class="w-full px-4 py-2 rounded-lg bg-gray-100 border border-gray-300
                               text-gray-600 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject
                    </label>
                    <input type="text" value="{{ $exam->subject }}" readonly class="w-full px-4 py-2 rounded-lg bg-gray-100 border border-gray-300
                               text-gray-600 cursor-not-allowed">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam Title
                    </label>
                    <input type="text" name="title" value="{{ old('title',$exam->title) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Academic Session
                    </label>
                    <input type="text" name="academic_session"
                        value="{{ old('academic_session',$exam->academic_session) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam Type
                    </label>
                    <select name="exam_type" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">

                        <option value="term" @selected(old('exam_type',$exam->exam_type)=='term')>Term</option>
                        <option value="mock" @selected(old('exam_type',$exam->exam_type)=='mock')>Mock</option>
                        <option value="final" @selected(old('exam_type',$exam->exam_type)=='final')>Final</option>
                    </select>
                </div>

            </div>

        </div>


        <!-- ================= Exam Settings ================= -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-base font-semibold text-gray-800">
                    Exam Settings
                </h2>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Duration (Minutes)
                    </label>
                    <input type="number" name="duration_minutes"
                        value="{{ old('duration_minutes',$exam->duration_minutes) }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Passing Marks
                    </label>
                    <input type="number" name="pass_marks" value="{{ old('pass_marks',$exam->pass_marks) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Negative Marks
                    </label>
                    <input type="number" step="0.25" name="negative_marks"
                        value="{{ old('negative_marks',$exam->negative_marks) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                </div>

            </div>

            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-6">

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="negative_marking" value="1"
                        @checked(old('negative_marking',$exam->negative_marking))
                    class="rounded border-gray-300 text-indigo-600">
                    Enable Negative Marking
                </label>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="shuffle_questions" value="1"
                        @checked(old('shuffle_questions',$exam->shuffle_questions))
                    class="rounded border-gray-300 text-indigo-600">
                    Shuffle Questions
                </label>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="shuffle_options" value="1"
                        @checked(old('shuffle_options',$exam->shuffle_options))
                    class="rounded border-gray-300 text-indigo-600">
                    Shuffle Options
                </label>

            </div>

        </div>


        <!-- ================= Instructions ================= -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
                <h2 class="text-base font-semibold text-gray-800">
                    Student Instructions
                </h2>
            </div>

            <div class="p-6 space-y-4">

                @php
                $instructions = old('instructions',
                $exam->instructions ? json_decode($exam->instructions,true) : []
                );
                @endphp

                <div id="instructionWrapper" class="space-y-3">
                    @forelse($instructions as $inst)
                    <input type="text" name="instructions[]" value="{{ $inst }}" class="w-full px-4 py-2 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                    @empty
                    <input type="text" name="instructions[]" class="w-full px-4 py-2 rounded-lg border border-gray-300
                                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                    @endforelse
                </div>

                <button type="button" onclick="addInstruction()"
                    class="text-sm font-medium text-indigo-600 hover:underline">
                    + Add Instruction
                </button>

            </div>

        </div>


        <!-- ================= Actions ================= -->
        <div class="flex justify-end gap-3">

            <a href="{{ route('admin.exams.index') }}" class="px-5 py-2 rounded-lg border border-gray-300
                       text-sm font-medium text-gray-700
                       hover:bg-gray-100 transition">
                Cancel
            </a>

            <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white
                       text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                Update Exam
            </button>

        </div>

    </form>

</div>

<script>
    function addInstruction() {
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'instructions[]';
    input.className =
        'w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm';

    document.getElementById('instructionWrapper').appendChild(input);
}
</script>

@endsection