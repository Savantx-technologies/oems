@extends('layouts.admin')

@section('title','Exams')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Page header -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-col gap-4">
            <h1 class="text-2xl font-semibold text-gray-800">Exams</h1>
            <div class="mobile-safe-scroll flex max-w-full items-center gap-1 overflow-x-auto rounded-2xl border border-gray-200 bg-white p-1 text-sm font-medium shadow-sm">
                <a href="{{ route('admin.exams.index', array_merge(request()->query(), ['filter' => null, 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ !request('filter') ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">All</a>
                <a href="{{ route('admin.exams.index', array_merge(request()->query(), ['filter' => 'live', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'live' ? 'bg-green-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Live</a>
                <a href="{{ route('admin.exams.index', array_merge(request()->query(), ['filter' => 'upcoming', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'upcoming' ? 'bg-yellow-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Upcoming</a>
                <a href="{{ route('admin.exams.index', array_merge(request()->query(), ['filter' => 'closed', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'closed' ? 'bg-red-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Closed</a>
            </div>
        </div>

        <a href="{{ route('admin.exams.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            + Create Exam
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">

        <div class="space-y-4 p-4 md:hidden">
            @forelse($exams as $exam)
                @php
                    $now = now();
                    $isLive = $exam->schedule && $now->between($exam->schedule->start_at, $exam->schedule->end_at);
                    $ready = ($exam->exam_type === 'mock' || $exam->schedule)
                        && !empty($exam->selected_questions) && count($exam->selected_questions) > 0;
                    $map = [
                        'draft' => 'bg-gray-100 text-gray-700',
                        'published' => 'bg-green-100 text-green-700',
                        'closed' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <a href="{{ route('admin.exams.show',$exam->id) }}" class="block truncate text-base font-semibold text-indigo-700">
                                {{ $exam->title }}
                            </a>
                            <div class="mt-1 text-xs text-gray-500">{{ $exam->academic_session }}</div>
                        </div>
                        <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold {{ $map[$exam->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($exam->status) }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-400">Class</div>
                            <div class="mt-1 font-medium text-gray-800">Class {{ $exam->class }}</div>
                            <div class="text-xs text-gray-500">{{ $exam->subject }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-400">Marks</div>
                            <div class="mt-1 font-medium text-gray-800">{{ $exam->total_marks }} / {{ $exam->pass_marks }}</div>
                            <div class="text-xs text-gray-500">Total / Pass</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-[11px] uppercase tracking-wide text-gray-400">Schedule</div>
                        @if($exam->exam_type === 'mock')
                            <div class="mt-1 text-sm font-medium text-gray-500">No schedule required</div>
                        @elseif($exam->schedule)
                            <div class="mt-1 text-sm font-medium text-gray-800">{{ $exam->schedule->start_at->format('d M Y H:i') }}</div>
                            <div class="text-xs text-gray-500">to {{ $exam->schedule->end_at->format('d M Y H:i') }}</div>
                        @else
                            <div class="mt-1 text-sm font-medium text-red-600">Not scheduled</div>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($exam->status === 'draft')
                            <a href="{{ route('admin.exams.questions',$exam->id) }}" class="rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700">Questions</a>
                            <a href="{{ route('admin.exams.schedule',$exam->id) }}" class="rounded-xl bg-gray-100 px-3 py-2 text-xs font-medium text-gray-700">Schedule</a>
                        @endif

                        @if($exam->status === 'published' && $isLive)
                            <a href="{{ route('admin.exams.monitor', $exam->id) }}" class="rounded-xl bg-indigo-600 px-3 py-2 text-xs font-medium text-white">Monitor</a>
                        @endif

                        @if($exam->status === 'draft' && $ready)
                            <form method="POST" action="{{ route('admin.exams.publish',$exam->id) }}" onsubmit="return confirm('Publish this exam?')">
                                @csrf
                                <button class="rounded-xl bg-green-50 px-3 py-2 text-xs font-medium text-green-700">Publish</button>
                            </form>
                        @elseif($exam->status === 'draft')
                            <span class="rounded-xl bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-800">Not ready</span>
                        @endif

                        <a href="{{ route('admin.exams.show',$exam->id) }}" class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700">View</a>

                        @if(auth('admin')->user()->role === 'school_admin')
                            <a href="{{ route('admin.exams.monitor-blocks.index', $exam->id) }}" class="rounded-xl bg-violet-50 px-3 py-2 text-xs font-medium text-violet-700">Blocks</a>
                        @endif

                        @if($exam->status !== 'closed')
                            <a href="{{ route('admin.exams.edit',$exam->id) }}" class="rounded-xl bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">Edit</a>
                        @endif

                        @if($exam->status === 'published')
                            <form method="POST" action="{{ route('admin.exams.close',$exam->id) }}" onsubmit="return confirm('Close this exam?')">
                                @csrf
                                <button class="rounded-xl bg-red-50 px-3 py-2 text-xs font-medium text-red-700">Close</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-gray-500">
                    No exams created yet.
                </div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Exam</th>
                        <th class="px-5 py-3 text-left">Class / Subject</th>
                        <th class="px-5 py-3 text-left">Schedule</th>
                        <th class="px-5 py-3 text-left">Total Marks</th>
                        <th class="px-5 py-3 text-left">Pass Marks</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($exams as $exam)

                    <tr class="hover:bg-gray-50">

                        <td class="px-5 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-3">
                            <a href="{{ route('admin.exams.show',$exam->id) }}"
                                class="font-medium text-indigo-700 hover:underline">
                                {{ $exam->title }}
                            </a>

                            <div class="text-xs text-gray-500">
                                {{ $exam->academic_session }}
                            </div>
                        </td>

                        <td class="px-5 py-3">
                            <div class="text-gray-800">
                                Class {{ $exam->class }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $exam->subject }}
                            </div>
                        </td>

                        <td class="px-5 py-3 text-sm text-gray-600">

                            @if($exam->exam_type === 'mock')

                            <span class="text-gray-400">-</span>

                            @elseif($exam->schedule)

                            <div>
                                {{ $exam->schedule->start_at->format('d M Y H:i') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                to {{ $exam->schedule->end_at->format('d M Y H:i') }}
                            </div>

                            @else

                            <span class="text-xs text-red-600 font-medium">
                                Not scheduled
                            </span>

                            @endif

                        </td>


                        <td class="px-5 py-3">
                            {{ $exam->total_marks }}
                        </td>
                        <td class="px-5 py-3">
                            {{ $exam->pass_marks }}
                        </td>

                        <td class="px-5 py-3">
                            @php
                            $map = [
                            'draft' => 'bg-gray-100 text-gray-700',
                            'published' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-red-100 text-red-700',
                            ];
                            @endphp

                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $map[$exam->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($exam->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-3 text-right">
                            <div class="flex justify-end gap-2">

                                @php
                                $now = now();
                                $isLive = $exam->schedule && $now->between($exam->schedule->start_at, $exam->schedule->end_at);
                                // Mock exams don't need a schedule to be ready for publishing
                                $ready = ($exam->exam_type === 'mock' || $exam->schedule)
                                    && !empty($exam->selected_questions) && count($exam->selected_questions) > 0;
                                @endphp

                                @if($exam->status === 'draft')

                                <a href="{{ route('admin.exams.questions',$exam->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                                    Questions
                                </a>

                                <a href="{{ route('admin.exams.schedule',$exam->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    Schedule
                                </a>
                                @endif

                                {{-- Monitor Button: Show if exam is published and is currently live --}}
                                @if($exam->status === 'published' && $isLive)
                                <a href="{{ route('admin.exams.monitor', $exam->id) }}"
                                    class="px-3 py-1 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700">
                                    <i class="bi bi-camera-video"></i> Monitor
                                </a>
                                @endif

                                @if($exam->status === 'closed')
                                <span
                                    class="px-3 py-1.5 text-center w-full font-semibold rounded-lg bg-gray-200 text-red-700">
                                    Exam closed
                                </span>
                                @endif

                                @if($exam->status === 'draft')
                                @if($ready)
                                <form method="POST" action="{{ route('admin.exams.publish',$exam->id) }}"
                                    onsubmit="return confirm('Publish this exam?')">
                                    @csrf
                                    <button
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-green-50 text-green-700 hover:bg-green-100">
                                        Publish
                                    </button>
                                </form>
                                @else
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-yellow-100 text-yellow-800"
                                    title="Add questions and schedule before publishing">
                                    Not ready
                                </span>
                                @endif
                                @endif

                                <a href="{{ route('admin.exams.show',$exam->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                                    View
                                </a>

                                @if(auth('admin')->user()->role === 'school_admin')
                                <a href="{{ route('admin.exams.monitor-blocks.index', $exam->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-violet-50 text-violet-700 hover:bg-violet-100">
                                    Blocks
                                </a>
                                @endif

                                @if($exam->status !== 'closed')
                                <a href="{{ route('admin.exams.edit',$exam->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100">
                                    Edit
                                </a>
                                @endif

                                @if($exam->status === 'published')
                                <form method="POST" action="{{ route('admin.exams.close',$exam->id) }}"
                                    onsubmit="return confirm('Close this exam?')">
                                    @csrf
                                    <button
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100">
                                        Close
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            No exams created yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">
            {{ $exams->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
