@extends('layouts.superadmin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Roles & Permissions</h1>
            <p class="text-sm text-gray-500">Manage which admin sidebar sections are visible for each role. Backend role restrictions remain protected separately.</p>
        </div>
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            School-level roles: `school_admin`, `sub_admin`, `invigilator`, `staff`
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.roles-permissions.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Admin Sidebar Visibility Matrix</h2>
                <p class="mt-1 text-sm text-gray-500">Turn sections on or off for each role from the superadmin panel.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Sidebar Section</th>
                            @foreach($roles as $role => $roleSections)
                                <th class="px-4 py-4 text-center font-semibold">{{ ucwords(str_replace('_', ' ', $role)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($sections as $sectionKey => $sectionLabel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $sectionLabel }}</td>
                                @foreach($roles as $role => $roleSections)
                                    <td class="px-4 py-4 text-center">
                                        <label class="inline-flex items-center justify-center">
                                            <input
                                                type="checkbox"
                                                name="permissions[{{ $role }}][{{ $sectionKey }}]"
                                                value="1"
                                                {{ !empty($permissions[$role][$sectionKey]) ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            >
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                <h3 class="font-semibold">What this page controls</h3>
                <p class="mt-2">This page controls sidebar visibility for admin roles. It helps you decide what each role should see in the panel.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
                <h3 class="font-semibold">Safety note</h3>
                <p class="mt-2">Hidden menu items do not replace backend security. Existing route middleware and role checks still protect sensitive actions.</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Save Permissions
            </button>
        </div>
    </form>
</div>
@endsection
