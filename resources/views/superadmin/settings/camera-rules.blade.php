@extends('layouts.superadmin')

@section('title', 'Camera Rules')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Camera Rules</h1>
            <p class="text-sm text-gray-500">Set default camera access, quality, and capture policies for exams.</p>
        </div>
        <a href="{{ route('superadmin.settings.anti-cheat-settings') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-shield-lock mr-2"></i> Anti-Cheat Settings
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.settings.camera-rules.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-900">Access Controls</h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ([
                    'require_camera' => ['Require Camera Access', 'Students must grant webcam access before joining an exam.'],
                    'allow_mobile_camera' => ['Allow Mobile Camera Fallback', 'Permit alternate mobile camera connections where supported.'],
                    'capture_snapshots' => ['Capture Snapshots', 'Take periodic still images during the exam session.'],
                    'block_virtual_cameras' => ['Block Virtual Cameras', 'Reject software-based virtual camera devices where possible.'],
                    'camera_permission_retry' => ['Allow Retry on Camera Denial', 'Let students retry camera permissions before failing the system check.'],
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

        <div class="grid gap-8 md:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-semibold text-gray-900">Image Quality</h2>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Minimum Resolution</label>
                    <select name="minimum_resolution" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="480p" {{ old('minimum_resolution', $settings['minimum_resolution']) === '480p' ? 'selected' : '' }}>480p</option>
                        <option value="720p" {{ old('minimum_resolution', $settings['minimum_resolution']) === '720p' ? 'selected' : '' }}>720p</option>
                        <option value="1080p" {{ old('minimum_resolution', $settings['minimum_resolution']) === '1080p' ? 'selected' : '' }}>1080p</option>
                    </select>
                    @error('minimum_resolution')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-5 text-lg font-semibold text-gray-900">Capture Timing</h2>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Snapshot Interval</label>
                    <div class="relative">
                        <input type="number" name="snapshot_interval_seconds" min="5" max="300" value="{{ old('snapshot_interval_seconds', $settings['snapshot_interval_seconds']) }}" class="w-full rounded-lg border-gray-300 pr-16 focus:border-blue-500 focus:ring-blue-500">
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-gray-500">sec</span>
                    </div>
                    @error('snapshot_interval_seconds')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Save Camera Rules
            </button>
        </div>
    </form>
</div>
@endsection
