@extends('layouts.superadmin')

@section('title', 'Admin List')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800 mb-0">Admin Management</h2>
        <a href="{{ route('superadmin.admins.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New Admin
        </a>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <form method="GET" action="{{ route('superadmin.admins.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        placeholder="Search by name, email...">
                </div>
            </div>
            <div>
                <select name="school_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="role" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">All Roles</option>
                    <option value="school_admin" {{ request('role') == 'school_admin' ? 'selected' : '' }}>School Admin</option>
                    <option value="sub_admin" {{ request('role') == 'sub_admin' ? 'selected' : '' }}>Sub Admin</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="invigilator" {{ request('role') == 'invigilator' ? 'selected' : '' }}>Invigilator</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex justify-center items-center w-full px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'school_id']))
                    <a href="{{ route('superadmin.admins.index') }}" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="{{ $admins->count() === 1 ? '' : 'overflow-x-auto' }}">
        <table class="min-w-full divide-y divide-gray-200 text-sm {{ $admins->count() === 1 ? '' : '' }}">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">School</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created At</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($admins as $admin)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold mr-4 text-lg">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block rounded-full px-3 py-1 text-xs bg-gray-100 text-gray-700">
                            {{ str_replace('_', ' ', $admin->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($admin->school)
                        <span class="text-gray-800">{{ $admin->school->name }}</span>
                        @else
                        <span class="text-gray-400 italic text-xs">Global / None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $statusClasses = [
                        'active' => 'bg-green-100 text-green-800',
                        'blocked' => 'bg-red-100 text-red-800',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        ];
                        $statusClass = $statusClasses[$admin->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($admin->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        {{ $admin->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div
                            x-data="{ open: false }"
                            class="relative inline-block"
                            @click.away="open = false"
                            :class="{ 'z-50': open }">
                            <button
                                type="button"
                                @click="open = !open"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                            <div
                                x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-50 mt-2 w-44 bg-white rounded-md shadow-lg border border-gray-200 py-1"
                                style="display: none;">
                                <a href="{{ route('superadmin.admins.edit', $admin->id) }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M15.232 5.232l3.536 3.536M9 13h3L21 7l-3-3L9 13z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7M9 7V5a2 2 0 1 1 4 0v2m-6 0h12" />
                                    </svg>
                                    Delete
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No admins found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
    <div class="mt-4">
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection