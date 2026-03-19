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

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"> Admin Login </h2> <!-- Password Login -->
        <form id="passwordForm" method="POST" action="{{ route('admin.login') }}" class="space-y-4"> @csrf <div> <label
                    class="block text-sm font-medium text-gray-700">Email</label> <input type="email" name="email"
                    value="{{ old('email') }}" required
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <!-- small otp button --> <button type="button" onclick="showOtpForm()"
                    class="mt-2 text-sm text-indigo-600 hover:underline font-medium"> Login with OTP </button>
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror </div>
            <div> <label class="block text-sm font-medium text-gray-700">Password</label> <input type="password"
                    name="password" required
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror </div> <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Login with Password </button>
        </form> <!-- OTP Login Form (hidden initially) -->
        <form id="otpForm" method="POST" action="{{ route('admin.send.otp') }}" class="space-y-4 hidden mt-4"> @csrf
            <div> <label class="block text-sm font-medium text-gray-700">Email for OTP</label> <input type="email"
                    name="email" value="{{ old('email') }}" required
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div> <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700 transition"> Send
                OTP </button> <!-- back to password login --> <button type="button" onclick="showPasswordForm()"
                class="w-full text-sm text-gray-600 hover:underline"> Back to password login </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-5">
            <div class="flex-1 border-t"></div>
            <span class="px-3 text-sm text-gray-500">Mobile OTP</span>
            <div class="flex-1 border-t"></div>
        </div>

        <!-- Mobile OTP -->
        <form method="POST" action="{{ route('admin.send.mobile-otp') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mobile Number
                </label>
                <input type="text" name="mobile" id="mobile"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="Enter mobile number">
            </div>

            <button type="button" onclick="sendMobileOtp()"
                class="w-full bg-green-600 text-white py-2 rounded-md font-semibold hover:bg-green-700 transition duration-200">
                Send OTP
            </button>
        </form>
        <!-- MOBILE OTP VERIFY -->
        <div id="mobileOtpVerify" class="hidden mt-6">

            <p class="text-center text-sm text-gray-600">
                Enter OTP sent to <span id="showMobile" class="font-semibold"></span>
            </p>

            <!-- OTP BOXES -->
            <div class="flex justify-center gap-2 mt-4">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
                <input type="text" maxlength="1" class="otp-box w-10 h-12 border rounded text-center text-lg">
            </div>

            <!-- VERIFY BUTTON -->
            <button onclick="verifyMobileOtp()"
                class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700">
                Verify OTP
            </button>

            <!-- TIMER -->
            <div class="text-center mt-3 text-sm text-gray-500">
                Resend OTP in <span id="timer">30</span>s
            </div>

            <!-- RESEND BUTTON -->
            <div class="text-center mt-2">
                <button id="resendBtn" onclick="sendMobileOtp()" class="text-indigo-600 font-medium hover:underline">
                    Resend OTP
                </button>
            </div>

        </div>
    </div>
<div id="loader" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white px-6 py-3 rounded-lg shadow">Processing...</div>
</div>

<div id="toast" class="hidden"></div>
    <script>
        function showOtpForm() { document.getElementById('passwordForm').classList.add('hidden'); document.getElementById('otpForm').classList.remove('hidden'); } function showPasswordForm() { document.getElementById('otpForm').classList.add('hidden'); document.getElementById('passwordForm').classList.remove('hidden'); } 

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

    fetch('/api/admin/send-mobile-otp',{
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

    fetch('/api/admin/verify-mobile-otp',{
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
                window.location.href='/admin/dashboard';
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