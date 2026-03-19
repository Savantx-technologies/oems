<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Super Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
        }

        .card {
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.9);
        }

        .otp-box {
            width: 48px;
            height: 52px;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
    </style>

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<!-- LOADER -->
<div id="loader" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white px-6 py-4 rounded-lg shadow">Processing...</div>
</div>

<!-- TOAST -->
<div id="toast" class="hidden"></div>

<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"> Super Admin Login </h2>

    <form method="POST" action="{{ route('superadmin.login.submit') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"> Email </label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="admin@example.com">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"> Password </label>
            <input type="password" name="password"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="••••••••">
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition duration-200">
            Login
        </button>

        <!-- Existing OTP Login -->
        <a href="{{ route('superadmin.otp.form') }}"
            class="block w-full text-center bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition duration-200">
            Login with OTP
        </a>
    </form>

    <!-- Divider -->
    <div class="flex items-center my-5">
        <div class="flex-1 border-t"></div>
        <span class="px-3 text-sm text-gray-500">Mobile OTP</span>
        <div class="flex-1 border-t"></div>
    </div>

    <!-- MOBILE NUMBER -->
    <div class="space-y-4">
        <input type="text" id="mobile"
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
            placeholder="Enter mobile number">

        <button onclick="sendMobileOtp()"
            class="w-full bg-green-600 text-white py-2 rounded-md font-semibold hover:bg-green-700">
            Send OTP
        </button>
    </div>

    <!-- OTP VERIFY -->
    <div id="mobileOtpVerify" class="hidden mt-6">

        <p class="text-center text-sm text-gray-600 mb-3">
            OTP sent to <span id="showMobile" class="font-semibold"></span>
        </p>

        <div class="flex justify-between mb-4">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
            <input type="text" maxlength="1" class="otp-box border w-10 h-10 text-center rounded">
        </div>

        <button onclick="verifyMobileOtp()"
            class="w-full bg-indigo-600 text-white py-2 rounded-md font-semibold">
            Verify OTP
        </button>

        <div class="text-center mt-3">
            <span class="text-sm text-gray-500">Resend in <span id="timer">30</span>s</span>
        </div>

        <button id="resendBtn" onclick="sendMobileOtp()"
            class="w-full mt-3 bg-gray-300 py-2 rounded-md">
            Resend OTP
        </button>
    </div>

</div>

<script>

/* ================= MOBILE OTP ================= */

let timerInterval;
let mobileNumber = '';

function showLoader(show = true){
    document.getElementById('loader').classList.toggle('hidden', !show);
}

function showToast(msg, success = true){
    let toast = document.getElementById('toast');
    toast.innerText = msg;
    toast.className = `fixed top-5 right-5 px-4 py-2 rounded-lg text-white shadow-lg ${success ? 'bg-green-500' : 'bg-red-500'}`;
    toast.classList.remove('hidden');

    setTimeout(() => toast.classList.add('hidden'), 3000);
}

/* SEND OTP */
function sendMobileOtp(){
    let mobile = document.getElementById('mobile').value;

    if(mobile.length !== 10){
        showToast('Enter valid mobile number', false);
        return;
    }

    mobileNumber = mobile;
    showLoader(true);

    fetch('/api/superadmin/send-mobile-otp',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({mobile})
    })
    .then(res=>res.json())
    .then(data=>{
        showLoader(false);

        if(data.status){
            showToast('OTP sent successfully');

            document.getElementById('mobileOtpVerify').classList.remove('hidden');
            document.getElementById('showMobile').innerText = '+91 ' + mobile;

            startTimer();
        }else{
            showToast(data.message, false);
        }
    });
}

/* TIMER */
function startTimer(){
    let time = 30;
    let timer = document.getElementById('timer');
    let resendBtn = document.getElementById('resendBtn');

    resendBtn.disabled = true;

    clearInterval(timerInterval);

    timerInterval = setInterval(()=>{
        time--;
        timer.innerText = time;

        if(time <= 0){
            clearInterval(timerInterval);
            resendBtn.disabled = false;
            timer.innerText = 0;
        }
    },1000);
}

/* GET OTP */
function getMobileOtp(){
    let otp = '';
    document.querySelectorAll('.otp-box').forEach(i => otp += i.value);
    return otp;
}

/* VERIFY OTP */
function verifyMobileOtp(){
    let otp = getMobileOtp();

    if(otp.length !== 6){
        showToast('Enter complete OTP', false);
        return;
    }

    showLoader(true);

    fetch('/api/superadmin/verify-mobile-otp',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({mobile: mobileNumber, otp})
    })
    .then(res=>res.json())
    .then(data=>{
        showLoader(false);

        if(data.status){
            showToast('Login successful');

            setTimeout(()=>{
                window.location.href='/superadmin/dashboard';
            },1000);

        }else{
            showToast(data.message, false);
        }
    });
}

/* OTP INPUT CONTROL */
const otpInputs = document.querySelectorAll('.otp-box');

otpInputs.forEach((input, index) => {

    input.addEventListener('input', () => {
        if(input.value && index < 5){
            otpInputs[index+1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if(e.key === "Backspace" && !input.value && index > 0){
            otpInputs[index-1].focus();
        }
    });
});

/* PASTE OTP */
document.addEventListener('paste', function(e){
    let paste = e.clipboardData.getData('text');
    if(paste.length === 6){
        otpInputs.forEach((input,i)=> input.value = paste[i]);
    }
});

</script>

</body>

</html>
