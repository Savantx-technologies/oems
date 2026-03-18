@extends('layouts.superadmin')

@section('title', 'Notification Templates')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notification Templates</h1>
            <p class="text-sm text-gray-500">Manage the default message templates used by the superadmin panel.</p>
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

    <form action="{{ route('superadmin.settings.notification-templates.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-semibold text-gray-900">Delivery Channels</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                    <input type="checkbox" name="email_enabled" {{ old('email_enabled', !empty($settings['email_enabled'])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">Enable Email Notifications</span>
                        <span class="block text-sm text-gray-500">Use these templates for outgoing email alerts.</span>
                    </span>
                </label>
                <label class="flex items-start gap-4 rounded-xl border border-gray-100 px-4 py-4 hover:border-blue-200 hover:bg-blue-50/40">
                    <input type="checkbox" name="dashboard_enabled" {{ old('dashboard_enabled', !empty($settings['dashboard_enabled'])) ? 'checked' : '' }} class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">Enable Dashboard Notifications</span>
                        <span class="block text-sm text-gray-500">Reuse these messages for in-app alerts inside the admin experience.</span>
                    </span>
                </label>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-3">
            @foreach ([
                'exam_alert' => 'Exam Alert',
                'violation_alert' => 'Violation Alert',
                'system_alert' => 'System Alert',
            ] as $key => $label)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-5 text-lg font-semibold text-gray-900">{{ $label }}</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="{{ $key }}_subject" value="{{ old($key . '_subject', $settings[$key . '_subject']) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error($key . '_subject')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Message Body</label>
                            <textarea name="{{ $key }}_body" rows="7" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old($key . '_body', $settings[$key . '_body']) }}</textarea>
                            @error($key . '_body')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                Save Notification Templates
            </button>
        </div>
    </form>
</div>
@endsection
