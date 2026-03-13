@extends('layouts.superadmin')

@section('title', 'All Schools')

@section('content')
<div class="min-h-screen bg-gradient-to-tr from-slate-50 to-blue-50 pt-12 pb-16">
    <div class="max-w-6xl mx-auto px-2 md:px-6">
        <div class="rounded-2xl bg-white shadow-xl overflow-visible">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-blue-100 px-6 py-6">
                <div class="flex items-center gap-4">
                    <i class="bi bi-building text-blue-600 text-3xl"></i>
                    <div>
                        <div class="text-xs font-semibold text-slate-500 mb-1">Super Admin Panel</div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">School Management</h2>
                    </div>
                </div>
                <a href="{{ route('superadmin.schools.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition"
                   >
                    <i class="bi bi-plus-circle text-lg"></i>
                    <span>Add New School</span>
                </a>
            </div>

            {{-- Advanced Search / Filters --}}
            <div class="px-6 pt-6">
                <form method="GET" action="">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">School Name</label>
                            <input type="text"
                                   class="w-full rounded-lg border border-slate-200 focus:border-blue-400 focus:ring-blue-200 focus:ring-2 transition px-3 py-2 text-[15px] bg-slate-50"
                                   placeholder="Search by name"
                                   name="name" value="{{ request('name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">Code</label>
                            <input type="text"
                                   class="w-full rounded-lg border border-slate-200 focus:border-blue-400 focus:ring-blue-200 focus:ring-2 transition px-3 py-2 text-[15px] bg-slate-50"
                                   placeholder="School code"
                                   name="code" value="{{ request('code') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">City</label>
                            <input type="text"
                                   class="w-full rounded-lg border border-slate-200 focus:border-blue-400 focus:ring-blue-200 focus:ring-2 transition px-3 py-2 text-[15px] bg-slate-50"
                                   placeholder="City"
                                   name="city" value="{{ request('city') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-1">Status</label>
                            <select name="status"
                                    class="w-full rounded-lg border border-slate-200 focus:border-blue-400 focus:ring-blue-200 focus:ring-2 transition px-3 py-2 text-[15px] bg-slate-50">
                                <option value="">Any Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-1 px-4 py-2 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 font-semibold hover:bg-blue-100 transition">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('superadmin.schools.index') }}"
                               class="inline-flex items-center px-3 py-2 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 font-medium hover:bg-slate-100 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="pt-2 px-0">
                @if (session('success'))
                    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 flex items-center px-4 py-3 rounded-lg shadow-sm text-base">
                        <i class="bi bi-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto px-6 mt-2">
                    <table class="min-w-full mt-4 rounded-xl bg-white text-[15px]">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100 text-left">School Name</th>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100">School Code</th>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100">City</th>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100">Status</th>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100">Created</th>
                                <th class="font-bold text-slate-700 uppercase text-[15px] py-3 px-3 border-b-2 border-blue-100 text-center min-w-[120px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schools as $school)
                                <tr class="hover:bg-blue-50/60 transition-colors">
                                    <td class="py-3 px-3 align-middle">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if($school->logo)
                                                    <img class="h-12 w-12 rounded-lg object-contain border border-slate-200" src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }} logo">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-bold border border-slate-200 text-lg">
                                                        {{ substr($school->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-blue-700 text-base">{{ $school->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $school->board ?? 'Board N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="bg-slate-100 text-blue-800 px-2.5 py-1 rounded-md font-semibold text-sm border border-slate-200 inline-block">
                                            {{ $school->code }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span class="font-medium">{{ $school->city ? ucfirst($school->city) : '—' }}</span>
                                        @if($school->state)
                                            <div class="text-xs text-slate-400">{{ $school->state }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        @if ($school->status === 'active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold text-sm">
                                                <i class="bi bi-check-circle-fill mr-1"></i> Active
                                            </span>
                                        @elseif ($school->status === 'inactive')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gradient-to-r from-slate-400 to-slate-600 text-white font-semibold text-sm">
                                                <i class="bi bi-pause-circle mr-1"></i> Inactive
                                            </span>
                                        @elseif ($school->status === 'draft')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gradient-to-r from-yellow-300 to-yellow-500 text-yellow-900 font-semibold text-sm">
                                                <i class="bi bi-file-earmark-text mr-1"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 align-middle">
                                        <span title="{{ $school->created_at->format('Y-m-d H:i:s') }}">
                                            {{ $school->created_at->diffForHumans() }}
                                        </span>
                                        <div class="text-xs text-slate-400">{{ $school->created_at->format('d M, Y') }}</div>
                                    </td>
                                    <td class="py-3 px-3 align-middle text-center">
                                        <div class="flex justify-center items-center gap-1">
                                            <a href="#"
                                               class="inline-flex items-center justify-center p-2 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition border border-transparent"
                                               aria-label="View Details"
                                               title="View Details"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.schools.edit', $school->id) }}"
                                               class="inline-flex items-center justify-center p-2 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition border border-transparent"
                                               aria-label="Edit School"
                                               title="Edit School"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($school->status === 'draft')
                                            <a href="{{ route('superadmin.schools.create-admin', $school->id) }}"
                                               class="inline-flex items-center justify-center p-2 rounded-lg text-green-600 bg-green-50 hover:bg-green-100 transition border border-transparent"
                                               aria-label="Continue Setup"
                                               title="Continue Setup"
                                            >
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-16 text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <i class="bi bi-building-exclamation text-4xl mb-2"></i>
                                            <div class="font-semibold text-xl mb-1">No schools found</div>
                                            <a href="{{ route('superadmin.schools.create') }}"
                                               class="mt-3 inline-flex items-center px-4 py-2 rounded-lg border border-blue-300 bg-blue-50 font-semibold text-blue-700 hover:bg-blue-100 transition">
                                                <i class="bi bi-plus-circle mr-1"></i> Create your first school
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end items-center px-6 pb-4 mt-4">
                    {{ $schools->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
