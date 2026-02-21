@extends('layouts.admin')

@section('title','Edit Question')

@section('content')

<div class="max-w-5xl mx-auto space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">
        <div>
            <p class="text-sm text-gray-500 mt-1">
                Update question details and answer options
            </p>
        </div>

        <a href="{{ route('admin.questions.index') }}"
            class="px-4 py-2 rounded-xl border border-gray-300 text-sm text-gray-700 hover:bg-gray-100 transition">
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.questions.update',$question->id) }}">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-hidden">

            <!-- Section Header -->
            <div class="px-6 py-5 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">
                    Question Details
                </h2>
            </div>

            <div class="p-6 space-y-6">

                <!-- Grid Section -->
                <div class="grid md:grid-cols-3 gap-6">

                    <!-- Class -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Class / Grade
                        </label>
                        <input type="text" name="class" required
                            value="{{ old('class',$question->class) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            transition text-sm">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Subject
                        </label>
                        <input type="text" name="subject" required
                            value="{{ old('subject',$question->subject) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            transition text-sm">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Question Type
                        </label>
                        <select name="type" id="questionType"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            transition text-sm">
                            <option value="mcq" @selected($question->type==='mcq')>MCQ</option>
                            <option value="subjective" @selected($question->type==='subjective')>Subjective</option>
                            <option value="summary" @selected($question->type==='summary')>Summary</option>
                        </select>
                    </div>

                </div>

                <!-- Question -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Question
                    </label>
                    <textarea name="question_text" rows="4" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm resize-none">{{ old('question_text',$question->question_text) }}</textarea>
                </div>

                <!-- Marks + Difficulty -->
                <div class="grid md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Marks
                        </label>
                        <input type="number" name="marks" min="1" required
                            value="{{ old('marks',$question->marks) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty
                        </label>
                        <select name="difficulty" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            transition text-sm">
                            <option value="">Select difficulty</option>
                            @foreach($difficulties as $level)
                                <option value="{{ $level }}"
                                    @selected(old('difficulty',$question->difficulty)===$level)>
                                    {{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>

            <!-- MCQ Block -->
            <div id="mcqBlock" class="border-t px-6 py-6 bg-gray-50">

                <h3 class="text-sm font-semibold text-gray-800 mb-4">
                    Answer Options (MCQ Only)
                </h3>

                @php
                    $correct = strtoupper(old('correct_option', $question->correct_option));
                @endphp

                <div class="space-y-4">

                    @foreach(['a','b','c','d'] as $key)

                    <div class="flex items-center gap-4 p-3 bg-white border border-gray-200 rounded-xl">

                        <input type="radio"
                            name="correct_option"
                            value="{{ strtoupper($key) }}"
                            @checked($correct === strtoupper($key))
                            class="w-4 h-4 text-indigo-600 bg-white border-gray-300 focus:ring-indigo-500">

                        <input type="text"
                            name="option_{{ $key }}"
                            value="{{ old('option_'.$key,$question->{'option_'.$key}) }}"
                            class="flex-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                        <span class="text-xs font-semibold text-gray-400 uppercase">
                            {{ strtoupper($key) }}
                        </span>

                    </div>

                    @endforeach

                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="px-6 py-5 border-t flex justify-end gap-3 bg-white">

                <a href="{{ route('admin.questions.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                    Update Question
                </button>

            </div>

        </div>

    </form>

</div>

<script>
const typeSelect = document.getElementById('questionType');
const mcqBlock   = document.getElementById('mcqBlock');

function toggleMcq() {
    const isMcq = typeSelect.value === 'mcq';
    mcqBlock.style.display = isMcq ? 'block' : 'none';

    mcqBlock.querySelectorAll('input').forEach(el => {
        el.disabled = !isMcq;
    });
}

toggleMcq();
typeSelect.addEventListener('change', toggleMcq);
</script>

@endsection