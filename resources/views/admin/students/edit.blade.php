@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
<div class="min-h-screen bg-gray-50 px-2 py-4 sm:px-4 md:px-8 md:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header with Actions -->
        <div class="mb-6 flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white px-4 py-5 shadow-sm sm:px-6 md:mb-8 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full ring-2 ring-blue-400">
                    <!-- Pencil Icon from Heroicons -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2 2 0 00-2.828-2.828l-8 8v3z" />
                    </svg>
                </span>
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-blue-900">Edit Student</span>
                    <span class="text-xs text-gray-500">Update student details</span>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}" class="flex items-center justify-center gap-2 rounded-2xl border border-gray-300 bg-gray-100 px-4 py-2.5 font-semibold text-gray-800 transition hover:bg-gray-200">
                    <!-- Left arrow icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Students List
                </a>
            </div>
        </div>

        <!-- Main Form -->
        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6 lg:p-8">
            @if(session('success'))
            <div class="mb-6 flex items-center p-4 rounded bg-green-100 text-green-800 relative">
                <span class="flex-1">{{ session('success') }}</span>
                <button type="button" class="ml-4 text-green-700 focus:outline-none" onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-center p-4 rounded bg-red-100 text-red-800 relative">
                <span class="flex-1">{{ session('error') }}</span>
                <button type="button" class="ml-4 text-red-700 focus:outline-none" onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @endif

            <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <section>
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('name') border-red-500 @enderror"
                                autocomplete="off">
                            @error('name')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('email') border-red-500 @enderror"
                                autocomplete="off">
                            @error('email')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700 mb-1">
                                Password <span class="text-xs text-gray-400">(leave blank to keep current password)</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('password') border-red-500 @enderror"
                                autocomplete="new-password">
                            @error('password')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Student Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block font-medium text-sm text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('status') border-red-500 @enderror">
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="aadhar_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Aadhar Number
                            </label>
                            <input type="text" id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number', $student->aadhar_number) }}"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('aadhar_number') border-red-500 @enderror">
                            @error('aadhar_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="admission_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Admission Number
                            </label>
                            <input type="text" id="admission_number" name="admission_number" value="{{ old('admission_number', $student->admission_number) }}"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('admission_number') border-red-500 @enderror">
                            @error('admission_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-2 mt-6">
                        <label for="address" class="block font-medium text-sm text-gray-700 mb-1">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="2" required
                            class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="grade" class="block font-medium text-sm text-gray-700 mb-1">
                                Grade / Class
                            </label>
                            <input id="grade" name="grade" type="text" value="{{ old('grade', $student->grade) }}" placeholder="e.g. 10th"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('grade') border-red-500 @enderror">
                            @error('grade')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="photo" class="block font-medium text-sm text-gray-700 mb-1">
                            Student Photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="w-full px-3 py-2 rounded border border-gray-300 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('photo') border-red-500 @enderror">
                        @if($student->photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Current Photo" class="h-20 w-20 object-cover rounded border border-gray-300">
                        </div>
                        @endif
                        @error('photo')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <div class="flex flex-col justify-end gap-3 pt-5 sm:flex-row">
                    <a href="{{ route('admin.students.index') }}"
                        class="rounded-2xl border border-gray-300 bg-gray-100 px-5 py-2.5 font-medium text-gray-700 transition hover:bg-gray-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-2.5 font-semibold text-white shadow transition-colors hover:bg-blue-700">
                        <!-- Save Icon from Heroicons -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
