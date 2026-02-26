@extends('layouts.admin')

@section('title', 'Exam Rules')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Exam Rules & Defaults</h1>
        <p class="text-gray-500 text-sm">Configure default settings and behavioral rules for exams created in your school.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.settings.exam_rules.update') }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- General Defaults -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">General Defaults</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Passing Percentage</label>
                        <div class="relative">
                            <input type="number" name="passing_percentage" value="{{ $rules['passing_percentage'] ?? 33 }}" min="0" max="100" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 pr-8">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Default score required to pass an exam.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Duration (Minutes)</label>
                        <input type="number" name="default_duration" value="{{ $rules['default_duration'] ?? 60 }}" min="1" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Standard time limit for new exams.</p>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Result Release Mode</label>
                        <select name="result_release_mode" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="immediate" {{ ($rules['result_release_mode'] ?? '') == 'immediate' ? 'selected' : '' }}>Immediate (After Submission)</option>
                            <option value="manual" {{ ($rules['result_release_mode'] ?? '') == 'manual' ? 'selected' : '' }}>Manual (Teacher Approval Required)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Controls when students can see their results.</p>
                    </div>
                </div>
            </div>

            <!-- Security & Proctoring -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Security & Proctoring</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="shuffle_questions" name="shuffle_questions" type="checkbox" {{ !empty($rules['shuffle_questions']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="shuffle_questions" class="font-medium text-gray-700">Shuffle Questions by Default</label>
                            <p class="text-gray-500">Randomize the order of questions for each student.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="disable_copy_paste" name="disable_copy_paste" type="checkbox" {{ !empty($rules['disable_copy_paste']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="disable_copy_paste" class="font-medium text-gray-700">Disable Copy/Paste</label>
                            <p class="text-gray-500">Prevent students from copying text or pasting content during the exam.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="full_screen_mode" name="full_screen_mode" type="checkbox" {{ !empty($rules['full_screen_mode']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="full_screen_mode" class="font-medium text-gray-700">Enforce Full Screen Mode</label>
                            <p class="text-gray-500">Require students to stay in full screen. Exiting may trigger a violation warning.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Default Instructions</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exam Instructions</label>
                    <textarea name="default_instructions" rows="5"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter default instructions that will appear on all new exams...">{{ $rules['default_instructions'] ?? "1. Read all questions carefully.\n2. Do not switch tabs.\n3. Ensure stable internet connection." }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">These instructions will be pre-filled when creating a new exam.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Save Rules
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
