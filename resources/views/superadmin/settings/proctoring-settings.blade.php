@extends('layouts.superadmin')

@section('title', 'Proctoring Settings')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Proctoring Settings</h1>
            <p class="text-sm text-gray-500">Control how automated monitoring behaves across the platform.</p>
        </div>
        <a href="{{ route('superadmin.settings.camera-rules') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-webcam mr-2"></i> Camera Rules
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.settings.proctoring-settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">Monitoring Rules</h2>
                    <div class="space-y-4">
                        @foreach ([
                            'enable_ai_proctoring' => ['Enable AI Proctoring', 'Turn on automated incident scanning for active exam sessions.'],
                            'face_presence_required' => ['Require Face Visibility', 'Flag sessions when the candidate face is missing from the camera feed.'],
                            'detect_multiple_faces' => ['Detect Multiple Faces', 'Create an incident when more than one face is visible in the exam frame.'],
                            'auto_warn_student' => ['Auto Warn Student', 'Issue an on-screen warning when a proctoring rule is violated.'],
                            'auto_pause_exam' => ['Auto Pause Exam', 'Temporarily pause the attempt after severe or repeated violations.'],
                            'incident_review_required' => ['Require Manual Review', 'Send flagged incidents into a review queue for follow-up.'],
                        ] as $field => [$label, $desc])
                            <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                                <input type="checkbox" name="{{ $field }}" {{ old($field, !empty($settings[$field])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span>
                                    <span class="block text-sm font-medium text-gray-800">{{ $label }}</span>
                                    <span class="block text-sm text-gray-500">{{ $desc }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">Thresholds</h2>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Warning Limit</label>
                            <input type="number" name="warning_limit" min="0" max="20" value="{{ old('warning_limit', $settings['warning_limit']) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('warning_limit')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Confidence Threshold</label>
                            <div class="relative">
                                <input type="number" name="confidence_threshold" min="50" max="100" value="{{ old('confidence_threshold', $settings['confidence_threshold']) }}" class="w-full rounded-lg border-gray-300 pr-8 focus:border-blue-500 focus:ring-blue-500">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-500">%</span>
                            </div>
                            @error('confidence_threshold')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-300">Profile</p>
                    <div class="mt-5 space-y-4">
                        <div>
                            <p class="text-xs text-slate-400">Automation</p>
                            <p class="text-2xl font-bold">{{ !empty($settings['enable_ai_proctoring']) ? 'Enabled' : 'Disabled' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Warning Limit</p>
                            <p class="text-2xl font-bold">{{ old('warning_limit', $settings['warning_limit']) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Confidence Threshold</p>
                            <p class="text-2xl font-bold">{{ old('confidence_threshold', $settings['confidence_threshold']) }}%</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Save Changes</h2>
                    <p class="mb-5 text-sm text-gray-500">These defaults guide how monitored exams behave before school-level overrides are applied.</p>
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Save Proctoring Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
