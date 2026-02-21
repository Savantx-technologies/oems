@extends('layouts.admin')

@section('title', 'Question Bank')

@section('content')
<div class="max-w-7xl mx-auto">

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b">

        <h2 class="text-lg font-semibold text-gray-800">
            Question Bank
        </h2>

        <div class="flex items-center gap-3">

            <!-- Add Question Button -->
            <a href="{{ route('admin.questions.create') }}"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                Add Question
            </a>

        </div>

    </div>


    {{-- ===============================
    CLASS CARD VIEW
    ================================ --}}
    @if(!request()->filled('class'))

    <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

        @forelse($classes as $class)

        <a href="{{ route('admin.questions.index', ['class' => $class]) }}"
            class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-6 text-center">

            <div class="text-xl font-bold text-indigo-600">
                Class {{ $class }}
            </div>

            <div class="text-sm text-gray-500 mt-2">
                View Questions
            </div>

        </a>

        @empty

        <div class="col-span-full text-center text-gray-500">
            No classes available.
        </div>

        @endforelse

    </div>


    {{-- ===============================
    QUESTION TABLE VIEW
    ================================ --}}
    @else

    <!-- Sub Header -->
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">

        <h2 class="text-lg font-semibold text-gray-800">
            Class {{ request('class') }} Questions
        </h2>

        <a href="{{ route('admin.questions.index') }}" class="text-sm text-indigo-600 hover:underline">
            ← Back to Classes
        </a>

    </div>


    <!-- Table Section -->
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-left">Question</th>
                    <th class="px-4 py-3 text-left">Marks</th>
                    <th class="px-4 py-3 text-left">Difficulty</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($questions as $q)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">{{ $q->subject }}</td>

                    <td class="px-4 py-3 text-gray-700 space-y-2">

                        {{-- Question --}}
                        <div class="font-medium text-gray-800">
                            {{ $q->question_text }}
                        </div>

                        {{-- Options --}}
                        <div class="text-xs space-y-1">

                            <div class="flex gap-2">
                                <span class="font-semibold">A.</span>
                                <span class="{{ $q->correct_option == 'a' ? 'text-green-600 font-semibold' : '' }}">
                                    {{ $q->option_a }}
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <span class="font-semibold">B.</span>
                                <span class="{{ $q->correct_option == 'b' ? 'text-green-600 font-semibold' : '' }}">
                                    {{ $q->option_b }}
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <span class="font-semibold">C.</span>
                                <span class="{{ $q->correct_option == 'c' ? 'text-green-600 font-semibold' : '' }}">
                                    {{ $q->option_c }}
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <span class="font-semibold">D.</span>
                                <span class="{{ $q->correct_option == 'd' ? 'text-green-600 font-semibold' : '' }}">
                                    {{ $q->option_d }}
                                </span>
                            </div>

                        </div>

                        {{-- Correct Answer Badge --}}
                        <div class="mt-2">
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                Correct: {{ strtoupper($q->correct_option) }}
                            </span>
                        </div>

                    </td>

                    <td class="px-4 py-3">{{ $q->marks }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                            {{ $q->difficulty }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-right">
                        <div class="inline-flex gap-2">

                            <a href="{{ route('admin.questions.edit', $q->id) }}"
                                class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100 transition">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.questions.destroy', $q->id) }}"
                                onsubmit="return confirm('Delete this question?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100 transition">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        No questions found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <!-- Pagination -->
    @if($questions->hasPages())
    <div class="px-6 py-4 border-t bg-gray-50">
        {{ $questions->appends(request()->query())->links() }}
    </div>
    @endif

    @endif

</div>

@endsection