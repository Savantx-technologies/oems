@extends('layouts.superadmin')

@section('title', 'Edit School Admin')

@section('content')

<div class="max-w-4xl mx-auto py-10 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-blue-700 flex items-center gap-2">
            <span>Edit School Admin</span>
        </h2>
        <p class="text-gray-500">
            Update details for the primary administrator of 
            <span class="font-medium text-blue-600">{{ $school->name }}</span>
        </p>
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

    <form action="{{ route('superadmin.schools.admin-update', ['school' => $school->id, 'admin' => $admin->id]) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Personal Details -->
        <section class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-2xl">👤</span>
                <h3 class="text-lg font-semibold text-gray-700">Personal Details</h3>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Full Name (as per Aadhaar) *</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 px-3 py-2 bg-gray-50">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', $admin->dob) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                        <option value="Male" {{ old('gender', $admin->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $admin->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $admin->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Father / Guardian Name</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $admin->father_name ?? '') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Mobile Number *</label>
                    <input type="tel" name="mobile" value="{{ old('mobile', $admin->mobile) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email ID *</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>
            </div>
        </section>

        <!-- Aadhaar Details -->
        <section class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl shadow p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-lg">🪪</span>
                <h3 class="text-lg font-semibold text-yellow-700">Aadhaar Details <span class="font-normal text-yellow-600 text-sm"></span></h3>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 text-sm font-medium text-yellow-900">Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number', $admin->aadhaar_number) }}" placeholder="XXXX-XXXX-1234"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-400 px-3 py-2 bg-white text-gray-900">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-yellow-900">Aadhaar Name</label>
                    <input type="text" name="aadhaar_name" value="{{ old('aadhaar_name', $admin->aadhaar_name) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-400 px-3 py-2 bg-white text-gray-900">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-yellow-900">Aadhaar DOB</label>
                    <input type="date" name="aadhaar_dob" value="{{ old('aadhaar_dob', $admin->aadhaar_dob) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-400 px-3 py-2 bg-white text-gray-900">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-yellow-900">Aadhaar Gender</label>
                    <select name="aadhaar_gender" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-400 px-3 py-2 bg-white text-gray-900">
                        <option value="">Select</option>
                        <option value="male" {{ old('aadhaar_gender', $admin->aadhaar_gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('aadhaar_gender', $admin->aadhaar_gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('aadhaar_gender', $admin->aadhaar_gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Role Details -->
        <section class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-2xl">🏢</span>
                <h3 class="text-lg font-semibold text-gray-700">Admin Role Details</h3>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Role</label>
                    <input type="text" value="School Admin" readonly disabled
                        class="block w-full rounded-md border-gray-200 bg-gray-100 text-gray-800 px-3 py-2 cursor-not-allowed">
                    <input type="hidden" name="role" value="school_admin">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Assigned School</label>
                    <input type="text" value="{{ $school->name }}" readonly disabled
                        class="block w-full rounded-md border-gray-200 bg-gray-100 text-gray-800 px-3 py-2 cursor-not-allowed">
                </div>
            </div>
        </section>

        <!-- Account Access -->
        <section class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center gap-2 mb-6">
                <span class="text-2xl">🔐</span>
                <h3 class="text-lg font-semibold text-gray-700">Account Access Setup</h3>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                    <input type="text" value="{{ $admin->email }}" readonly disabled
                        class="block w-full rounded-md border-gray-200 bg-gray-100 text-gray-800 px-3 py-2 cursor-not-allowed">
                    <span class="text-xs text-gray-500 mt-1 block">Username is linked to email</span>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Login Method</label>
                    <select name="login_method"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                        <option value="password" {{ old('login_method', $admin->login_method) == 'password' ? 'selected' : '' }}>Password</option>
                        <option value="otp" {{ old('login_method', $admin->login_method) == 'otp' ? 'selected' : '' }}>OTP Only</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Account Status</label>
                    <select name="status"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                        <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ old('status', $admin->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="blocked" {{ old('status', $admin->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
                <div class="flex items-center md:col-span-2 sm:col-span-2 mt-3">
                    <input id="two_factor" type="checkbox" name="two_factor" value="1" {{ $admin->two_factor ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="two_factor" class="ml-2 block text-sm font-medium text-gray-700">
                        Two-Factor Authentication
                    </label>
                </div>
            </div>
        </section>

        <!-- Actions -->
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('superadmin.schools.index') }}"
               class="inline-flex items-center px-5 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-semibold transition shadow-sm">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center px-5 py-2 rounded-lg bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                Update Admin
            </button>
        </div>
    </form>

</div>

@endsection
