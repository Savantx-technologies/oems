@extends('layouts.superadmin')

@section('title', 'Edit School')

@section('content')

<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <span class="text-blue-600">Edit School</span>
        </h2>
        <p class="text-gray-500">Update school details</p>
    </div>

    <form method="POST" action="{{ route('superadmin.schools.update', $school->id) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- School Identity -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-lg text-gray-700 mb-6 flex items-center gap-2">🏫 School Identity</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">School Name *</label>
                    <input type="text" name="name" value="{{ old('name', $school->name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition" />
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">School Code *</label>
                    <input type="text" name="code" value="{{ old('code', $school->code) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition"/>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Account Status</label>
                    <select name="is_active" class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition">
                        <option value="1" {{ old('is_active', $school->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $school->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">School Type</label>
                    <select name="type" class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition">
                        <option value="Government" {{ old('type', $school->type) == 'Government' ? 'selected' : '' }}>Government</option>
                        <option value="Private" {{ old('type', $school->type) == 'Private' ? 'selected' : '' }}>Private</option>
                        <option value="Semi-Government" {{ old('type', $school->type) == 'Semi-Government' ? 'selected' : '' }}>Semi-Government</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Board / Authority</label>
                    <select name="board" class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition">
                        <option value="CBSE" {{ old('board', $school->board) == 'CBSE' ? 'selected' : '' }}>CBSE</option>
                        <option value="ICSE" {{ old('board', $school->board) == 'ICSE' ? 'selected' : '' }}>ICSE</option>
                        <option value="State Board" {{ old('board', $school->board) == 'State Board' ? 'selected' : '' }}>State Board</option>
                        <option value="University" {{ old('board', $school->board) == 'University' ? 'selected' : '' }}>University</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Registration Number</label>
                    <input type="text" name="registration_no" value="{{ old('registration_no', $school->registration_no) }}"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition"/>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Year of Establishment</label>
                    <input type="number" name="established_year" value="{{ old('established_year', $school->established_year) }}"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition"/>
                </div>

                <div class="md:col-span-3 space-y-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700">School Logo</label>
                    <input type="file" id="logo" name="logo"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm py-2 px-3 transition bg-white"/>
                    @if ($school->logo)
                        <div class="mt-3">
                            <span class="text-xs text-gray-500">Current Logo:</span>
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="h-20 rounded-md shadow border border-gray-200"/>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Address -->
        <section class="bg-white rounded-xl shadow p-6">
            <h3 class="font-semibold text-lg text-gray-700 mb-6 flex items-center gap-2">📍 Address & Location</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Full Address</label>
                    <textarea name="address" rows="2"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm px-3 py-2 transition resize-none"
                    >{{ old('address', $school->address) }}</textarea>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">City</label>
                    <input name="city" value="{{ old('city', $school->city) }}"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm px-3 py-2 transition"/>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">State</label>
                    <input name="state" value="{{ old('state', $school->state) }}"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm px-3 py-2 transition"/>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">PIN Code</label>
                    <input name="pincode" type="text" value="{{ old('pincode', $school->pincode) }}"
                        class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm px-3 py-2 transition"/>
                </div>
            </div>
        </section>

        <!-- Actions -->
        <div class="flex justify-end gap-4 pt-6">
            <a href="{{ route('superadmin.schools.index') }}"
                class="px-5 py-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium shadow-sm transition focus:outline-none focus:ring-2 focus:ring-blue-200">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2 rounded-md bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                Save & Next
            </button>
        </div>
    </form>
</div>

@endsection