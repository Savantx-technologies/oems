@extends('layouts.superadmin')

@section('title', 'All Exams')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-800">All Exams</h1>
            <div class="flex items-center gap-1 text-sm font-medium border border-gray-200 bg-white shadow-sm rounded-full p-1">
                <a href="{{ route('superadmin.exams.index', array_merge(request()->query(), ['filter' => null, 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ !request('filter') ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">All</a>
                <a href="{{ route('superadmin.exams.index', array_merge(request()->query(), ['filter' => 'live', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'live' ? 'bg-green-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Live</a>
                <a href="{{ route('superadmin.exams.index', array_merge(request()->query(), ['filter' => 'upcoming', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'upcoming' ? 'bg-yellow-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Upcoming</a>
                <a href="{{ route('superadmin.exams.index', array_merge(request()->query(), ['filter' => 'closed', 'page' => null])) }}" class="px-3 py-1 rounded-full transition-colors {{ request('filter') == 'closed' ? 'bg-red-500 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">Closed</a>
            </div>
        </div>

        <!-- Filter by School -->
        <form method="GET" class="flex items-center gap-2">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            @endif
            <select name="school_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 min-w-[200px]">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                    <tr>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Class / Subject</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Created At</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                            @if($exam->school)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($exam->school->logo)
                                            <img class="h-10 w-10 rounded-lg object-contain border border-gray-200" src="{{ asset('storage/' . $exam->school->logo) }}" alt="{{ $exam->school->name }} logo">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200 text-base">
                                                {{ substr($exam->school->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900">{{ $exam->school->name }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400">Unknown School</span>
                            @endif
                            </td>
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $exam->title }}</td>
                            <td class="px-6 py-3">
                                <div>Class {{ $exam->class }}</div>
                                <div class="text-xs text-gray-500">{{ $exam->subject }}</div>
                            </td>
                            <td class="px-6 py-3 capitalize">{{ $exam->exam_type }}</td>
                            <td class="px-6 py-3">
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'published' => 'bg-green-100 text-green-700',
                                        'closed' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$exam->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($exam->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">
                                {{ $exam->created_at ? $exam->created_at->format('M d, Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                @php
                                    $now = now();
                                    $isLive = $exam->schedule && $now->between($exam->schedule->start_at, $exam->schedule->end_at);
                                    $isOver = $exam->schedule && $exam->schedule->end_at && $exam->schedule->end_at->isPast();
                                @endphp
                                <div class="flex flex-wrap justify-end gap-x-3 gap-y-2">
                                    <a href="{{ route('superadmin.exams.show', $exam->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('superadmin.exams.monitor-blocks.index', $exam->id) }}" class="text-violet-600 hover:text-violet-900 text-xs font-medium">
                                        Blocks
                                    </a>
                                    @if($exam->status === 'published' && $isLive)
                                        <a href="{{ route('superadmin.exams.monitor', $exam->id) }}" class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                            <i class="bi bi-camera-video"></i> Monitor
                                        </a>
                                    @endif
                                    @if($exam->status === 'published')
                                        <form action="{{ route('superadmin.exams.force-close', $exam->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $isOver ? 'close' : 'force stop' }} this exam?');" class="inline">
                                            @csrf
                                            <button type="submit" class="{{ $isOver ? 'text-orange-600 hover:text-orange-900' : 'text-red-600 hover:text-red-900' }} text-xs font-medium">
                                                {{ $isOver ? 'Mark Closed' : 'Force Stop' }}
                                            </button>
                                        </form>
                                    @elseif($exam->status === 'draft')
                                        <span class="text-gray-400 text-xs">Draft</span>
                                    @else
                                        <span class="text-gray-400 text-xs">Closed</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No exams found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $exams->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
