@extends('layouts.admin')

@section('content')

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">
            Question Bank
        </h2>

        <a href="{{ route('admin.questions.create') }}"
            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Add Question
        </a>
    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-xs uppercase text-gray-600">
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

                @forelse($questions as $q)

                <tr class="hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $q->class }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $q->subject }}
                    </td>

                    <td class="px-4 py-3 capitalize">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $q->type === 'mcq' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $q->type }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-700">
                        {{ Str::limit($q->question_text, 60) }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $q->marks }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $q->difficulty }}
                    </td>

                    <td class="px-4 py-3 text-right">

                        <div class="inline-flex gap-2">

                            <a href="{{ route('admin.questions.edit',$q->id) }}"
                                class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.questions.destroy',$q->id) }}"
                                onsubmit="return confirm('Delete this question?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No questions found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="px-4 py-3 border-t">
        {{ $questions->links() }}
    </div>

</div>


@endsection