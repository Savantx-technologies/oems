@extends('layouts.admin')

@section('title', 'Add New Staff - Step 1')

@section('content')
<div class="flex justify-center w-full bg-gray-50 py-10 min-h-screen">
    <div class="w-full max-w-3xl">
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-8 py-6 border-b border-gray-100 flex flex-col gap-1">
                <h2 class="text-2xl font-bold text-blue-700">Add New Staff</h2>
                <p class="text-gray-500 text-sm">Step 1: Basic Information</p>
            </div>
            <div class="px-8 py-8">
                @include('admin.staff.wizard.partials.stepper', ['currentStep' => 1])

                <form method="POST" action="{{ route('admin.staff.create.postStep1') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 mb-1 font-medium">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name"
                                class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2"
                                value="{{ old('name', $data['name'] ?? '') }}" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 mb-1 font-medium">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email"
                                class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2"
                                value="{{ old('email', $data['email'] ?? '') }}" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="mobile" class="block text-gray-700 mb-1 font-medium">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="text" name="mobile" id="mobile"
                                class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2"
                                value="{{ old('mobile', $data['mobile'] ?? '') }}" required>
                        </div>
                        <div>
                            <label for="staff_type" class="block text-gray-700 mb-1 font-medium">Staff Type <span class="text-red-500">*</span></label>
                            <select name="staff_type" id="staff_type"
                                class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2 bg-white"
                                required>
                                <option value="">-- Select Type --</option>
                                @foreach(['teacher','admin_staff','librarian','lab_assistant'] as $type)
                                <option value="{{ $type }}"
                                    {{ ($data['staff_type'] ?? '') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_',' ', $type)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Aadhaar Section -->
                    <div class="pt-6 mt-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Aadhaar Details (Optional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="aadhaar_number" class="block text-gray-700 mb-1 font-medium">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" id="aadhaar_number"
                                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2"
                                    value="{{ old('aadhaar_number', $data['aadhaar_number'] ?? '') }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="aadhaar_dob" class="block text-gray-700 mb-1 font-medium">Date of Birth (Aadhaar)</label>
                                <input type="date" name="aadhaar_dob" id="aadhaar_dob"
                                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2"
                                    value="{{ old('aadhaar_dob', $data['aadhaar_dob'] ?? '') }}">
                            </div>
                            <div>
                                <label for="aadhaar_gender" class="block text-gray-700 mb-1 font-medium">Gender (Aadhaar)</label>
                                <select name="aadhaar_gender" id="aadhaar_gender"
                                    class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm p-2 bg-white">
                                    <option value="">-- Select Gender --</option>
                                    @foreach(['male', 'female', 'other'] as $gender)
                                    <option value="{{ $gender }}" {{ (old('aadhaar_gender', $data['aadhaar_gender'] ?? '') == $gender) ? 'selected' : '' }}>{{ ucfirst($gender) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="photo" class="block text-gray-700 mb-1 font-medium">Profile Photo</label>
                        <input type="file" name="photo" id="photo"
                            class="block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 shadow-sm text-gray-700 p-2">
                        @if(!empty($data['photo']))
                        <img src="{{ asset('storage/'.$data['photo']) }}" class="mt-3 h-16 w-16 object-cover rounded border border-gray-200 p-1 shadow-sm" alt="Profile photo preview">
                        @endif
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2 rounded-md bg-blue-600 hover:bg-blue-700 transition text-white font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            Next: Professional Details
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection