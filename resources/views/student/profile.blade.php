@extends('layouts.student')

@section('title', 'My Profile')

@section('content')
<div class="mx-auto max-w-6xl" x-data="{ passwordModalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
    <!-- Header -->
    <div class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-900 to-blue-700 px-5 py-6 text-white shadow-xl sm:px-6">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Profile Center</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">My Profile</h1>
        <p class="mt-2 max-w-2xl text-sm text-blue-100/85">Manage your account information, academic details, and password from a cleaner mobile-friendly profile page.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Identity Card -->
        <div class="lg:col-span-1">
            <div class="relative overflow-hidden rounded-3xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative z-10 -mt-12 mb-4">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile Photo" class="w-28 h-28 rounded-full border-4 border-white shadow-md mx-auto object-cover bg-white">
                    @else
                        <div class="w-28 h-28 rounded-full border-4 border-white shadow-md mx-auto bg-indigo-100 flex items-center justify-center text-indigo-600 text-3xl font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-500 mb-4 flex items-center justify-center gap-1">
                    {{ auth()->user()->email }}
                    @if(auth()->user()->email_verified_at)
                        <i class="bi bi-patch-check-fill text-blue-500" title="Verified Account"></i>
                    @endif
                </p>

                <div class="mb-6 flex flex-wrap justify-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Student
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ auth()->user()->status === 'active' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                        {{ ucfirst(auth()->user()->status) }}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-6 text-left">
                    <div class="mb-3">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">School</label>
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="bi bi-building text-gray-400"></i>
                            {{ auth()->user()->school?->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</label>
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="bi bi-calendar3 text-gray-400"></i>
                            {{ auth()->user()->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Academic Info -->
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-mortarboard text-indigo-600"></i>
                    Academic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Admission Number</label>
                        <div class="text-gray-900 font-semibold">{{ auth()->user()->admission_number ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Class / Grade</label>
                        <div class="text-gray-900 font-semibold">{{ auth()->user()->grade ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-indigo-600"></i>
                    Personal Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                        <div class="text-gray-900">{{ auth()->user()->phone_number ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Aadhar Number</label>
                        <div class="text-gray-900">{{ auth()->user()->aadhar_number ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                        <div class="text-gray-900">{{ auth()->user()->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-shield-lock text-indigo-600"></i>
                            Security
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update your password to keep your account secure.</p>
                    </div>
                    <button @click="passwordModalOpen = true" class="inline-flex items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-2.5 text-sm font-medium text-indigo-600 transition hover:bg-indigo-100">
                        Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div x-show="passwordModalOpen" 
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="passwordModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="passwordModalOpen = false"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="passwordModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                <form action="{{ route('student.password.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Change Password</h3>
                        <p class="text-sm text-gray-500 mt-1">Ensure your account is using a long, random password to stay secure.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button" @click="passwordModalOpen = false" class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</button>
                        <button type="submit" class="rounded-xl border border-transparent bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
