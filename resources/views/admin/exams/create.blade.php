@extends('layouts.admin')

@section('title','Create Exam')

@section('content')

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
                    <input type="text" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="Mid Term Examination">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Class / Grade
                    </label>
                    <input type="text" name="class" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="8">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject
                    </label>
                    <input type="text" name="subject" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="Science">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Academic Session
                    </label>
                    <input type="text" name="academic_session" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                        placeholder="2025–26">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam Type
                    </label>
                    <select name="exam_type" required class="w-full px-4 py-2 rounded-lg border border-gray-300
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                        <option value="">Select type</option>
                        <option value="term">Term</option>
                        <option value="mock">Mock</option>
                        <option value="final">Final</option>
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
</script>

@endsection