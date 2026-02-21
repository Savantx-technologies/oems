@extends('layouts.admin')

@section('title','Add Question')

@section('content')

<div class="max-w-5xl mx-auto py-8 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-5 border-b bg-gray-50">
       <h2 class="text-lg font-semibold text-gray-800">
                    Create New Question
                </h2>
        <div class="text-sm text-gray-600">
            Total Questions :
            <span class="font-semibold text-blue-600">
                {{ $total }}
            </span>
        </div>
    </div>

    <!-- Recently Added -->
    @if($questions->count() > 0)
    <div id="recentBox" class="bg-white rounded-xl shadow border border-gray-200">

        <div class="px-6 py-3 border-b flex items-center justify-between bg-gray-50 rounded-t-xl">
            <span class="text-sm font-semibold text-gray-800">
                Recently Added (Current Session)
            </span>

            <span id="recentCount" class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                {{ $questions->count() }} added
            </span>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-left border-separate border-spacing-y-2">

                <thead>
                    <tr class="text-xs uppercase text-gray-500">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Class</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Question</th>
                        <th class="px-4 py-2 text-right">Marks</th>
                    </tr>
                </thead>

                <tbody id="recentTable">

                    @foreach($questions as $index => $q)
                    <tr class="bg-white shadow-sm rounded-lg hover:shadow-md transition">

                        <td class="px-4 py-3 font-medium text-gray-500">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                                Class {{ $q->class }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-700">
                            {{ $q->subject }}
                        </td>

                        <td class="px-4 py-3 capitalize">
                            <span class="px-3 py-1 text-xs font-medium
                        {{ $q->type === 'mcq'
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-purple-100 text-purple-700' }}
                        rounded-full">
                                {{ $q->type }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                            {{ $q->question_text }}
                        </td>

                        <td class="px-4 py-3 text-right font-semibold text-gray-700">
                            {{ $q->marks }}
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('admin.questions.store') }}">
        @csrf

        <div class="bg-white border border-gray-200 rounded-2xl shadow-md overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b bg-gray-50">
                
                <p class="text-sm text-gray-500 mt-1">
                    Fill in the details below to add a new question.
                </p>
            </div>

            <div class="p-6 space-y-8">

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Class <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="class" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Question Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="questionType" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm">
                            <option value="mcq">MCQ</option>
                            <option value="summary">Summary</option>
                        </select>
                    </div>

                </div>

                <!-- Question Text -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <textarea name="question_text" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl
                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                    transition text-sm resize-none"></textarea>
                </div>

                <!-- Marks + Difficulty -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Marks <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="marks" min="1" value="1" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty <span class="text-red-500">*</span>
                        </label>
                        <select name="difficulty" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                        transition text-sm">
                            <option value="">Select difficulty</option>
                            @foreach($difficulties as $level)
                            <option value="{{ $level }}">
                                {{ ucfirst($level) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>

            <!-- MCQ Section -->
            <div id="mcqBlock" class="border-t bg-gray-50 px-6 py-6">

                <h3 class="text-sm font-semibold text-gray-800 mb-4">
                    Answer Options (MCQ Only)
                </h3>

                <div class="space-y-4">

                    @foreach(['A','B','C','D'] as $opt)

                    <div class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-xl shadow-sm">

                        <input type="radio" name="correct_option" value="{{ $opt }}"
                            class="w-4 h-4 text-indigo-600 bg-white border-gray-300 focus:ring-indigo-500">

                        <input type="text" name="option_{{ strtolower($opt) }}" placeholder="Option {{ $opt }}" class="flex-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                        <span class="text-xs font-semibold text-gray-400">
                            {{ $opt }}
                        </span>

                    </div>

                    @endforeach

                </div>

            </div>

            <!-- Footer Buttons -->
            <div class="px-6 py-5 border-t bg-white flex justify-end gap-3">

                <a href="{{ route('admin.questions.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm hover:bg-gray-100 transition">
                    Cancel
                </a>

                <button type="submit" name="save_add_more" value="1"
                    class="px-6 py-2.5 rounded-xl bg-indigo-100 text-indigo-700 text-sm font-medium hover:bg-indigo-200 transition">
                    Save & Add More
                </button>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                    Save Question
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
    mcqBlock.querySelectorAll('input').forEach(el => el.disabled = !isMcq);
}

toggleMcq();
typeSelect.addEventListener('change', toggleMcq);
</script>


@endsection