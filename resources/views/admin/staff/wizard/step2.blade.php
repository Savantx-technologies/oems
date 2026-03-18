@extends('layouts.admin')

@section('title', 'Add New Staff - Step 2')

@section('content')
<div class="flex justify-center w-full bg-gray-50 py-10 min-h-screen">
    <div class="w-full max-w-3xl">
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-8 py-6 border-b border-gray-100 flex flex-col gap-1">
                <h2 class="text-2xl font-bold text-blue-700">Add New Staff</h2>
                <p class="text-gray-500 text-sm">Step 2: Professional Details</p>
            </div>
            <div class="px-8 py-8">
                @include('admin.staff.wizard.partials.stepper', ['currentStep' => 2])

                <form method="POST" action="{{ route('admin.staff.create.postStep2') }}" class="mt-6 space-y-6">
                    @csrf
                    <div>
                        <div class="text-lg font-semibold text-blue-600 mb-6">
                            For {{ ucfirst(str_replace('_', ' ', $step1['staff_type'])) }}
                        </div>
                        @if($step1['staff_type'] === 'teacher')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="qualification" class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                                <input type="text" name="qualification" id="qualification"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                    value="{{ old('qualification', $data['qualification'] ?? '') }}">
                            </div>
                            <div>
                                <label for="subject_specialization" class="block text-sm font-medium text-gray-700 mb-2">Subject Specialization</label>
                                <input type="text" name="subject_specialization" id="subject_specialization"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                    value="{{ old('subject_specialization', $data['subject_specialization'] ?? '') }}">
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">Experience (Years)</label>
                            <input type="number" name="experience_years" id="experience_years"
                                class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                value="{{ old('experience_years', $data['experience_years'] ?? '') }}" min="0">
                        </div>
                        @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <input type="text" name="department" id="department"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                    value="{{ old('department', $data['department'] ?? '') }}">
                            </div>
                            <div>
                                <label for="designation" class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                <input type="text" name="designation" id="designation"
                                    class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                    value="{{ old('designation', $data['designation'] ?? '') }}">
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.staff.create.step1') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-700 border border-gray-300 rounded-md px-5 py-2 bg-white hover:bg-gray-100 transition">
                            <!-- Heroicon: Arrow Left -->
                            <svg class="w-5 h-5 mr-2 -ml-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                            class="inline-flex items-center text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-md px-6 py-2 shadow transition">
                            Next: Role & Access
                            <!-- Heroicon: Arrow Right -->
                            <svg class="w-5 h-5 ml-2 -mr-1 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection