@extends('layouts.admin')

@section('title', 'Add New Student')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-2 md:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header with Actions -->
        <div
            class="bg-white rounded shadow px-6 py-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full ring-2 ring-blue-400">
                    <!-- User icon from Heroicons -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 20v-2a4 4 0 00-3-3.87M6 20v-2a4 4 0 013-3.87m9-9.13A4 4 0 1115 7a4 4 0 014 4zm-9 0A4 4 0 114 7a4 4 0 014 4zm3 5a4 4 0 016 0v2a4 4 0 01-4 4H9a4 4 0 01-4-4v-2a4 4 0 016-0z" />
                    </svg>
                </span>
                <div class="flex flex-col">
                    <span class="text-lg font-semibold text-blue-900">Add New Student</span>
                    <span class="text-xs text-gray-500">Create a new student record</span>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.students.index') }}"
                    class="bg-gray-100 text-gray-800 px-4 py-2 rounded flex items-center justify-center gap-2 hover:bg-gray-200 font-semibold border border-gray-300 transition">
                    <!-- Arrow Left from Heroicons -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Students List
                </a>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded shadow p-8">
            @if(session('success'))
            <div class="mb-6 flex items-center p-4 rounded bg-green-100 text-green-800 relative">
                <span class="flex-1">{{ session('success') }}</span>
                <button type="button" class="ml-4 text-green-700 focus:outline-none"
                    onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-center p-4 rounded bg-red-100 text-red-800 relative">
                <span class="flex-1">{{ session('error') }}</span>
                <button type="button" class="ml-4 text-red-700 focus:outline-none"
                    onclick="this.parentElement.style.display='none'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @endif

            <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <section>
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('email') border-red-500 @enderror"
                                autocomplete="off">
                            @error('email')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700 mb-1">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('password') border-red-500 @enderror"
                                autocomplete="new-password">
                            @error('password')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                autocomplete="new-password">
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Personal Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                required
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('phone_number') border-red-500 @enderror"
                                autocomplete="off">
                            @error('phone_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="aadhar_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Aadhar Number
                            </label>
                            <input type="text" id="aadhar_number" name="aadhar_number"
                                value="{{ old('aadhar_number') }}"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('aadhar_number') border-red-500 @enderror"
                                autocomplete="off">
                            @error('aadhar_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="address" class="block font-medium text-sm text-gray-700 mb-1">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <textarea id="address" name="address" rows="2" required
                            class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <label for="photo" class="block font-medium text-sm text-gray-700 mb-1">
                            Student Photo
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="block w-full text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 shadow-sm @error('photo') border-red-500 @enderror">
                        @error('photo')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-700 mb-3">Academic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="admission_number" class="block font-medium text-sm text-gray-700 mb-1">
                                Admission Number
                            </label>

                            <input type="text" id="admission_number" name="admission_number"
                                value="{{ old('admission_number', $nextAdmissionNumber ?? '') }}"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none @error('admission_number') border-red-500 @enderror"
                                autocomplete="off" > 

                            @error('admission_number')
                            <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="grade" class="flex items-center gap-2 font-medium text-sm text-gray-700 mb-1">

                                <span>Grade / Class</span>

                                <button type="button" onclick="openClassPopup()"
                                    class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-semibold">
                                    + Add
                                </button>

                            </label>

                            <select id="grade" name="grade"
                                class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">

                                <option value="">Select Class</option>

                                @foreach($grades as $grade)
                                <option value="{{ $grade }}">
                                    {{ $grade }}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col md:flex-row justify-end gap-4 pt-8">
                    <button type="reset"
                        class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded hover:bg-gray-200 border border-gray-300 transition-colors">
                        Reset
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 shadow transition-colors flex items-center gap-2">
                        <!-- Save Icon from Heroicons -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Add Student
                    </button>
                </div>
            </form>
        </div>
        <div id="classPopup" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">

            <div class="bg-white w-full max-w-md rounded-xl shadow-xl p-6">

                <h2 class="text-lg font-bold text-gray-800 mb-4">
                    Add New Class
                </h2>

                <input type="text" id="newClassName" placeholder="Enter class name (Example: 10th)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">

                <p id="classError" class="text-red-500 text-sm mt-2"></p>

                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeClassPopup()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>

                    <button onclick="saveClass()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Class
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    function openClassPopup() {
    let popup = document.getElementById('classPopup');
    popup.classList.remove('hidden');
    popup.classList.add('flex');
}

function closeClassPopup() {
    let popup = document.getElementById('classPopup');
    popup.classList.add('hidden');
    popup.classList.remove('flex');
}

function saveClass() {
    let input = document.getElementById('newClassName');
    let newClass = input.value.trim();

    if (!newClass) {
        document.getElementById('classError').innerText = "Class name is required";
        return;
    }

    let select = document.getElementById('grade');

    let exists = [...select.options].some(opt => opt.value.toLowerCase() === newClass.toLowerCase());

    if (exists) {
        document.getElementById('classError').innerText = "Class already exists";
        return;
    }

    let option = document.createElement('option');
    option.value = newClass;
    option.text = newClass;
    option.selected = true;

    select.appendChild(option);

    input.value = '';
    document.getElementById('classError').innerText = '';

    closeClassPopup();
}
</script>

@endpush