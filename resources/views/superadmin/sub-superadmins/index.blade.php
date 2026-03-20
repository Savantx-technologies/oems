@extends('layouts.superadmin')

@section('title', 'Sub Super Admins')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Sub Super Admins</h2>
            <p class="text-sm text-gray-500">Create limited-access superadmin accounts and control exactly what they can open.</p>
        </div>
        <a href="{{ route('superadmin.sub-superadmins.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow transition">
            <i class="bi bi-plus-circle mr-2"></i> Add Sub Super Admin
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Access Summary</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subSuperAdmins as $subSuperAdmin)
                @php
                    $enabledPermissions = collect($subSuperAdmin->permissions ?? [])->filter()->keys()->map(fn ($key) => \App\Models\Setting::superAdminSidebarSections()[$key] ?? $key);
                @endphp
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $subSuperAdmin->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $subSuperAdmin->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subSuperAdmin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $subSuperAdmin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $enabledPermissions->isNotEmpty() ? $enabledPermissions->take(3)->implode(', ') . ($enabledPermissions->count() > 3 ? ' +' . ($enabledPermissions->count() - 3) . ' more' : '') : 'No section access assigned' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('superadmin.sub-superadmins.edit', $subSuperAdmin) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit Access</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">No sub super admins found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subSuperAdmins->hasPages())
    <div class="mt-4">
        {{ $subSuperAdmins->links() }}
    </div>
    @endif
</div>
@endsection
