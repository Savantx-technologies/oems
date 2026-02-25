@extends('layouts.admin')

@section('title','Create Exam')

@section('content')
@if($lastExam)

<div class="flex items-start gap-3 bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-sm text-indigo-800">

    <div class="mt-0.5">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
        </svg>
    </div>

    <div>
        <p class="font-semibold">
            How to Create Exam
        </p>

        <p class="mt-1">
            The form is automatically filled with details from your last created exam.
            If you are creating exams for multiple subjects under the same
            session, title, and exam type, you only need to change the
            <strong>Subject</strong>.
        </p>
    </div>

</div>

@endif
<div class="max-w-5xl mx-auto py-0 space-y-8">

    <!-- ================= Header ================= -->
    <form method="POST" action="{{ route('admin.exams.store') }}" class="space-y-8">
        @csrf

        <!-- ================= Academic Information ================= -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl
            flex items-center justify-between">

                <h2 class="text-base font-semibold text-gray-800">
                    Academic Information
                </h2>

                <button type="button" onclick="window.location.href='{{ route('admin.exams.index') }}'" class="flex items-center gap-2 px-4 py-2 rounded-lg
               border border-gray-300 bg-white
               text-sm font-medium text-gray-700
               hover:bg-gray-100 transition shadow-sm">
                    ← Back
                </button>

            </div>


            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam Title
                    </label>
                    <input type="text" name="title" value="{{ old('title', $lastExam->title ?? '') }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300
    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none" placeholder="Mid Term Examination">

                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-sm font-medium text-gray-700">
                            Class / Grade
                        </label>

                        <button type="button" onclick="showNewClassInput()"
                            class="text-indigo-600 text-sm font-semibold hover:text-indigo-800">
                            + Add
                        </button>
                    </div>

                    <!-- Dropdown -->
                    <select name="class" id="classSelect" class="w-full px-4 py-2 rounded-lg border border-gray-300
        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">

                        <option value="">Select Class</option>

                        @foreach($classes as $class)
                        <option value="{{ $class }}" {{ old('class', $lastExam->class ?? '') == $class ? 'selected' : ''
                            }}>
                            {{ $class }}
                        </option>
                        @endforeach

                    </select>

                    <!-- Hidden Input -->
                    <input type="text" name="new_class" id="newClassInput" placeholder="Enter new class"
                        class="hidden mt-2 w-full px-4 py-2 rounded-lg border border-gray-300">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-sm font-medium text-gray-700">
                            Subject
                        </label>

                        <button type="button" onclick="showNewSubjectInput()"
                            class="text-indigo-600 text-sm font-semibold hover:text-indigo-800">
                            + Add
                        </button>
                    </div>

                    <!-- Dropdown -->
                    <select name="subject" id="subjectSelect" class="w-full px-4 py-2 rounded-lg border border-gray-300
        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">

                        <option value="">Select Subject</option>

                        @foreach($subjects as $sub)
                        <option value="{{ $sub }}" {{ old('subject')==$sub ? 'selected' : '' }}>
                            {{ $sub }}
                        </option>
                        @endforeach

                    </select>

                    <input type="text" name="new_subject" id="newSubjectInput" placeholder="Enter new subject"
                        class="hidden mt-2 w-full px-4 py-2 rounded-lg border border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Academic Session
                    </label>
                    <input type="text" name="academic_session"
                        value="{{ old('academic_session', $lastExam->academic_session ?? '') }}" required class="w-full px-4 py-2 rounded-lg border border-gray-300
    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none" placeholder="2025–26">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam Type
                    </label>
                    <select name="exam_type" required class="w-full px-4 py-2 rounded-lg border border-gray-300
    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">

                        <option value="">Select type</option>

                        <option value="term" {{ old('exam_type', $lastExam->exam_type ?? '') == 'term' ? 'selected' : ''
                            }}>
                            Term
                        </option>

                        <option value="mock" {{ old('exam_type', $lastExam->exam_type ?? '') == 'mock' ? 'selected' : ''
                            }}>
                            Mock
                        </option>

                        <option value="final" {{ old('exam_type', $lastExam->exam_type ?? '') == 'final' ? 'selected' :
                            '' }}>
                            Final
                        </option>

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
                    <input type="number" name="duration_minutes" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="60">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Passing Marks
                    </label>
                    <input type="number" name="pass_marks" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="18">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Negative Marks
                    </label>
                    <input type="number" step="0.25" name="negative_marks" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="0.25">
                </div>

            </div>

            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-6">

                <label class="block text-sm font-medium  text-gray-700 mb-1">
                    <input type="checkbox" name="negative_marking" value="1"
                        class="rounded border-gray-300 text-indigo-600">
                    Enable Negative Marking
                </label>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="shuffle_questions" value="1" checked
                        class="rounded border-gray-300 text-indigo-600">
                    Shuffle Questions
                </label>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="shuffle_options" value="1" checked
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

                <div id="instructionWrapper" class="space-y-3">
                    <input type="text" name="instructions[]" class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="Instruction point">
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
                Save & Continue
            </button>

        </div>

    </form>

</div>

<script>
    function addInstruction() {
    const div = document.createElement('div');
    div.innerHTML = `
        <input type="text" name="instructions[]"
            class="w-full px-4 py-2 rounded-lg border border-gray-300
                   focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
            placeholder="Instruction point">
    `;
    document.getElementById('instructionWrapper').appendChild(div);
}
function showNewClassInput() {
    document.getElementById("classSelect").classList.add("hidden");
    document.getElementById("newClassInput").classList.remove("hidden");
}

function showNewSubjectInput() {
    document.getElementById("subjectSelect").classList.add("hidden");
    document.getElementById("newSubjectInput").classList.remove("hidden");
}

</script>

@endsection