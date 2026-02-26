@extends('layouts.admin')

@section('title', 'School Profile')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 lg:px-0">
    <div class="mb-10 flex flex-col sm:flex-row items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-gray-900 mb-1 tracking-tight">School Profile</h1>
            <p class="text-gray-500 text-base">Manage your school's key information and branding below.</p>
        </div>
        @if($school->logo)
        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="h-16 w-16 rounded border border-gray-200 shadow-sm object-contain bg-white p-1 mt-6 sm:mt-0">
        @endif
    </div>

    <div class="bg-white/95 rounded-3xl shadow-2xl border border-gray-100 ring-1 ring-gray-50 overflow-hidden mb-10 transition hover:ring-indigo-100">
        <form action="{{ route('admin.settings.school.update') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-12">
            @csrf
            @method('PUT')

            <!-- Logo Upload Section -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-10 py-4 border-b border-gray-100">
                <div class="flex flex-col items-center md:items-start">
                    <label class="block font-medium text-gray-700 mb-2 text-base">School Logo</label>
                    <div class="relative w-36 h-36 rounded-full border-4 border-dashed border-gray-300 bg-gray-50 overflow-hidden group shadow-inner cursor-pointer hover:border-indigo-400 hover:shadow-md transition-all duration-200">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="w-full h-full object-contain">
                        @else
                            <i class="bi bi-image text-5xl text-gray-300 absolute inset-0 flex items-center justify-center h-full w-full"></i>
                        @endif
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                            <span class="flex flex-col items-center text-white drop-shadow">
                                <i class="bi bi-pencil-square text-2xl mb-1"></i>
                                <span class="text-sm font-medium">Change</span>
                            </span>
                        </div>
                        <input 
                            type="file" 
                            name="logo" 
                            class="absolute inset-0 opacity-0 cursor-pointer z-20"
                            accept="image/*"
                            title="Upload new logo"
                        >
                    </div>
                    <p class="text-xs text-gray-400 mt-3 text-center md:text-left">Recommended: 200x200px PNG/JPG<br>Max size: 2MB</p>
                </div>
                <div class="flex-1 space-y-2 mt-8 md:mt-0">
                    <h2 class="text-xl font-bold text-gray-900">Brand Identity</h2>
                    <p class="text-sm text-gray-500">Your school's logo helps identify you across the platform. Click the image to upload a new one.</p>
                </div>
            </div>

            <!-- Basic Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">School Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $school->name) }}" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-900"
                            placeholder="Enter school name">
                    </div>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $school->email) }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                placeholder="Enter email address">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                name="contact_number"
                                value="{{ old('contact_number', $school->contact_number) }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                placeholder="Enter 10-digit phone number"
                                maxlength="10"
                                minlength="10"
                                pattern="[0-9]{10}"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">Must be a 10-digit number.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 md:pl-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">School Code</label>
                        <input type="text" value="{{ $school->code }}" disabled
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed font-mono tracking-wider"
                        >
                        <p class="text-xs text-gray-500 mt-1">This is your school's unique ID. (Uneditable)</p>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Location Details</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Address</label>
                        <textarea name="address" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                            placeholder="Enter full address">{{ old('address', $school->address) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $school->city) }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                placeholder="Enter city">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">State</label>
                            <input type="text" name="state" value="{{ old('state', $school->state) }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                placeholder="Enter state">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Zip Code</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $school->pincode) }}"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                placeholder="Zip code">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-10 border-t border-gray-100 flex flex-col sm:flex-row items-center sm:justify-end gap-4">
                <button type="reset" class="px-8 py-3 border border-gray-300 text-gray-600 bg-gray-50 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Reset
                </button>
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white text-base font-bold rounded-lg hover:bg-indigo-700 transition-shadow shadow-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
