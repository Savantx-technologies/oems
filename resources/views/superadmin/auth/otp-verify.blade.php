<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .otp-box {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.15);
        }

        .otp-box:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, .2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700 px-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Verify Email OTP</h2>
        <p class="text-center text-sm text-gray-500 mb-6">
            {{ session('superadmin_otp_email') ? 'OTP sent to ' . session('superadmin_otp_email') : 'Enter the OTP sent to your registered email.' }}
        </p>

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">One-Time Password</label>
                <div class="flex justify-center gap-2">
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box otpVerifyBox" />
                </div>

                @error('otp')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button id="verifyOtpBtn" type="button"
                class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition duration-200">
                Verify
            </button>

            <div class="text-center text-sm text-gray-500">
                Resend OTP in <span id="resendTimer">30</span>s
            </div>

            <button id="resendOtpBtn" type="button" disabled
                class="w-full text-indigo-600 font-semibold hover:underline disabled:opacity-50 transition">
                Resend OTP
            </button>
        </div>
    </div>

    <div id="loader" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white px-6 py-3 rounded-lg shadow">Processing...</div>
    </div>

    <div id="toast" class="hidden"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const otpEmail = "{{ session('superadmin_otp_email') ?? '' }}";

        let timerInterval = null;

        function showLoader(show = true) {
            document.getElementById('loader').classList.toggle('hidden', !show);
        }

        function showToast(msg, success = true) {
            const toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg text-white shadow-lg ${success ? 'bg-green-500' : 'bg-red-500'}`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        function attachOtpControls() {
            const boxes = document.querySelectorAll('.otpVerifyBox');
            boxes.forEach((input, index) => {
                input.addEventListener('input', () => {
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

        function getOtpFromBoxes() {
            let otp = '';
            document.querySelectorAll('.otpVerifyBox').forEach(i => otp += i.value || '');
            return otp;
        }

        function clearBoxes() {
            document.querySelectorAll('.otpVerifyBox').forEach(i => i.value = '');
        }

        function startResendTimer(seconds = 30) {
            const resendBtn = document.getElementById('resendOtpBtn');
            const timerEl = document.getElementById('resendTimer');
            let time = seconds;
            resendBtn.disabled = true;
            timerEl.innerText = time;

            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                time--;
                timerEl.innerText = time;
                if (time <= 0) {
                    clearInterval(timerInterval);
                    resendBtn.disabled = false;
                    timerEl.innerText = 0;
                }
            }, 1000);
        }

        function sendEmailOtp() {
            if (!otpEmail) {
                showToast('Session expired. Please request a new OTP.', false);
                return;
            }

            showLoader(true);
            fetch('{{ route("superadmin.otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ email: otpEmail })
            })
                .then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    return { ok: res.ok, data };
                })
                .then(({ ok, data }) => {
                    showLoader(false);
                    if (data.status) {
                        showToast('OTP resent successfully');
                        clearBoxes();
                        startResendTimer(30);
                    } else {
                        showToast(data.message || 'Failed to resend OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }

        function verifyEmailOtp() {
            const otp = getOtpFromBoxes();
            if (otp.length !== 6) {
                showToast('Enter complete OTP', false);
                return;
            }

            showLoader(true);
            fetch('{{ route("superadmin.otp.verify") }}', {
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
                        showToast('OTP verified');
                        const redirect = data.redirect || '/superadmin/dashboard';
                        setTimeout(() => window.location.href = redirect, 700);
                    } else {
                        showToast(data.message || 'Invalid OTP', false);
                    }
                })
                .catch(() => {
                    showLoader(false);
                    showToast('Network error. Please try again.', false);
                });
        }

        attachOtpControls();
        startResendTimer(30);

        document.getElementById('verifyOtpBtn').addEventListener('click', verifyEmailOtp);
        document.getElementById('resendOtpBtn').addEventListener('click', sendEmailOtp);
    </script>

</body>
</html>
