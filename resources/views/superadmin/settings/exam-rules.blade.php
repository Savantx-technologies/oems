@extends('layouts.superadmin')

@section('title', 'Exam Rules Engine')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Exam Rules Engine</h1>
            <p class="text-sm text-gray-500">Define the default exam behavior applied to newly onboarded schools and platform-wide baseline policies.</p>
        </div>
        <a href="{{ route('superadmin.settings.system') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-gear mr-2"></i> General Settings
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Schools</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stats['total_schools'] }}</p>
            <p class="mt-2 text-sm text-slate-500">Institutions currently managed by the platform.</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Using Platform Defaults</p>
            <p class="mt-3 text-3xl font-bold text-blue-900">{{ $stats['schools_using_defaults'] }}</p>
            <p class="mt-2 text-sm text-blue-700">These schools inherit the rules configured on this page.</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Custom School Rules</p>
            <p class="mt-3 text-3xl font-bold text-amber-900">{{ $stats['schools_with_custom_rules'] }}</p>
            <p class="mt-2 text-sm text-amber-700">Schools with their own overridden exam rule settings.</p>
        </div>
    </div>

    <form action="{{ route('superadmin.settings.exam-rules.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid gap-8 xl:grid-cols-[1.25fr_0.75fr]">
            <div class="space-y-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">Core Defaults</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Passing Percentage</label>
                            <div class="relative">
                                <input type="number" name="passing_percentage" value="{{ old('passing_percentage', $rules['passing_percentage'] ?? 33) }}" min="0" max="100" required class="w-full rounded-lg border-gray-300 pr-8 focus:border-blue-500 focus:ring-blue-500">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-500">%</span>
                            </div>
                            @error('passing_percentage')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Default Duration</label>
                            <div class="relative">
                                <input type="number" name="default_duration" value="{{ old('default_duration', $rules['default_duration'] ?? 60) }}" min="1" max="1440" required class="w-full rounded-lg border-gray-300 pr-16 focus:border-blue-500 focus:ring-blue-500">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-500">mins</span>
                            </div>
                            @error('default_duration')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Result Release Mode</label>
                            <select name="result_release_mode" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="immediate" {{ old('result_release_mode', $rules['result_release_mode'] ?? 'immediate') === 'immediate' ? 'selected' : '' }}>Immediate after submission</option>
                                <option value="manual" {{ old('result_release_mode', $rules['result_release_mode'] ?? '') === 'manual' ? 'selected' : '' }}>Manual approval required</option>
                            </select>
                            @error('result_release_mode')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">Security and Proctoring</h2>
                    <div class="space-y-4">
                        <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                            <input type="checkbox" name="shuffle_questions" {{ old('shuffle_questions', !empty($rules['shuffle_questions'])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-800">Shuffle Questions by Default</span>
                                <span class="block text-sm text-gray-500">Randomize question order to reduce answer sharing patterns.</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                            <input type="checkbox" name="disable_copy_paste" {{ old('disable_copy_paste', !empty($rules['disable_copy_paste'])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-800">Disable Copy and Paste</span>
                                <span class="block text-sm text-gray-500">Block common copy actions while students are inside an exam window.</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                            <input type="checkbox" name="full_screen_mode" {{ old('full_screen_mode', !empty($rules['full_screen_mode'])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-800">Enforce Full Screen Mode</span>
                                <span class="block text-sm text-gray-500">Treat leaving full screen as a monitored event for anti-cheat handling.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">Default Instructions</h2>
                    <textarea name="default_instructions" rows="6" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Add the instructions that should appear on new exams...">{{ old('default_instructions', $rules['default_instructions'] ?? " Read all questions carefully.\n Do not leave the exam window.\n Ensure your camera and internet connection remain stable.") }}</textarea>
                    @error('default_instructions')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-300">Engine Summary</p>
                    <div class="mt-5 space-y-4">
                        <div>
                            <p class="text-xs text-slate-400">Passing Threshold</p>
                            <p class="text-2xl font-bold">{{ old('passing_percentage', $rules['passing_percentage'] ?? 33) }}%</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Standard Duration</p>
                            <p class="text-2xl font-bold">{{ old('default_duration', $rules['default_duration'] ?? 60) }} min</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Result Flow</p>
                            <p class="text-lg font-semibold capitalize">{{ str_replace('_', ' ', old('result_release_mode', $rules['result_release_mode'] ?? 'immediate')) }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Applies To</h2>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-check-circle-fill mt-0.5 text-green-600"></i>
                            New schools that have no school-specific exam rule override yet.
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-check-circle-fill mt-0.5 text-green-600"></i>
                            Admin teams using inherited defaults when creating exams.
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-info-circle-fill mt-0.5 text-blue-600"></i>
                            Existing school-level custom rules remain unchanged.
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Save Changes</h2>
                    <p class="mb-5 text-sm text-gray-500">Publishing here updates the platform baseline used across new deployments and inherited exam setups.</p>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Save Exam Rules Engine
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
