@extends('layouts.superadmin')

@section('title', 'Add New Admin')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-2">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-1 flex items-center gap-2">
                <span class="text-blue-600"><i class="bi bi-person"></i></span> Add New Admin
            </h2>
            <p class="text-slate-500">Create a new administrator or staff member.</p>
        </div>
        <a href="{{ route('superadmin.admins.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition font-semibold shadow-sm">
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

    <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-8">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Information -->
                <section class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-person"></i> Basic Information
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Mobile Number
                            </label>
                            <input type="text" name="mobile" value="{{ old('mobile') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900 bg-slate-50">
                        </div>
                    </div>
                </section>

                <!-- Role & Access -->
                <section class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-shield-lock"></i> Role & Access
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="roleSelect" required
                                class="block w-full rounded-md border-gray-300 bg-slate-50 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900">
                                <option value="">Select Role</option>
                                <option value="school_admin" {{ old('role')=='school_admin' ? 'selected' : '' }}>
                                    School Admin
                                </option>
                                <option value="sub_admin" {{ old('role')=='sub_admin' ? 'selected' : '' }}>
                                    Sub Admin
                                </option>
                                <option value="invigilator" {{ old('role')=='invigilator' ? 'selected' : '' }}>
                                    Invigilator
                                </option>
                                <option value="staff" {{ old('role')=='staff' ? 'selected' : '' }}>
                                    Staff
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Assign School
                            </label>
                            <select name="school_id" id="schoolSelect"
                                class="block w-full rounded-md border-gray-300 bg-slate-50 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900">
                                <option value="">Global (No School)</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id')==$school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->code }})
                                </option>
                                @endforeach
                            </select>
                            <span class="block text-xs text-slate-500 mt-1">Required for School Admins and Staff.</span>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Account Status
                            </label>
                            <select name="status"
                                class="block w-full rounded-md border-gray-300 bg-slate-50 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900">
                                <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="blocked" {{ old('status')=='blocked' ? 'selected' : '' }}>Blocked
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">
                                Login Method
                            </label>
                            <select name="login_method"
                                class="block w-full rounded-md border-gray-300 bg-slate-50 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 px-3 py-2 text-gray-900">
                                <option value="password" {{ old('login_method')=='password' ? 'selected' : '' }}>
                                    Password</option>
                                <option value="otp" {{ old('login_method')=='otp' ? 'selected' : '' }}>OTP Only</option>
                            </select>
                        </div>
                    </div>
                </section>
            </div>
            <!-- Side Settings -->
            <div>
                <section class="bg-white rounded-xl shadow p-6 space-y-6">
                    <h3 class="text-lg font-semibold text-blue-700 mb-2 flex items-center gap-2">
                        <i class="bi bi-gear"></i> Settings
                    </h3>
                    <div class="flex items-center gap-3">
                        <input id="twoFactor" name="two_factor" type="checkbox" value="1"
                            class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300" {{
                            old('two_factor', 1) ? 'checked' : '' }}>
                        <label for="twoFactor" class="block text-sm font-medium text-gray-700 select-none">
                            Enable Two-Factor Auth
                        </label>
                    </div>
                    <div class="border-t border-slate-100 pt-5 flex flex-col gap-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow transition">
                            <i class="bi bi-plus-circle text-lg"></i> Create Admin
                        </button>
                        <a href="{{ route('superadmin.admins.index') }}"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold shadow transition">
                            Cancel
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </form>
</div>
@endsection