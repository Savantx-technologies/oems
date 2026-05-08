<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | ExamPlatform Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .otp-box {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .otp-box:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, .2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700 px-4">
    <div class="w-full max-w-xl bg-white rounded-xl shadow-2xl p-7 sm:p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">Admin Login</h2>
            <p class="text-center text-sm text-gray-500 mt-2">Sign in using Password or OTP</p>
        </div>

        <div class="flex items-center justify-center">
            <div class="inline-flex rounded-lg p-1 bg-gray-100 border border-gray-200">
                <button id="tabPassword" type="button" onclick="showPanel('password')" class="px-4 py-2 text-sm rounded-md font-semibold bg-white shadow-sm text-gray-900">
                    Password
                </button>
                <button id="tabOtp" type="button" onclick="showPanel('otp')" class="px-4 py-2 text-sm rounded-md font-semibold text-gray-600 hover:text-gray-900">
                    OTP
                </button>
            </div>
        </div>

        <!-- Password panel -->
        <div id="panel-password" class="mt-6">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Login with Password
                </button>

                <div class="text-center text-sm text-gray-500">
                    Prefer OTP? Click <button type="button" class="text-indigo-600 font-semibold hover:underline" onclick="showPanel('otp')">OTP</button>
                </div>
            </form>
        </div>

        <!-- OTP panel -->
        <div id="panel-otp" class="hidden mt-6 space-y-5">
            <div class="flex items-center justify-center">
                <div class="inline-flex rounded-lg p-1 bg-gray-100 border border-gray-200">
                    <button id="tabMobile" type="button" onclick="showOtpTab('mobile')" class="px-4 py-2 text-sm rounded-md font-semibold bg-white shadow-sm text-gray-900">
                        Mobile OTP
                    </button>
                    <button id="tabEmail" type="button" onclick="showOtpTab('email')" class="px-4 py-2 text-sm rounded-md font-semibold text-gray-600 hover:text-gray-900">
                        Email OTP
                    </button>
                </div>
            </div>

            <!-- Mobile OTP -->
            <div id="otp-mobile" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                    <input type="text" name="mobile" id="mobile"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter mobile number">
                </div>

                <button id="mobileSendBtn" type="button" onclick="sendMobileOtp()"
                    class="w-full bg-green-600 text-white py-2 rounded-md font-semibold hover:bg-green-700 transition duration-200">
                    Send OTP
                </button>

                <div id="mobileOtpVerify" class="hidden pt-2 space-y-4">
                    <p class="text-center text-sm text-gray-600">
                        Enter OTP sent to <span id="showMobile" class="font-semibold"></span>
                    </p>

                    <div class="flex justify-center gap-2">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box mobileOtpBox w-10 h-12 border rounded text-center text-lg" />
                    </div>

                    <button type="button" onclick="verifyMobileOtp()"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700">
                        Verify OTP
                    </button>

                    <div class="text-center text-sm text-gray-500">
                        Resend OTP in <span id="mobileTimer">30</span>s
                    </div>

                    <div class="text-center">
                        <button id="mobileResendBtn" type="button" onclick="resendMobileOtp()" disabled
                            class="text-indigo-600 font-semibold hover:underline disabled:opacity-50 disabled:hover:no-underline">
                            Resend OTP
                        </button>
                    </div>
                </div>
            </div>

            <!-- Email OTP -->
            <div id="otp-email" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="admin@example.com">
                </div>

                <button id="emailSendBtn" type="button" onclick="sendEmailOtp()"
                    class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition duration-200">
                    Send OTP
                </button>

                <div id="emailOtpVerify" class="hidden pt-2 space-y-4">
                    <p class="text-center text-sm text-gray-600">
                        Enter OTP sent to <span id="showEmail" class="font-semibold"></span>
                    </p>

                    <div class="flex justify-center gap-2">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box emailOtpBox w-10 h-12 border rounded text-center text-lg" />
                    </div>

                    <button type="button" onclick="verifyEmailOtp()"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700">
                        Verify OTP
                    </button>

                    <div class="text-center text-sm text-gray-500">
                        Resend OTP in <span id="emailTimer">30</span>s
                    </div>

                    <div class="text-center">
                        <button id="emailResendBtn" type="button" onclick="resendEmailOtp()" disabled
                            class="text-indigo-600 font-semibold hover:underline disabled:opacity-50 disabled:hover:no-underline">
                            Resend OTP
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOADER -->
    <div id="loader" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white px-6 py-3 rounded-lg shadow">Processing...</div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="hidden"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function showToast(msg, success = true) {
            const toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg text-white shadow-lg ${success ? 'bg-green-500' : 'bg-red-500'}`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        function showLoader(show = true) {
            document.getElementById('loader').classList.toggle('hidden', !show);
        }

        function setButtonActive(btn, active) {
            if (!btn) return;
            if (active) {
                btn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
                btn.classList.remove('text-gray-600');
            } else {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
                btn.classList.add('text-gray-600');
            }
        }

        function showPanel(panel) {
            const passwordPanel = document.getElementById('panel-password');
            const otpPanel = document.getElementById('panel-otp');
            const tabPassword = document.getElementById('tabPassword');
            const tabOtp = document.getElementById('tabOtp');

            if (panel === 'otp') {
                passwordPanel.classList.add('hidden');
                otpPanel.classList.remove('hidden');
                setButtonActive(tabPassword, false);
                setButtonActive(tabOtp, true);
            } else {
                otpPanel.classList.add('hidden');
                passwordPanel.classList.remove('hidden');
                setButtonActive(tabPassword, true);
                setButtonActive(tabOtp, false);
            }
        }

        function showOtpTab(tab) {
            const mobilePanel = document.getElementById('otp-mobile');
            const emailPanel = document.getElementById('otp-email');
            const tabMobile = document.getElementById('tabMobile');
            const tabEmail = document.getElementById('tabEmail');

            if (tab === 'email') {
                mobilePanel.classList.add('hidden');
                emailPanel.classList.remove('hidden');
                setButtonActive(tabMobile, false);
                setButtonActive(tabEmail, true);
            } else {
                emailPanel.classList.add('hidden');
                mobilePanel.classList.remove('hidden');
                setButtonActive(tabMobile, true);
                setButtonActive(tabEmail, false);
            }
        }

        // =======================
        // OTP input helpers
        // =======================
        function attachOtpBoxControls(boxSelector) {
            const boxes = document.querySelectorAll(boxSelector);
            boxes.forEach((input, index) => {
                input.addEventListener('input', () => {
                    // Keep only digits (1-char).
                    input.value = input.value.replace(/[^0-9]/g, '').slice(0, 1);
                    if (input.value && index < boxes.length - 1) boxes[index + 1].focus();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) boxes[index - 1].focus();
                });
            });

            document.addEventListener('paste', (e) => {
                const paste = e.clipboardData?.getData('text') || '';
                if (paste.length === 6) {
                    boxes.forEach((input, i) => input.value = paste[i]);
                }
            });
        }

        function getOtpFromBoxes(boxSelector) {
            let otp = '';
            document.querySelectorAll(boxSelector).forEach(i => otp += i.value || '');
            return otp;
        }

        function clearBoxes(boxSelector) {
            document.querySelectorAll(boxSelector).forEach(i => i.value = '');
        }

        attachOtpBoxControls('.mobileOtpBox');
        attachOtpBoxControls('.emailOtpBox');

        // =======================
        // Mobile OTP
        // =======================
        let mobileNumber = '';
        let mobileTimerInterval = null;
        function startMobileTimer(seconds = 30) {
            const timerEl = document.getElementById('mobileTimer');
            const resendBtn = document.getElementById('mobileResendBtn');
            let time = seconds;
            resendBtn.disabled = true;
            clearInterval(mobileTimerInterval);

            timerEl.innerText = time;
            mobileTimerInterval = setInterval(() => {
                time--;
                timerEl.innerText = time;
                if (time <= 0) {
                    clearInterval(mobileTimerInterval);
                    resendBtn.disabled = false;
                    timerEl.innerText = 0;
                }
            }, 1000);
        }

        function resendMobileOtp() {
            clearBoxes('.mobileOtpBox');
            sendMobileOtp();
        }

        function sendMobileOtp() {
            const mobile = document.getElementById('mobile').value.trim();
            if (mobile.length !== 10 || !/^[0-9]+$/.test(mobile)) {
                showToast('Enter a valid mobile number', false);
                return;
            }

            mobileNumber = mobile;
            showLoader(true);

            fetch('{{ route("admin.send.mobile.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ mobile })
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                })
                .then(({ ok, data }) => {
                    showLoader(false);
                    if (data.status || ok && data.status !== false) {
                        showToast('OTP sent successfully');
                        document.getElementById('mobileOtpVerify').classList.remove('hidden');
                        document.getElementById('showMobile').innerText = '+91 ' + mobile;
                        startMobileTimer(30);
                    } else {
                        showToast(data.message || 'Failed to send OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }

        function verifyMobileOtp() {
            const otp = getOtpFromBoxes('.mobileOtpBox');
            if (otp.length !== 6) {
                showToast('Enter complete OTP', false);
                return;
            }

            showLoader(true);

            fetch('{{ route("admin.verify.mobile.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ mobile: mobileNumber, otp })
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                })
                .then(({ ok, data }) => {
                    showLoader(false);
                    if (data.status) {
                        showToast('Login successful');
                        const redirect = data.redirect || '/admin/dashboard';
                        setTimeout(() => window.location.href = redirect, 800);
                    } else {
                        showToast(data.message || 'Invalid OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }

        // =======================
        // Email OTP
        // =======================
        let emailTimerInterval = null;
        function startEmailTimer(seconds = 30) {
            const timerEl = document.getElementById('emailTimer');
            const resendBtn = document.getElementById('emailResendBtn');
            let time = seconds;
            resendBtn.disabled = true;
            clearInterval(emailTimerInterval);
            timerEl.innerText = time;
            emailTimerInterval = setInterval(() => {
                time--;
                timerEl.innerText = time;
                if (time <= 0) {
                    clearInterval(emailTimerInterval);
                    resendBtn.disabled = false;
                    timerEl.innerText = 0;
                }
            }, 1000);
        }

        function resendEmailOtp() {
            clearBoxes('.emailOtpBox');
            sendEmailOtp();
        }

        function sendEmailOtp() {
            const email = document.getElementById('email').value.trim();
            if (!email || !/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/.test(email)) {
                showToast('Enter a valid email address', false);
                return;
            }

            showLoader(true);

            fetch('{{ route("admin.send.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ email })
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                })
                .then(({ ok, data }) => {
                    showLoader(false);
                    if (data.status) {
                        showToast('OTP sent successfully');
                        document.getElementById('emailOtpVerify').classList.remove('hidden');
                        document.getElementById('showEmail').innerText = email;
                        startEmailTimer(30);
                        clearBoxes('.emailOtpBox');
                    } else {
                        showToast(data.message || 'Failed to send OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }

        function verifyEmailOtp() {
            const otp = getOtpFromBoxes('.emailOtpBox');
            if (otp.length !== 6) {
                showToast('Enter complete OTP', false);
                return;
            }

            showLoader(true);

            fetch('{{ route("admin.verify.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ otp })
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                })
                .then(({ ok, data }) => {
                    showLoader(false);
                    if (data.status) {
                        showToast('Login successful');
                        const redirect = data.redirect || '/admin/dashboard';
                        setTimeout(() => window.location.href = redirect, 800);
                    } else {
                        showToast(data.message || 'Invalid OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }
    </script>
</body>
</html>