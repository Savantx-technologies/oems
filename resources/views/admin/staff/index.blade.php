@extends('layouts.admin')

@section('title', 'Manage Staff')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Staff</h1>
            <p class="text-sm text-gray-500">View, edit, and manage staff accounts.</p>
        </div>
        <a href="{{ route('admin.staff.create.step1') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
            Add New Staff
        </a>
    </div>

    @php
        $tab = $tab ?? 'staff';
        $status = $status ?? 'all';
    @endphp

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.staff.index', ['tab' => 'staff']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab === 'staff' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
            Staff Accounts
        </a>
        <a href="{{ route('admin.staff.index', ['tab' => 'requests']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-lg border {{ $tab === 'requests' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
            Requested Staff
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="flex-1">
                <label for="search" class="text-sm font-medium text-gray-700 sr-only">Search</label>
                <input type="text" name="search" id="search" placeholder="Search by name, email, or type..." value="{{ request('search') }}" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full">
            </div>
            @if($tab === 'requests')
                <div>
                    <label for="status" class="text-sm font-medium text-gray-700 sr-only">Status</label>
                    <select name="status" id="status" class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            @endif
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Search</button>
            <a href="{{ route('admin.staff.index', ['tab' => $tab]) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Reset</a>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($tab === 'requests')
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Requested At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">
                                <div class="flex items-center gap-3">
                                    @if($request->photo)
                                    <img src="{{ asset('storage/' . $request->photo) }}" alt="{{ $request->name }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                    <span class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold">
                                        {{ strtoupper(substr($request->name, 0, 1)) }}
                                    </span>
                                    @endif
                                    <div>
                                        {{ $request->name }}
                                        <div class="text-xs text-gray-500">{{ $request->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $request->role)) }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $request->staff_type)) }}</td>
                            <td class="px-6 py-3">
                                @php
                                $statusClasses = [
                                    'pending_verification' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabel = $request->status === 'pending_verification' ? 'Pending' : ucfirst($request->status);
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($request->status === 'rejected' && $request->rejection_reason)
                                    <span class="ml-2 inline-block align-middle text-gray-400" title="{{ $request->rejection_reason }}">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="16" x2="12" y2="12" />
                                            <line x1="12" y1="8" x2="12.01" y2="8" />
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No staff requests found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Joined At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-800">
                                <div class="flex items-center gap-3">
                                    @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                    <span class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </span>
                                    @endif
                                    <div>
                                        {{ $member->name }}
                                        <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $member->staff_type)) }}</td>
                            <td class="px-6 py-3">
                                @php
                                $statusClasses = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-red-100 text-red-700',
                                ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClasses[$member->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500 text-xs">
                                {{ $member->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No staff members found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
        @if(($tab === 'requests' && $requests->hasPages()) || ($tab !== 'requests' && $staff->hasPages()))
        <div class="px-6 py-4 border-t">
            @if($tab === 'requests')
                {{ $requests->withQueryString()->links() }}
            @else
                {{ $staff->withQueryString()->links() }}
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
