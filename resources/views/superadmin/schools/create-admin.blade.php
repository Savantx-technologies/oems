@extends('layouts.superadmin')

@section('title', 'Create School Admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Step Indicator -->
    <div class="mb-6">
        <span class="inline-block bg-blue-600 text-white text-xs font-semibold px-3.5 py-1 rounded-full">Step 2 of 2</span>
        <h4 class="mt-4 text-xl font-semibold text-gray-800">Create School Admin</h4>
        <p class="text-gray-500 mt-2">
            Create the primary administrator for this school.
            Sub Admins, Teachers and Staff can be added later by this Admin.
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('superadmin.schools.admin-store', $school->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Personal Details -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="border-b px-6 py-4 text-gray-800 font-semibold flex items-center gap-2">
                <span>👤</span> <span>Personal Details</span>
            </div>
            <div class="p-6 grid md:grid-cols-4 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Full Name (as per Aadhaar) *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter full name" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 px-3 py-2 bg-gray-50">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Date of Birth *</label>
                    <input type="date" name="dob" value="{{ old('dob') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Father / Guardian Name</label>
                    <input type="text" name="father_name" value="{{ old('father_name') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Mobile Number *</label>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}" placeholder="10 digit mobile"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email ID *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2 bg-gray-50 text-gray-900">
                </div>

                <!-- Spacer for grid alignment -->
                <div class="hidden md:block"></div>
                <div class="hidden md:block"></div>
            </div>
        </div>

        <!-- Aadhaar Details -->
        <div class="bg-white shadow rounded-lg mb-8 border-l-4 border-yellow-400">
            <div class="border-b px-6 py-4 text-yellow-700 font-semibold flex items-center gap-2">
                <span>🪪</span> <span>Aadhaar Details (Government Required)</span>
            </div>
            <div class="p-6 grid md:grid-cols-4 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar Number *</label>
                    <input type="text" placeholder="XXXX-XXXX-1234" disabled
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-100 px-3 py-2 text-gray-400 cursor-not-allowed">
                    <small class="text-gray-400">Aadhaar input will be masked and encrypted</small>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar Name *</label>
                    <input type="text"
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar DOB *</label>
                    <input type="date"
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar Gender</label>
                    <select class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>

                <!-- <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar Linked Mobile</label>
                    <select class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div> -->

                <!-- <div class="flex items-end">
                    <button type="button" disabled
                        class="w-full bg-white border border-blue-400 text-blue-500 rounded-md px-4 py-2 font-semibold opacity-60 cursor-not-allowed transition">
                        Send OTP (Verification Placeholder)
                    </button>
                </div> -->

                <!-- <div class="flex items-end">
                    <span class="inline-block w-full text-yellow-700 bg-yellow-100 text-center py-2 rounded font-semibold">
                        Verification Status: Pending
                    </span>
                </div> -->
            </div>
        </div>

        <!-- Identity Proof -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="border-b px-6 py-4 text-gray-800 font-semibold flex items-center gap-2">
                <span>🧾</span> <span>Identity & Address Proof</span>
            </div>
            <div class="p-6 grid md:grid-cols-3 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Aadhaar Card Upload</label>
                    <input type="file"
                        class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900 file:rounded file:border-0 file:bg-blue-50 file:text-blue-500 file:px-3 file:py-2">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">PAN Number (Optional)</label>
                    <input type="text"
                        class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">PAN Card Upload</label>
                    <input type="file"
                        class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900 file:rounded file:border-0 file:bg-blue-50 file:text-blue-500 file:px-3 file:py-2">
                </div>

            </div>
        </div>

        <!-- Role Details -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="border-b px-6 py-4 text-gray-800 font-semibold flex items-center gap-2">
                <span>🏢</span> <span>Admin Role Details</span>
            </div>
            <div class="p-6 grid md:grid-cols-3 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Role</label>
                    <input type="text" value="School Admin" readonly disabled
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-100 px-3 py-2 text-gray-400 cursor-not-allowed">
                    <input type="hidden" name="role" value="school_admin">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Assigned School</label>
                    <input type="text" value="{{ $school->name }}" readonly disabled
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-100 px-3 py-2 text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Designation</label>
                    <select class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option>Principal</option>
                        <option>Director</option>
                        <option>Exam Controller</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Account Access -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="border-b px-6 py-4 text-gray-800 font-semibold flex items-center gap-2">
                <span>🔐</span> <span>Account Access Setup</span>
            </div>
            <div class="p-6 grid md:grid-cols-4 sm:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Username</label>
                    <input type="text" value="Auto-generated from Email" readonly disabled
                        class="block w-full rounded-md border-gray-200 shadow-sm bg-gray-100 px-3 py-2 text-gray-400 cursor-not-allowed">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" name="password" required
                        class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Login Method</label>
                    <select name="login_method" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option value="password">Password</option>
                        <option value="otp">OTP Only</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Account Status</label>
                    <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Send Credentials Via</label>
                    <select class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-900">
                        <option>Email</option>
                        <option>SMS</option>
                        <option>Email & SMS</option>
                    </select>
                </div>

                <div class="flex items-center mt-3 col-span-2">
                    <input type="checkbox" checked disabled
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-not-allowed mr-2">
                    <label class="text-gray-700 text-sm font-medium">Force Password Reset on First Login</label>
                </div>

                <div class="flex items-center mt-3 col-span-2">
                    <input type="checkbox" checked disabled
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-not-allowed mr-2">
                    <label class="text-gray-700 text-sm font-medium">Two-Factor Authentication (Mandatory)</label>
                </div>
            </div>
        </div>

        <!-- Legal Consent -->
        <div class="bg-white shadow rounded-lg mb-8 border-l-4 border-red-500">
            <div class="border-b px-6 py-4 text-red-700 font-semibold flex items-center gap-2">
                <span>⚠</span> <span>Legal Consent & Declaration</span>
            </div>
            <div class="p-6">

                <div class="flex items-center mb-3">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded mr-2">
                    <label class="text-gray-700 text-sm">I confirm that the above information is accurate</label>
                </div>
                <div class="flex items-center mb-3">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded mr-2">
                    <label class="text-gray-700 text-sm">I consent to share Aadhaar details for government verification</label>
                </div>
                <div class="flex items-center mb-3">
                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded mr-2">
                    <label class="text-gray-700 text-sm">I agree to the platform Terms &amp; Privacy Policy</label>
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('superadmin.schools.index') }}"
               class="inline-flex items-center px-5 py-2 bg-white border border-gray-300 text-gray-600 rounded-md shadow-sm hover:bg-gray-50 font-semibold transition">
                Cancel
            </a>
            <button type="button"
                    class="inline-flex items-center px-5 py-2 bg-yellow-400 text-yellow-900 border border-yellow-300 rounded-md shadow-sm hover:bg-yellow-500 font-semibold transition"
                    disabled>
                Save &amp; Verify Later
            </button>
            <button type="submit"
                    class="inline-flex items-center px-5 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 font-semibold transition">
                Create School Admin &amp; Complete Setup
            </button>
        </div>

    </form>

</div>

@endsection
