@extends('layouts.admin')

@section('title', 'Question Bank')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Question Bank</h2>

            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:flex lg:items-center lg:gap-3">
                <a href="{{ route('admin.questions.bulk.form') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                    Bulk Upload
                </a>
                <a href="{{ route('admin.questions.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                    Add Question
                </a>
            </div>
        </div>

        @if(!request()->filled('class'))
            <div class="grid grid-cols-2 gap-4 p-4 sm:p-6 md:grid-cols-4 lg:grid-cols-6">
                @forelse($classes as $class)
                    <a href="{{ route('admin.questions.index', ['class' => $class]) }}"
                        class="rounded-2xl border border-gray-200 bg-white p-5 text-center shadow-sm transition hover:shadow-md">
                        <div class="text-xl font-bold text-indigo-600">Class {{ $class }}</div>
                        <div class="mt-2 text-sm text-gray-500">View Questions</div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No classes available.</div>
                @endforelse
            </div>
        @else
            <div class="flex flex-col gap-3 border-b bg-gray-50 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Class {{ request('class') }} Questions</h2>
                <a href="{{ route('admin.questions.index') }}" class="text-sm text-indigo-600 hover:underline">Back to Classes</a>
            </div>

            <div class="space-y-4 p-4 sm:hidden">
                @forelse($questions as $q)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                    #{{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}
                                </div>
                                <div class="mt-1 text-sm font-semibold text-indigo-700">{{ $q->subject }}</div>
                            </div>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">{{ $q->difficulty }}</span>
                        </div>

                        <div class="mt-4 text-sm font-medium text-gray-800">{{ $q->question_text }}</div>

                        <div class="mt-4 space-y-2 text-xs text-gray-600">
                            <div class="flex gap-2"><span class="font-semibold">A.</span><span class="{{ $q->correct_option == 'a' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_a }}</span></div>
                            <div class="flex gap-2"><span class="font-semibold">B.</span><span class="{{ $q->correct_option == 'b' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_b }}</span></div>
                            <div class="flex gap-2"><span class="font-semibold">C.</span><span class="{{ $q->correct_option == 'c' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_c }}</span></div>
                            <div class="flex gap-2"><span class="font-semibold">D.</span><span class="{{ $q->correct_option == 'd' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_d }}</span></div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Correct: {{ strtoupper($q->correct_option) }}</span>
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $q->marks }} Marks</span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('admin.questions.edit', $q->id) }}" class="rounded-xl bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">Edit</a>
                            <form method="POST" action="{{ route('admin.questions.destroy', $q->id) }}" onsubmit="return confirm('Delete this question?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl bg-red-50 px-3 py-2 text-xs font-medium text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">No questions found.</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Subject</th>
                            <th class="px-4 py-3 text-left">Question</th>
                            <th class="px-4 py-3 text-left">Marks</th>
                            <th class="px-4 py-3 text-left">Difficulty</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($questions as $q)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-500">
                                    {{ $loop->iteration + ($questions->currentPage() - 1) * $questions->perPage() }}
                                </td>
                                <td class="px-4 py-3">{{ $q->subject }}</td>
                                <td class="space-y-2 px-4 py-3 text-gray-700">
                                    <div class="font-medium text-gray-800">{{ $q->question_text }}</div>
                                    <div class="space-y-1 text-xs">
                                        <div class="flex gap-2"><span class="font-semibold">A.</span><span class="{{ $q->correct_option == 'a' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_a }}</span></div>
                                        <div class="flex gap-2"><span class="font-semibold">B.</span><span class="{{ $q->correct_option == 'b' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_b }}</span></div>
                                        <div class="flex gap-2"><span class="font-semibold">C.</span><span class="{{ $q->correct_option == 'c' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_c }}</span></div>
                                        <div class="flex gap-2"><span class="font-semibold">D.</span><span class="{{ $q->correct_option == 'd' ? 'font-semibold text-green-600' : '' }}">{{ $q->option_d }}</span></div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Correct: {{ strtoupper($q->correct_option) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $q->marks }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs">{{ $q->difficulty }}</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('admin.questions.edit', $q->id) }}" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100">Edit</a>
                                        <form method="POST" action="{{ route('admin.questions.destroy', $q->id) }}" onsubmit="return confirm('Delete this question?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">No questions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($questions->hasPages())
                <div class="border-t bg-gray-50 px-4 py-4 sm:px-6">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
