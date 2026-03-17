@extends('layouts.superadmin')

@section('title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    passwordModalOpen: {{ ($errors->any() || session('open_password_modal') || request()->boolean('otp')) ? 'true' : 'false' }},
    otpStep: {{ (session('password_otp_step') === 'verify' || request()->boolean('otp')) ? 'true' : 'false' }},
    otpExpiresAt: '{{ $otpExpiresAt?->toIso8601String() ?? '' }}',
    otpRemaining: 0,
    otpTimer: null,
    init() {
        this.startOtpTimer();
    },
    startOtpTimer() {
        if (!this.otpExpiresAt) return;
        const expiresAt = new Date(this.otpExpiresAt).getTime();
        const update = () => {
            const now = new Date().getTime();
            const diff = Math.max(0, Math.floor((expiresAt - now) / 1000));
            this.otpRemaining = diff;
            if (diff <= 0 && this.otpTimer) {
                clearInterval(this.otpTimer);
                this.otpTimer = null;
            }
        };
        update();
        if (this.otpTimer) clearInterval(this.otpTimer);
        this.otpTimer = setInterval(update, 1000);
    },
    formatSeconds(total) {
        const m = Math.floor(total / 60);
        const s = total % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
    }
}">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
        <p class="text-gray-500">Review your account details and manage superadmin access security.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-slate-800 via-slate-700 to-blue-700"></div>

                <div class="relative z-10 -mt-12 mb-4">
                    <div class="w-28 h-28 rounded-full border-4 border-white shadow-md mx-auto bg-blue-100 flex items-center justify-center text-blue-700 text-3xl font-bold">
                        {{ strtoupper(substr($superAdmin->name, 0, 1)) }}
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900">{{ $superAdmin->name }}</h2>
                <p class="text-sm text-gray-500 mb-4">{{ $superAdmin->email }}</p>

                <div class="flex justify-center gap-2 mb-6">
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                        Super Admin
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $superAdmin->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                        {{ $superAdmin->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-6 text-left space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</label>
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="bi bi-calendar3 text-gray-400"></i>
                            {{ $superAdmin->created_at?->format('M d, Y') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Account ID</label>
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                            <i class="bi bi-hash text-gray-400"></i>
                            SA-{{ str_pad((string) $superAdmin->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-blue-600"></i>
                    Account Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <div class="text-gray-900 font-semibold">{{ $superAdmin->name }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                        <div class="text-gray-900 font-semibold">{{ $superAdmin->email }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        <div class="text-gray-900">Platform Super Administrator</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Access State</label>
                        <div class="text-gray-900">{{ $superAdmin->is_active ? 'Enabled' : 'Disabled' }}</div>
                    </div>
                </div>
            </div>

            <div id="security" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="bi bi-shield-lock text-blue-600"></i>
                            Access Security
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Update your password and review the latest account activity.</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="passwordModalOpen = true" class="px-4 py-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                            Change Password
                        </button>
                        <a href="{{ route('superadmin.security.logs') }}" class="px-4 py-2 bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-100 transition">
                            View All Sessions
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600"></i>
                        Recent Access Activity
                    </h3>
                    <a href="{{ route('superadmin.security.logs') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Open full log
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentLogs as $log)
                        <div class="flex items-start justify-between gap-4 rounded-xl border border-gray-100 px-4 py-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">{{ $log->event }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $log->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                            <div class="text-right text-xs text-gray-500">
                                <div>{{ $log->ip_address }}</div>
                                <div class="mt-1 truncate max-w-52">{{ $log->description ?: 'Activity recorded' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-200 px-4 py-6 text-sm text-gray-500 text-center">
                            No access activity has been recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div x-show="passwordModalOpen"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div x-show="passwordModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="passwordModalOpen = false"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="passwordModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Change Password</h3>
                    <p class="text-sm text-gray-500 mt-1" x-show="!otpStep">Enter your current and new password to request an OTP.</p>
                    <p class="text-sm text-gray-500 mt-1" x-show="otpStep">Enter the OTP received on your registered email to confirm the change.</p>
                </div>

                <form action="{{ route('superadmin.password.otp.send') }}" method="POST" class="p-6 pt-4" x-show="!otpStep">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="passwordModalOpen = false" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Cancel</button>
                        <button type="submit" class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update Password</button>
                    </div>
                </form>

                <div class="p-6 pt-4 space-y-4" x-show="otpStep">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 flex items-center justify-between gap-3">
                        <div>
                            OTP sent to <span class="font-semibold text-gray-800">{{ $superAdmin->email }}</span>
                            <span class="block text-xs text-gray-500 mt-1" x-show="otpRemaining > 0">
                                Expires in <span class="font-semibold" x-text="formatSeconds(otpRemaining)"></span>
                            </span>
                            <span class="block text-xs text-red-500 mt-1" x-show="otpRemaining === 0">
                                OTP expired. Please resend.
                            </span>
                        </div>
                        <form action="{{ route('superadmin.password.otp.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md border border-blue-200 text-blue-700 bg-blue-50 text-xs font-medium hover:bg-blue-100">
                                Resend OTP
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('superadmin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
                                <input type="text" name="otp" id="otp" inputmode="numeric" maxlength="6" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2">
                                @error('otp')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="passwordModalOpen = false" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Cancel</button>
                            <button type="submit" class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Confirm OTP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
