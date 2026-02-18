@extends('layouts.superadmin')

@section('title', 'All Exams')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">All Exams</h1>
        
        <!-- Filter by School -->
        <form method="GET" class="flex items-center gap-2">
            <select name="school_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 min-w-[200px]">
                <option value="">All Schools</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }} (ID: {{ $school->id }})
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
                        <th class="px-6 py-3">ID</th>
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
                            <td class="px-6 py-3 font-mono text-xs text-gray-500">#{{ $exam->id }}</td>
                            <td class="px-6 py-3">
                                <div class="font-medium text-gray-900">{{ $exam->school->name ?? 'Unknown School' }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $exam->school_id }}</div>
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
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
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
