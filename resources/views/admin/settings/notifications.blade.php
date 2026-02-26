@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Notification Settings</h1>
        <p class="text-gray-500 text-sm">Manage how and when notifications are sent to students and staff.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Channels -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Notification Channels</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="email_alerts" name="email_alerts" type="checkbox" {{ !empty($settings['email_alerts']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="email_alerts" class="font-medium text-gray-700">Enable Email Alerts</label>
                            <p class="text-gray-500">Send important updates via email to students and staff.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="sms_alerts" name="sms_alerts" type="checkbox" {{ !empty($settings['sms_alerts']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="sms_alerts" class="font-medium text-gray-700">Enable SMS Alerts</label>
                            <p class="text-gray-500">Send critical alerts via SMS (Requires SMS gateway configuration).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Triggers -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Automatic Triggers</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="exam_published" name="exam_published" type="checkbox" {{ !empty($settings['exam_published']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="exam_published" class="font-medium text-gray-700">Exam Published</label>
                            <p class="text-gray-500">Notify students automatically when a new exam is published.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="result_released" name="result_released" type="checkbox" {{ !empty($settings['result_released']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="result_released" class="font-medium text-gray-700">Result Released</label>
                            <p class="text-gray-500">Notify students when their exam results are available.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="student_login_alert" name="student_login_alert" type="checkbox" {{ !empty($settings['student_login_alert']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="student_login_alert" class="font-medium text-gray-700">New Device Login Alert</label>
                            <p class="text-gray-500">Notify students if their account is accessed from a new device.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
