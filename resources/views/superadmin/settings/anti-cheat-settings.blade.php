@extends('layouts.superadmin')

@section('title', 'Anti-Cheat Settings')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Anti-Cheat Settings</h1>
            <p class="text-sm text-gray-500">Configure the platform-wide anti-cheat defaults used during supervised exams.</p>
        </div>
        <a href="{{ route('superadmin.settings.notification-templates') }}" class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            <i class="bi bi-bell mr-2"></i> Notification Templates
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.settings.anti-cheat-settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-900">Restriction Rules</h2>
            <div class="space-y-4">
                @foreach ([
                    'enforce_full_screen' => ['Enforce Full Screen', 'Treat exiting full screen mode as a monitored event.'],
                    'disable_copy_paste' => ['Disable Copy and Paste', 'Block common clipboard shortcuts during exams.'],
                    'disable_right_click' => ['Disable Right Click', 'Reduce common browser shortcuts used for copying or inspecting content.'],
                    'detect_tab_switches' => ['Detect Tab Switching', 'Log focus changes when a student leaves the exam window.'],
                    'detect_multiple_displays' => ['Detect Multiple Displays', 'Flag attempts running across more than one monitor where supported.'],
                    'block_developer_tools' => ['Block Developer Tools', 'Apply available browser-level deterrents against inspection tooling.'],
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
            <h2 class="mb-5 text-lg font-semibold text-gray-900">Tolerance</h2>
            <div class="max-w-md">
                <label class="mb-1 block text-sm font-medium text-gray-700">Maximum Tab Switches</label>
                <input type="number" name="max_tab_switches" min="0" max="20" value="{{ old('max_tab_switches', $settings['max_tab_switches']) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('max_tab_switches')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Save Anti-Cheat Settings
            </button>
        </div>
    </form>
</div>
@endsection
