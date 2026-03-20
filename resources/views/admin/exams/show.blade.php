@extends('layouts.admin')

@section('title','Exam Details')

@php
    $hasQuestions = !empty($exam->selected_questions) && count($exam->selected_questions) > 0;
    $hasSchedule = !is_null($exam->schedule);
@endphp

@section('content')

<div class="max-w-5xl mx-auto space-y-8">

    <!-- ================= Header ================= -->
    <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl
     flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ $exam->title }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Class {{ $exam->class }} • {{ $exam->subject }}
            </p>
        </div>

        <div class="flex gap-3">
            @if(auth('admin')->user()->role === 'school_admin')
            <a href="{{ route('admin.exams.monitor-blocks.index',$exam->id) }}"
               class="px-4 py-2 rounded-lg bg-violet-600 text-white text-sm font-medium hover:bg-violet-700 transition shadow-sm">
                Blocks
            </a>
            @endif
            <a href="{{ route('admin.exams.edit',$exam->id) }}"
               class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                Edit
            </a>

            <a href="{{ route('admin.exams.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300
                      text-sm font-medium text-gray-700
                      hover:bg-gray-100 transition shadow-sm">
                Back
            </a>
        </div>
    </div>


    <!-- ================= Academic Information ================= -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-base font-semibold text-gray-800">
                Academic Information
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-gray-500">Academic Session</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $exam->academic_session }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Exam Type</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ ucfirst($exam->exam_type) }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Duration</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $exam->duration_minutes }} Minutes
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Total Marks</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $exam->total_marks }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Passing Marks</p>
                <p class="mt-1 font-medium text-gray-800">
                    {{ $exam->pass_marks ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">Status</p>
                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                    {{ $exam->status === 'published'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($exam->status) }}
                </span>
            </div>

        </div>
    </div>


    <!-- ================= Schedule ================= -->
    @if($exam->exam_type !== 'mock')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl">
            <h2 class="text-base font-semibold text-gray-800">
                Schedule
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

            @if($exam->schedule)

                <div>
                    <p class="text-xs text-gray-500">Start</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $exam->schedule->start_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">End</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $exam->schedule->end_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Max Attempts</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $exam->schedule->max_attempts }}
                    </p>
                </div>

            @else

                <div class="text-sm text-red-600">
                    Exam is not scheduled yet.
                </div>

            @endif

        </div>
    </div>

    @endif

    <!-- ================= Monitoring Blocks ================= -->
    <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Monitoring Blocks</h2>
                <p class="text-xs text-gray-500 mt-1">Assign students to blocks so invigilators only monitor their own group.</p>
            </div>
            <span class="text-sm text-gray-500">{{ $exam->monitorBlocks->count() }} blocks</span>
        </div>

        <div class="p-6 space-y-6">
            @if($exam->monitorBlocks->isNotEmpty())
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($exam->monitorBlocks as $block)
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $block->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Assigned to:
                                <span class="font-medium text-gray-700">{{ $block->assignee?->name ?? 'Unassigned' }}</span>
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.exams.monitor-blocks.destroy', ['id' => $exam->id, 'block' => $block->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </div>

                    <div class="mt-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">Students</p>
                        <div class="space-y-2">
                            @forelse($block->attempts as $attempt)
                            <div class="flex items-center justify-between rounded-lg border border-white bg-white px-3 py-2 text-sm">
                                <span class="font-medium text-gray-800">{{ $attempt->user->name ?? 'Unknown Student' }}</span>
                                <span class="text-xs text-gray-500">{{ $attempt->user->admission_number ?? 'N/A' }}</span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">No students assigned yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admin.exams.monitor-blocks.store', $exam->id) }}" class="grid gap-6 lg:grid-cols-3">
                @csrf
                <div class="lg:col-span-1 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Name</label>
                        <input type="text" name="name" required placeholder="Block A / Room 1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Invigilator / Monitor</label>
                        <select name="assignee_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Unassigned</option>
                            @foreach($assignableMonitors as $monitor)
                            <option value="{{ $monitor->id }}">{{ $monitor->name }} ({{ str_replace('_', ' ', $monitor->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Create Block
                    </button>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Students To This Block</label>
                    <div class="max-h-72 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-2">
                        @forelse($attemptOptions as $attempt)
                        <label class="flex items-center justify-between gap-3 rounded-lg border border-white bg-white px-3 py-2">
                            <span class="flex items-center gap-3">
                                <input type="checkbox" name="attempt_ids[]" value="{{ $attempt->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span>
                                    <span class="block text-sm font-medium text-gray-800">{{ $attempt->user->name ?? 'Unknown Student' }}</span>
                                    <span class="block text-xs text-gray-500">
                                        {{ $attempt->user->admission_number ?? 'N/A' }}
                                        @if($attempt->monitorBlock)
                                        | Current: {{ $attempt->monitorBlock->name }}
                                        @endif
                                    </span>
                                </span>
                            </span>
                            <span class="text-xs uppercase text-gray-400">{{ str_replace('_', ' ', $attempt->status) }}</span>
                        </label>
                        @empty
                        <p class="text-sm text-gray-500">No attempts found for this exam yet.</p>
                        @endforelse
                    </div>
                </div>
            </form>
        </div>
    </div> -->


    <!-- ================= Questions ================= -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b bg-gray-50 rounded-t-xl flex justify-between items-center">
            <h2 class="text-base font-semibold text-gray-800">
                Attached Questions
            </h2>
            <span class="text-sm text-gray-500">
                {{ count($exam->selected_questions ?? []) }} Questions
            </span>
        </div>

        <div class="overflow-hidden">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-xs uppercase tracking-wider text-gray-600">
                        <th class="px-6 py-4 text-left font-semibold">#</th>
                        <th class="px-6 py-4 text-left font-semibold">Question</th>
                        <th class="px-6 py-4 text-left font-semibold">Marks</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($questions as $q)

                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-5 text-gray-500 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-5 text-gray-800">
                            {{ \Illuminate\Support\Str::limit($q->question_text,120) }}
                        </td>

                        <td class="px-6 py-5">
                            <span class="px-3 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">
                                {{ $q->marks }} Marks
                            </span>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="3"
                            class="px-6 py-10 text-center text-gray-400">
                            No questions attached.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection
