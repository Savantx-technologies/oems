@extends('layouts.superadmin')

@section('title', 'System Configuration')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">System Configuration</h1>
        <p class="text-gray-500 text-sm">Manage global settings for the application.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Error</p>
            <p>{!! session('error') !!}</p>
        </div>
    @endif

    <!-- Separate form for the "Test Mail" functionality to avoid nesting forms -->
    <form id="test-mail-form" action="{{ route('superadmin.settings.system.test-mail') }}" method="POST" class="hidden">
        @csrf
    </form>

    <form action="{{ route('superadmin.settings.system.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900 pb-2 border-b border-gray-100">General Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="app_logo" class="block text-sm font-medium text-gray-700 mb-1">Application Logo</label>
                        <input type="file" name="app_logo" id="app_logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if(isset($settings['app_logo']))
                            <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Current Logo" class="mt-4 max-h-16 border p-1 rounded">
                        @endif
                    </div>
                    <div>
                        <label for="app_favicon" class="block text-sm font-medium text-gray-700 mb-1">Application Favicon (.ico, .png)</label>
                        <input type="file" name="app_favicon" id="app_favicon" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @if(isset($settings['app_favicon']))
                             <img src="{{ asset('storage/' . $settings['app_favicon']) }}" alt="Current Favicon" class="mt-4 max-h-16 border p-1 rounded">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Configuration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Email Configuration (SMTP)</h3>
                    <div class="flex items-center gap-2">
                        <input type="email" name="test_email" form="test-mail-form" placeholder="your-email@example.com" required class="w-64 text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" form="test-mail-form" class="px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                            Send Test Email
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mailer</label>
                        <select name="mail_mailer" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="log" {{ ($settings['mail_mailer'] ?? '') == 'log' ? 'selected' : '' }}>Log (Testing)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="smtp.example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="587">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="mail_password" value="" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Leave empty to keep current">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                        <select name="mail_encryption" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($settings['mail_encryption'] ?? '') == 'null' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="no-reply@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                        <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('app.name') }}" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="{{ config('app.name') }}">
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Default Exam Rules -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 space-y-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Default Exam Rules for New Schools</h3>
                
                @php $rules = $settings['default_exam_rules']; @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Passing Percentage</label>
                        <div class="relative">
                            <input type="number" name="exam_rules[passing_percentage]" value="{{ old('exam_rules.passing_percentage', $rules['passing_percentage'] ?? 33) }}" min="0" max="100" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 pr-8">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Duration (Minutes)</label>
                        <input type="number" name="exam_rules[default_duration]" value="{{ old('exam_rules.default_duration', $rules['default_duration'] ?? 60) }}" min="1" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Result Release Mode</label>
                        <select name="exam_rules[result_release_mode]" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="immediate" {{ (old('exam_rules.result_release_mode', $rules['result_release_mode'] ?? '') == 'immediate') ? 'selected' : '' }}>Immediate (After Submission)</option>
                            <option value="manual" {{ (old('exam_rules.result_release_mode', $rules['result_release_mode'] ?? '') == 'manual') ? 'selected' : '' }}>Manual (Teacher Approval Required)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-medium text-gray-900 mb-2">Security & Proctoring Defaults</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex items-center h-5"><input id="shuffle_questions" name="exam_rules[shuffle_questions]" type="checkbox" {{ old('exam_rules.shuffle_questions', !empty($rules['shuffle_questions'])) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"></div>
                            <div class="ml-3 text-sm"><label for="shuffle_questions" class="font-medium text-gray-700">Shuffle Questions by Default</label></div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5"><input id="disable_copy_paste" name="exam_rules[disable_copy_paste]" type="checkbox" {{ old('exam_rules.disable_copy_paste', !empty($rules['disable_copy_paste'])) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"></div>
                            <div class="ml-3 text-sm"><label for="disable_copy_paste" class="font-medium text-gray-700">Disable Copy/Paste</label></div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5"><input id="full_screen_mode" name="exam_rules[full_screen_mode]" type="checkbox" {{ old('exam_rules.full_screen_mode', !empty($rules['full_screen_mode'])) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"></div>
                            <div class="ml-3 text-sm"><label for="full_screen_mode" class="font-medium text-gray-700">Enforce Full Screen Mode</label></div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Exam Instructions</label>
                    <textarea name="exam_rules[default_instructions]" rows="4" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter default instructions...">{{ old('exam_rules.default_instructions', $rules['default_instructions'] ?? "1. Read all questions carefully.\n2. Do not switch tabs.") }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5">
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Save All Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection