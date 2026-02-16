@extends('layouts.admin')

@section('title','Edit Question')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Edit Question
            </h1>
            <p class="text-sm text-gray-500">
                Update question details
            </p>
        </div>

        <a href="{{ route('admin.questions.index') }}"
            class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.questions.update',$question->id) }}">
        @csrf
        @method('PUT')

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">
                    Question Details
                </h2>
            </div>

            <div class="p-6 space-y-6">

                <div class="grid md:grid-cols-3 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Class / Grade
                        </label>
                        <input type="text" name="class" required value="{{ old('class',$question->class) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Subject
                        </label>
                        <input type="text" name="subject" required value="{{ old('subject',$question->subject) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Question type
                        </label>
                        <select name="type" id="questionType"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="mcq" @selected($question->type==='mcq')>MCQ</option>
                            <option value="subjective" @selected($question->type==='subjective')>Subjective</option>
                            <option value="summary" @selected($question->type==='summary')>Summary</option>
                        </select>
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Question
                    </label>
                    <textarea name="question_text" rows="3" required
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">{{ old('question_text',$question->question_text) }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Marks
                        </label>
                        <input type="number" name="marks" min="1" required value="{{ old('marks',$question->marks) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <select name="difficulty" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                            <option value="">Select difficulty</option>

                            @foreach($difficulties as $level)
                            <option value="{{ $level }}" @selected(old('difficulty', $question->difficulty) === $level)>
                                {{ ucfirst($level) }}
                            </option>
                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- MCQ block --}}
            <div id="mcqBlock" class="border-t px-6 py-5">

                <h3 class="text-sm font-semibold text-gray-800 mb-4">
                    Answer options (MCQ only)
                </h3>

                <div class="space-y-3">

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ old('option_a',$question->option_a) }}"
                            @checked(old('option_a',$question->option_a) ==
                        old('correct_option',$question->correct_option))
                        class="text-indigo-600 border-gray-300">

                        <input type="text" name="option_a" id="optA" value="{{ old('option_a',$question->option_a) }}"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <span class="text-xs text-gray-400">A</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ old('option_b',$question->option_b) }}"
                            @checked(old('option_b',$question->option_b) ==
                        old('correct_option',$question->correct_option))
                        class="text-indigo-600 border-gray-300">

                        <input type="text" name="option_b" id="optB" value="{{ old('option_b',$question->option_b) }}"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <span class="text-xs text-gray-400">B</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ old('option_c',$question->option_c) }}"
                            @checked(old('option_c',$question->option_c) ==
                        old('correct_option',$question->correct_option))
                        class="text-indigo-600 border-gray-300">

                        <input type="text" name="option_c" id="optC" value="{{ old('option_c',$question->option_c) }}"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <span class="text-xs text-gray-400">C</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ old('option_d',$question->option_d) }}"
                            @checked(old('option_d',$question->option_d) ==
                        old('correct_option',$question->correct_option))
                        class="text-indigo-600 border-gray-300">

                        <input type="text" name="option_d" id="optD" value="{{ old('option_d',$question->option_d) }}"
                            class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        <span class="text-xs text-gray-400">D</span>
                    </div>

                </div>

            </div>
            <div class="px-6 py-4 border-t flex justify-end gap-3">

                <a href="{{ route('admin.questions.index') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    Update Question
                </button>

            </div>

        </div>

    </form>

</div>


<script>
    const typeSelect = document.getElementById('questionType');
const mcqBlock   = document.getElementById('mcqBlock');

function toggleMcq()
{
    const isMcq = typeSelect.value === 'mcq';

    mcqBlock.style.display = isMcq ? 'block' : 'none';

    mcqBlock.querySelectorAll('input').forEach(el => {
        if(isMcq){
            el.removeAttribute('disabled');
        }else{
            el.setAttribute('disabled','disabled');
        }
    });
}

toggleMcq();
typeSelect.addEventListener('change', toggleMcq);
</script>

@endsection