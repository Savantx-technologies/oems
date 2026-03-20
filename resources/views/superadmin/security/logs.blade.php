
@extends('layouts.superadmin')

@section('title', 'Security Logs')

@section('content')
<div class="p-4 sm:p-6">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4 sm:mb-6 gap-3">
        <div>
            <h3 class="text-xl sm:text-2xl font-semibold text-gray-800">Security & Activity Logs</h3>
            <p class="text-sm text-gray-500">Track actions by superadmin, sub-superadmin, admin, staff, and students from one place.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm text-gray-500">Total: {{ $logs->total() }} records</span>
            <a href="{{ route('superadmin.security.logs.export', request()->only('guard')) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-dark bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Export CSV
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('superadmin.security.logs') }}" class="mb-4 rounded-lg border border-gray-200 bg-white p-4">
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label for="guard" class="mb-1 block text-sm font-medium text-gray-700">Filter by Guard</label>
                <select id="guard" name="guard" class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="superadmin" {{ request('guard') === 'superadmin' ? 'selected' : '' }}>Superadmin / Sub-superadmin</option>
                    <option value="admin" {{ request('guard') === 'admin' ? 'selected' : '' }}>Admin / Staff</option>
                    <option value="student" {{ request('guard') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="web" {{ request('guard') === 'web' ? 'selected' : '' }}>Web User</option>
                </select>
            </div>
            <div class="flex items-end gap-2 sm:col-span-2">
                <button type="submit" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Apply Filter</button>
                <a href="{{ route('superadmin.security.logs') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border rounded-lg">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="sticky top-0 bg-gray-100 border-b z-10">
                    <tr class="text-gray-700">
                        <th class="px-3 py-2 text-left">Time</th>
                        <th class="px-3 py-2 text-left">Actor</th>
                        <th class="px-3 py-2 text-left">Event</th>
                        <th class="px-3 py-2 text-left">Action</th>
                        <th class="px-3 py-2 text-left">IP</th>
                        <th class="px-3 py-2 text-left">Browser</th>
                        <th class="px-3 py-2 text-left">Location</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
    
                    @forelse($logs as $log)

                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-500">
                                {{ $log->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs font-medium">
                                {{ $log->created_at->format('H:i:s') }}
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            <div class="font-medium text-gray-800">{{ $log->actor_name }}</div>
                            <div class="text-xs text-gray-500">{{ $log->actor_email }}</div>
                            <div class="mt-1 inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                {{ str_replace('_', ' ', $log->actor_role) }}
                            </div>
                        </td>

                        <td class="px-3 py-2">
                            @php
                            $color = match(true) {
                            str_contains($log->event,'failed') => 'bg-red-100 text-red-700',
                            str_contains($log->event,'expired') => 'bg-yellow-100 text-yellow-700',
                            str_contains($log->event,'logout') => 'bg-gray-100 text-gray-700',
                            str_contains($log->event,'page_visited') => 'bg-blue-100 text-blue-700',
                            default => 'bg-emerald-100 text-emerald-700'
                            };
                            @endphp

                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $color }}">
                                {{ $log->event }}
                            </span>
                        </td>

                        <td class="px-3 py-2 text-xs text-gray-700">
                            <div class="font-medium">{{ data_get($log->payload, 'method', '-') }}</div>
                            <div>{{ data_get($log->payload, 'route_name', $log->description) }}</div>
                            @if($log->description)
                            <div class="text-gray-500 mt-1">{{ $log->description }}</div>
                            @endif
                        </td>

                        <td class="px-3 py-2 text-xs text-gray-700">
                            {{ $log->ip_address }}
                        </td>

                        <td class="px-3 py-2 text-xs text-gray-600">
                            <div class="font-medium">
                                {{ $log->browser }} {{ $log->browser_version }}
                            </div>
                            <div class="text-gray-400">
                                {{ $log->platform }}
                            </div>
                        </td>

                        <td class="px-3 py-2 text-xs text-gray-700">
                            {{ $log->location }}
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-6">
                            No logs found
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $logs->appends(request()->query())->links() }}
    </div>

</div>
@endsection
