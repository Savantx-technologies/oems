@extends('layouts.admin')

@section('title','Create Exam')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    <!-- Page header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Create New Exam</h1>
            <p class="text-sm text-gray-500">Create exam and configure basic settings</p>
        </div>

        <a href="{{ route('admin.exams.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.exams.store') }}">
        @csrf

        <!-- ================= Academic Information  ================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Academic Information</h2>
                <p class="text-sm text-gray-500">Define exam identity and scope</p>
            </div>

            <div class="p-6 grid md:grid-cols-4 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam title
                    </label>
                    <input type="text" name="title"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Mid Term Examination" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Class / Grade
                    </label>
                    <input type="text" name="class"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="8" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject
                    </label>
                    <input type="text" name="subject"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Science" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Academic session
                    </label>
                    <input type="text" name="academic_session"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="2025–26" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Exam type
                    </label>
                    <select name="exam_type"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="">Select type</option>
                        <option value="practice">Practice</option>
                        <option value="mock">Mock</option>
                        <option value="final">Final</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- ================= Exam Settings ================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Exam Settings</h2>
                <p class="text-sm text-gray-500">Control exam behavior and evaluation</p>
            </div>

            <div class="p-6 grid md:grid-cols-4 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Duration (minutes)
                    </label>
                    <input type="number" name="duration_minutes" min="1"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="60" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Passing marks
                    </label>
                    <input type="number" name="pass_marks" min="0"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="18">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Negative marks (per question)
                    </label>
                    <input type="number" step="0.25" name="negative_marks"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.25">
                </div>

                <div class="flex items-center gap-4 pt-6">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="negative_marking" value="1"
                            class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm text-gray-700">Enable negative marking</span>
                    </label>

                </div>

                <div class="flex items-center gap-4">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="shuffle_questions" value="1" checked
                            class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm text-gray-700">Shuffle questions</span>
                    </label>

                </div>

                <div class="flex items-center gap-4">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="shuffle_options" value="1" checked
                            class="rounded border-gray-300 text-indigo-600">
                        <span class="text-sm text-gray-700">Shuffle options</span>
                    </label>

                </div>

            </div>
        </div>

        <!-- ================= Instructions ================= -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Student Instructions</h2>
                <p class="text-sm text-gray-500">Displayed to students before exam starts</p>
            </div>

            <div id="instructionWrapper" class="px-6 py-4 border-b">
                <input type="text" name="instructions[]" class="w-full rounded-lg border-gray-300"
                    placeholder="Instruction..">
            </div>

            <button type="button" onclick="addInstruction()" class="text-sm text-indigo-600 px-6 py-4 mt-2">
                + Add instruction
            </button>

        </div>

        <!-- ================= Action ================= -->
        <div class="flex justify-end gap-3 mt-2 px-6 py-4 border-t border-gray-200">

            <a href="{{ route('admin.exams.index') }}"
                class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50">
                Cancel
            </a>

            <button type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Save & Continue
            </button>

        </div>

    </form>

</div>

<script>
    function addInstruction()
{
    const div = document.createElement('div');
    div.innerHTML =
        `<input type="text" name="instructions[]"
                class="w-full rounded-lg border-gray-300"
                placeholder="Instruction point">`;

    document.getElementById('instructionWrapper').appendChild(div);
}
</script>

@endsection