@extends('layouts.admin')

@section('content')

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b">

        <h2 class="text-lg font-semibold text-gray-800">
            Question Bank
        </h2>

        <div class="flex items-center gap-3">

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.questions.index') }}" id="searchForm">

                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search question or subject..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </form>

            <!-- Add Question Button -->
            <a href="{{ route('admin.questions.create') }}"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                Add Question
            </a>

        </div>

    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Class</th>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Question</th>
                    <th class="px-4 py-3 text-left">Marks</th>
                    <th class="px-4 py-3 text-left">Difficulty</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @if(!request()->filled('search'))

                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        Start typing to search questions.
                    </td>
                </tr>

                @else

                @forelse($questions as $q)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">{{ $q->class }}</td>
                    <td class="px-4 py-3">{{ $q->subject }}</td>

                    <td class="px-4 py-3 capitalize">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $q->type === 'mcq'
                                        ? 'bg-indigo-100 text-indigo-700'
                                        : 'bg-gray-100 text-gray-700' }}">
                            {{ $q->type }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-700">
                        {{ Str::limit($q->question_text, 80) }}
                    </td>

                    <td class="px-4 py-3">{{ $q->marks }}</td>
                    <td class="px-4 py-3">{{ $q->difficulty }}</td>

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

                @endif

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    @if(request()->filled('search') && $questions->hasPages())
    <div class="px-6 py-4 border-t bg-gray-50">
        {{ $questions->appends(request()->query())->links() }}
    </div>
    @endif

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("searchInput");
    const form = document.getElementById("searchForm");

    if (input) {

        let debounceTimer;

        input.addEventListener("keyup", function () {

            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                form.submit();
            }, 600); // 600ms debounce delay

        });

    }

});

</script>

@endsection