@extends('layouts.admin')

@section('title', 'Security Logs')

@section('content')
<div class="p-4 sm:p-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-2">
        <h3 class="text-xl sm:text-2xl font-semibold text-gray-800">
            Security & Activity Logs
        </h3>

        <span class="text-sm text-gray-500">
            Total: {{ $logs->total() }} records
        </span>
        <a href="{{ route('admin.security.logs.export') }}"
            class="px-3 py-2 text-sm bg-blue-600 text-dark rounded hover:bg-blue-700">
            Export CSV
        </a>

    </div>

    <div class="bg-white border rounded-lg">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="sticky top-0 bg-gray-100 border-b z-10">
                    <tr class="text-gray-700">
                        <th class="px-3 py-2 text-left">Time</th>
                        <th class="px-3 py-2 text-left">Event</th>
                        <th class="px-3 py-2 text-left">IP</th>
                        <th class="px-3 py-2 text-left">Browser</th>
                        <th class="px-3 py-2 text-left">Location</th>

                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($logs as $log)

                    <tr class="hover:bg-gray-50">

                        {{-- time --}}
                        <td class="px-3 py-2 whitespace-nowrap">
                            <div class="text-xs text-gray-500">
                                {{ $log->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs font-medium">
                                {{ $log->created_at->format('H:i:s') }}
                            </div>
                        </td>

                        </td>

                        {{-- event --}}
                        <td class="px-3 py-2">
                            @php
                            $color = match(true) {
                            str_contains($log->event,'failed') => 'bg-red-100 text-red-700',
                            str_contains($log->event,'expired') => 'bg-yellow-100 text-yellow-700',
                            str_contains($log->event,'logout') => 'bg-gray-100 text-gray-700',
                            default => 'bg-emerald-100 text-emerald-700'
                            };
                            @endphp

                            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $color }}">
                                {{ $log->event }}
                            </span>
                        </td>

                        {{-- ip --}}
                        <td class="px-3 py-2 text-xs text-gray-700">
                            {{ $log->ip_address }}
                        </td>

                        {{-- browser (short, not full UA) --}}
                        {{-- browser --}}
                        <td class="px-3 py-2 text-xs text-gray-600">
                            <div class="font-medium">
                                {{ $log->browser }} {{ $log->browser_version }}
                            </div>
                            <div class="text-gray-400">
                                {{ $log->platform }}
                            </div>
                        </td>
                        {{-- location --}}
                        <td class="px-3 py-2 text-xs text-gray-700">
                            {{ $log->location }}
                        </td>


                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-6">
                            No logs found
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $logs->links() }}
    </div>

</div>
@endsection