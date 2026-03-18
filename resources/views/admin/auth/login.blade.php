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
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700 px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Admin Login
        </h2>

        <!-- TABS -->
        <div class="flex bg-gray-100 rounded-lg p-1 mb-6 text-sm font-medium">
            <button onclick="switchTab('password')" id="tab-password"
                class="w-1/3 py-2 rounded-lg bg-white shadow text-indigo-600">
                Password
            </button>
            <button onclick="switchTab('emailOtp')" id="tab-emailOtp" class="w-1/3 py-2 rounded-lg text-gray-600">
                Email OTP
            </button>
            <button onclick="switchTab('mobileOtp')" id="tab-mobileOtp" class="w-1/3 py-2 rounded-lg text-gray-600">
                Mobile OTP
            </button>
        </div>

        <!-- ================= PASSWORD LOGIN ================= -->
        <form id="passwordForm" method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf

            <input type="email" name="email" placeholder="Email"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

            <input type="password" name="password" placeholder="Password"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">

            <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                Login
            </button>
        </form>

        <!-- ================= EMAIL OTP ================= -->
        <div id="emailOtpForm" class="hidden space-y-4">

            <input type="email" id="emailOtpInput" placeholder="Enter Email"
                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500">

            <button onclick="sendEmailOtp()" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Send OTP
            </button>

            <div id="emailOtpVerify" class="hidden space-y-3">
                <input type="text" id="emailOtp" maxlength="6" placeholder="Enter OTP"
                    class="w-full border rounded-lg px-3 py-2 text-center tracking-widest">

                <button onclick="verifyEmailOtp()" class="w-full bg-green-600 text-white py-2 rounded-lg">
                    Verify & Login
                </button>
            </div>
        </div>

        <!-- ================= MOBILE OTP ================= -->
        <div id="mobileOtpForm" class="hidden space-y-4">

            <div class="flex">
                <span class="px-3 flex items-center bg-gray-100 border border-r-0 rounded-l-lg">
                    +91
                </span>
                <input type="text" id="mobile" maxlength="10" placeholder="Mobile Number"
                    class="w-full border rounded-r-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>

            <button onclick="sendMobileOtp()"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                Send OTP
            </button>

            <div id="mobileOtpVerify" class="hidden space-y-4">

                <p class="text-center text-sm text-gray-600">
                    OTP sent to <span id="showMobile"></span>
                </p>

                <!-- OTP BOXES -->
                <div class="flex justify-between gap-2">
                    <input type="text" maxlength="1" class="otp-box" />
                    <input type="text" maxlength="1" class="otp-box" />
                    <input type="text" maxlength="1" class="otp-box" />
                    <input type="text" maxlength="1" class="otp-box" />
                    <input type="text" maxlength="1" class="otp-box" />
                    <input type="text" maxlength="1" class="otp-box" />
                </div>

                <!-- TIMER -->
                <p class="text-center text-sm text-gray-500">
                    Resend OTP in <span id="timer">30</span>s
                </p>

                <button id="resendBtn" onclick="sendMobileOtp()" disabled
                    class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                    Resend OTP
                </button>

                <button onclick="verifyMobileOtp()"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                    Verify & Login
                </button>

            </div>
        </div>

    </div>

 <!-- TOAST -->
    <div id="toast" class="fixed top-5 right-5 hidden px-4 py-2 rounded-lg text-white shadow-lg"></div>

    <!-- LOADER -->
    <div id="loader" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
            <div class="w-5 h-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <span class="text-gray-700 font-medium">Processing...</span>
        </div>
    </div>

    <script>
        function switchTab(tab) {

    // hide all
    document.getElementById('passwordForm').classList.add('hidden');
    document.getElementById('emailOtpForm').classList.add('hidden');
    document.getElementById('mobileOtpForm').classList.add('hidden');

    // reset tab styles
    document.querySelectorAll('[id^="tab-"]').forEach(btn => {
        btn.classList.remove('bg-white','shadow','text-indigo-600');
        btn.classList.add('text-gray-600');
    });

    // show selected
    if(tab === 'password'){
        document.getElementById('passwordForm').classList.remove('hidden');
        document.getElementById('tab-password').classList.add('bg-white','shadow','text-indigo-600');
    }

    if(tab === 'emailOtp'){
        document.getElementById('emailOtpForm').classList.remove('hidden');
        document.getElementById('tab-emailOtp').classList.add('bg-white','shadow','text-indigo-600');
    }

    if(tab === 'mobileOtp'){
        document.getElementById('mobileOtpForm').classList.remove('hidden');
        document.getElementById('tab-mobileOtp').classList.add('bg-white','shadow','text-indigo-600');
    }
}

/* ================= EMAIL OTP ================= */

function sendEmailOtp(){
    let email = document.getElementById('emailOtpInput').value;

    fetch('/api/admin/send-email-otp', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({email})
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status){
            document.getElementById('emailOtpVerify').classList.remove('hidden');
        }else{
            alert(data.message);
        }
    });
}

function verifyEmailOtp(){
    let email = document.getElementById('emailOtpInput').value;
    let otp = document.getElementById('emailOtp').value;

    fetch('/api/admin/verify-email-otp',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({email,otp})
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.status){
            window.location.href='/admin/dashboard';
        }else{
            alert(data.message);
        }
    });
}

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

/* ================= SEND OTP ================= */
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

/* ================= TIMER ================= */
function startTimer(){
    let time = 30;
    let timer = document.getElementById('timer');
    let resendBtn = document.getElementById('resendBtn');

    resendBtn.disabled = true;
    resendBtn.classList.add('bg-gray-300','cursor-not-allowed');
    resendBtn.classList.remove('bg-indigo-600');

    clearInterval(timerInterval);

    timerInterval = setInterval(()=>{
        time--;
        timer.innerText = time;

        if(time <= 0){
            clearInterval(timerInterval);
            resendBtn.disabled = false;
            resendBtn.classList.remove('bg-gray-300','cursor-not-allowed');
            resendBtn.classList.add('bg-indigo-600','text-white');
            timer.innerText = 0;
        }
    },1000);
}

/* ================= GET OTP ================= */
function getMobileOtp(){
    let otp = '';
    document.querySelectorAll('.otp-box').forEach(i => otp += i.value);
    return otp;
}

/* ================= VERIFY ================= */
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

/* ================= OTP INPUT CONTROL ================= */
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

/* PASTE */
document.addEventListener('paste', function(e){
    let paste = e.clipboardData.getData('text');
    if(paste.length === 6){
        otpInputs.forEach((input,i)=> input.value = paste[i]);
    }
});
    </script>

   
</body>


</html>