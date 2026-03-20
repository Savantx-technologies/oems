@extends('layouts.admin')

@section('title', 'Monitor Blocks')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-600">Live Monitoring Setup</p>
            <h1 class="text-3xl font-semibold text-gray-900">Monitoring Block Assignment</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $exam->title }} | Class {{ $exam->class }} | {{ $exam->subject }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.exams.show', $exam->id) }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 transition shadow-sm">Exam Details</a>
            <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">Back</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Total Students</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $rosterStudents->count() }}</p>
            <p class="mt-1 text-sm text-gray-500">Class roster available for assignment.</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Started</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $attemptsByUser->count() }}</p>
            <p class="mt-1 text-sm text-gray-500">Students who have already opened the exam.</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">Blocks</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $exam->monitorBlocks->count() }}</p>
            <p class="mt-1 text-sm text-gray-500">Manual or auto-created monitoring groups.</p>
        </div>
        <div class="rounded-2xl border border-violet-100 bg-gradient-to-br from-violet-50 to-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-violet-600">Unassigned</p>
            <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $rosterStudents->filter(fn ($student) => !$exam->monitorBlocks->first(fn ($block) => $block->students->contains('id', $student->id)))->count() }}</p>
            <p class="mt-1 text-sm text-gray-500">Students not yet mapped to any block.</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Auto Assign</h2>
                        <p class="text-sm text-gray-500">Quickly split the class into balanced blocks.</p>
                    </div>
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Fast Setup</span>
                </div>
                <form method="POST" action="{{ route('admin.exams.monitor-blocks.auto-assign', $exam->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Students Per Block</label>
                        <input type="number" name="students_per_block" value="15" min="1" max="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Monitors Round-Robin</label>
                        <select name="assignee_ids[]" multiple size="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($assignableMonitors as $monitor)
                            <option value="{{ $monitor->id }}">{{ $monitor->name }} ({{ str_replace('_', ' ', $monitor->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Auto Create Blocks</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Create Manual Block</h2>
                    <p class="text-sm text-gray-500">Make a room, zone, or invigilator-specific block manually.</p>
                </div>
                <form method="POST" action="{{ route('admin.exams.monitor-blocks.store', $exam->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Block Name</label>
                        <input type="text" name="name" required placeholder="Block A / Room 1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Monitor</label>
                        <select name="assignee_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Unassigned</option>
                            @foreach($assignableMonitors as $monitor)
                            <option value="{{ $monitor->id }}">{{ $monitor->name }} ({{ str_replace('_', ' ', $monitor->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign Students Now</label>
                        <select name="student_ids[]" multiple size="8" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($rosterStudents as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->admission_number ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Create Empty Block</button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Existing Blocks</h2>
                        <p class="text-sm text-gray-500">Rename blocks or change monitor responsibility.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">{{ $exam->monitorBlocks->count() }} total</span>
                </div>
                <div class="grid gap-4 lg:grid-cols-2">
                    @forelse($exam->monitorBlocks as $block)
                    <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-4 shadow-sm">
                        <form method="POST" action="{{ route('admin.exams.monitor-blocks.update', ['id' => $exam->id, 'block' => $block->id]) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Block Name</label>
                                <input type="text" name="name" value="{{ $block->name }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Assigned Monitor</label>
                                <select name="assignee_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Unassigned</option>
                                    @foreach($assignableMonitors as $monitor)
                                    <option value="{{ $monitor->id }}" {{ $block->assignee_id == $monitor->id && $block->assignee_type === \App\Models\Admin::class ? 'selected' : '' }}>
                                        {{ $monitor->name }} ({{ str_replace('_', ' ', $monitor->role) }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-medium text-gray-600 border border-gray-200">{{ $block->students->count() }} students</span>
                                <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">Save</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.exams.monitor-blocks.destroy', ['id' => $exam->id, 'block' => $block->id]) }}" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Delete Block</button>
                        </form>
                    </div>
                    @empty
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-500 lg:col-span-2">
                        No blocks created yet.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-900">Move Students Between Blocks</h2>
                    <p class="text-sm text-gray-500 mt-1">Bulk assign or fine-tune individual student placement.</p>
                </div>
                <form id="admin-bulk-assign-{{ $exam->id }}" method="POST" action="{{ route('admin.exams.monitor-students.bulk-move', ['id' => $exam->id]) }}" class="px-6 py-4 border-b bg-blue-50/60 flex flex-col lg:flex-row lg:items-center gap-3">
                    @csrf
                    <div class="text-sm font-medium text-gray-700">Select multiple students and assign them in one click.</div>
                    <div class="flex flex-1 flex-col sm:flex-row gap-3">
                        <select name="monitor_block_id" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Unassigned</option>
                            @foreach($exam->monitorBlocks as $block)
                            <option value="{{ $block->id }}">{{ $block->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Assign Selected Students</button>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" onclick="document.querySelectorAll('.admin-bulk-student-checkbox').forEach(cb => cb.checked = this.checked)">
                                </th>
                                <th class="px-6 py-3 text-left">Student</th>
                                <th class="px-6 py-3 text-left">Current Block</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Reassign</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($rosterStudents as $student)
                            @php
                            $attempt = $attemptsByUser->get($student->id);
                            $currentBlock = $exam->monitorBlocks->first(fn ($block) => $block->students->contains('id', $student->id));
                            @endphp
                            <tr>
                                <td class="px-6 py-3">
                                    <input form="admin-bulk-assign-{{ $exam->id }}" type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="admin-bulk-student-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $student->admission_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-3 text-gray-700">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $currentBlock ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $currentBlock->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase {{ !$attempt ? 'bg-gray-100 text-gray-600' : ($attempt->status === 'in_progress' ? 'bg-emerald-50 text-emerald-700' : ($attempt->status === 'submitted' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700')) }}">
                                        {{ $attempt ? str_replace('_', ' ', $attempt->status) : 'NOT STARTED' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <form method="POST" action="{{ route('admin.exams.monitor-students.move', ['id' => $exam->id, 'student' => $student->id]) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="monitor_block_id" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Unassigned</option>
                                            @foreach($exam->monitorBlocks as $block)
                                            <option value="{{ $block->id }}" {{ $currentBlock && $currentBlock->id === $block->id ? 'selected' : '' }}>{{ $block->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">Move</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No students found for this class.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
