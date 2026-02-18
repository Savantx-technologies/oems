
@extends('layouts.admin')

@section('title','Add Question')

@section('content')

<div class="max-w-5xl mx-auto py-8 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-800">
            Add Question
        </h1>

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

            <span id="recentCount"
                class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
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

        <div class="bg-white border rounded-lg shadow p-6 space-y-6">

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Class <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="class" required class="w-full px-4 py-2 rounded border border-gray-300
                               focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" required class="w-full px-4 py-2 rounded border border-gray-300
                               focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Question Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required class="w-full px-4 py-2 rounded border border-gray-300
                               focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="mcq">MCQ</option>
                        <option value="summary">Summary</option>
                    </select>
                </div>

            </div>

            <!-- Question Text -->
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">
                    Question <span class="text-red-500">*</span>
                </label>
                <textarea name="question_text" rows="3" required class="w-full px-4 py-2 rounded border border-gray-300
                           focus:ring-2 focus:ring-blue-400 focus:outline-none resize-none"></textarea>
            </div>

            <!-- Marks + Difficulty -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Marks <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="marks" min="1" value="1" required class="w-full px-4 py-2 rounded border border-gray-300
                               focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">
                        Difficulty <span class="text-red-500">*</span>
                    </label>
                    <select name="difficulty" required class="w-full px-4 py-2 rounded border border-gray-300
                               focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="">Select difficulty</option>
                        @foreach($difficulties as $level)
                        <option value="{{ $level }}">
                            {{ ucfirst($level) }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- MCQ Options -->
            <div class="border-t pt-6 space-y-4">

                <h3 class="font-semibold text-gray-700">
                    Answer Options (MCQ)
                </h3>

                @foreach(['A','B','C','D'] as $opt)
                <div class="flex items-center gap-4">

                    <input type="radio" name="correct_option" value="{{ $opt }}" class="text-blue-600 border-gray-300">

                    <input type="text" name="option_{{ strtolower($opt) }}" placeholder="Option {{ $opt }}" class="flex-1 px-4 py-2 rounded border border-gray-300
                                      focus:ring-2 focus:ring-blue-400 focus:outline-none">

                </div>
                @endforeach

            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t">

                <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded border border-gray-300
                          text-sm hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit" name="save_add_more" value="1" id="saveAddMoreBtn"
                    class="px-5 py-2 rounded-xl bg-indigo-100 text-indigo-700 text-sm font-medium hover:bg-indigo-200 transition">
                    Save & Add More
                </button>
                <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white
                               text-sm hover:bg-blue-700">
                    Save Question
                </button>

            </div>

        </div>

    </form>

</div>

@endsection