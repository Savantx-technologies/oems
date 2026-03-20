@extends('layouts.superadmin')

@section('title', 'Add Sub Super Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Add Sub Super Admin</h2>
            <p class="text-slate-500">Create a limited-access superadmin and choose exactly what they can open.</p>
        </div>
        <a href="{{ route('superadmin.sub-superadmins.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition font-semibold shadow-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-6 py-4">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('superadmin.sub-superadmins.store') }}" method="POST" class="space-y-8">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <section class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4">Basic Information</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div class="flex items-center pt-7">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">Account is active</label>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-700">Panel Access</h3>
                            <p class="text-sm text-slate-500">Turn on only the sections this user should be able to access.</p>
                        </div>
                        <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                            For live exam watching, enable `Live Monitoring`. Enabling `Exam Control` is also useful if they need to browse all exams.
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($sections as $key => $label)
                        <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <input type="checkbox" name="permissions[{{ $key }}]" value="1" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old("permissions.$key", $defaultPermissions[$key] ?? false) ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-medium text-slate-800">{{ $label }}</span>
                                <span class="block text-xs text-slate-500">Allow access to {{ strtolower($label) }}.</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </section>
            </div>

            <div>
                <section class="bg-white rounded-xl shadow p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-blue-700">Save</h3>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow transition">
                        <i class="bi bi-person-plus"></i> Create Sub Super Admin
                    </button>
                    <a href="{{ route('superadmin.sub-superadmins.index') }}" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold shadow transition">
                        Cancel
                    </a>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection
