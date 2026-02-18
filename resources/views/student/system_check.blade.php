@extends('layouts.student')

@section('title', 'System Compatibility Check')

@section('content')
<div class="max-w-4xl mx-auto" x-data="systemCheck()">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Compatibility Check</h1>
        <p class="text-gray-500">Ensure your device is ready for online exams by running the diagnostics below.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Diagnostic Status</h2>
                    <p class="text-sm text-gray-500" x-text="statusMessage"></p>
                </div>
                <button @click="runChecks()"
                        :disabled="isRunning"
                        class="px-6 py-2.5 rounded-lg font-medium text-white transition-all flex items-center gap-2"
                        :class="isRunning ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 shadow-md hover:shadow-lg'">
                    <i class="bi" :class="isRunning ? 'bi-arrow-repeat animate-spin' : 'bi-play-circle-fill'"></i>
                    <span x-text="isRunning ? 'Running Checks...' : 'Run System Check'"></span>
                </button>
            </div>
        </div>

        <!-- Check Items -->
        <template x-for="(check, key) in checks" :key="key">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-start gap-4 transition-colors"
                 :class="{
                    'border-green-200 bg-green-50': check.status === 'pass',
                    'border-red-200 bg-red-50': check.status === 'fail',
                    'border-gray-200': check.status === 'pending'
                 }">
                
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-lg"
                     :class="{
                        'bg-green-100 text-green-600': check.status === 'pass',
                        'bg-red-100 text-red-600': check.status === 'fail',
                        'bg-gray-100 text-gray-500': check.status === 'pending' || check.status === 'running'
                     }">
                    <i class="bi" :class="getIcon(key, check.status)"></i>
                </div>

                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 capitalize" x-text="check.label"></h3>
                    <p class="text-sm mt-1" 
                       :class="{
                           'text-green-700': check.status === 'pass',
                           'text-red-700': check.status === 'fail',
                           'text-gray-500': check.status === 'pending'
                       }" 
                       x-text="check.message"></p>
                    
                    <!-- Preview Area for Camera -->
                    <div x-show="key === 'camera' && check.status === 'pass'" class="mt-3">
                        <video id="camera-preview" autoplay playsinline muted class="w-full h-32 object-cover rounded-lg bg-black border border-gray-300"></video>
                    </div>
                </div>

                <div x-show="check.status === 'pass'" class="text-green-600 text-xl"><i class="bi bi-check-circle-fill"></i></div>
                <div x-show="check.status === 'fail'" class="text-red-600 text-xl"><i class="bi bi-x-circle-fill"></i></div>
                <div x-show="check.status === 'running'" class="text-indigo-600 text-xl"><i class="bi bi-arrow-repeat animate-spin"></i></div>
            </div>
        </template>

    </div>
</div>

<script>
function systemCheck() {
    return {
        isRunning: false,
        statusMessage: 'Click the button to start checking your system compatibility.',
        checks: {
            browser: { label: 'Browser Compatibility', status: 'pending', message: 'Waiting to check...' },
            resolution: { label: 'Screen Resolution', status: 'pending', message: 'Waiting to check...' },
            connection: { label: 'Internet Connection', status: 'pending', message: 'Waiting to check...' },
            mouse: { label: 'Mouse / Cursor', status: 'pending', message: 'Waiting to check...' },
            camera: { label: 'Webcam Access', status: 'pending', message: 'Waiting to check...' },
            microphone: { label: 'Microphone Access', status: 'pending', message: 'Waiting to check...' },
        },

        getIcon(key, status) {
            if (status === 'running') return 'bi-arrow-repeat';
            const icons = {
                browser: 'bi-browser-chrome',
                resolution: 'bi-display',
                connection: 'bi-wifi',
                mouse: 'bi-mouse',
                camera: 'bi-webcam',
                microphone: 'bi-mic'
            };
            return icons[key] || 'bi-question';
        },

        async runChecks() {
            this.isRunning = true;
            this.statusMessage = 'Diagnostics in progress...';
            
            // Reset
            Object.keys(this.checks).forEach(k => {
                this.checks[k].status = 'pending';
                this.checks[k].message = 'Waiting...';
            });

            try {
                // 1. Browser Check
                this.updateStatus('browser', 'running', 'Checking browser...');
                await new Promise(r => setTimeout(r, 500)); // UX delay
                const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
                const isFirefox = /Firefox/.test(navigator.userAgent);
                const isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
                
                if (isChrome || isFirefox || isSafari) {
                    this.updateStatus('browser', 'pass', 'Supported browser detected.');
                } else {
                    this.updateStatus('browser', 'pass', 'Browser appears compatible (Chrome/Firefox recommended).');
                }

                // 2. Resolution Check
                this.updateStatus('resolution', 'running', 'Checking resolution...');
                await new Promise(r => setTimeout(r, 500));
                const width = window.screen.width;
                const height = window.screen.height;
                if (width >= 1024) {
                    this.updateStatus('resolution', 'pass', `Resolution: ${width}x${height} (Good)`);
                } else {
                    this.updateStatus('resolution', 'pass', `Resolution: ${width}x${height} (Usable, but desktop recommended)`);
                }

                // 3. Connection Check
                this.updateStatus('connection', 'running', 'Pinging server...');
                const start = Date.now();
                try {
                    await fetch('{{ route("student.dashboard") }}', { method: 'HEAD', cache: 'no-store' });
                    const latency = Date.now() - start;
                    this.updateStatus('connection', 'pass', `Online (Latency: ${latency}ms)`);
                } catch (e) {
                    this.updateStatus('connection', 'fail', 'Could not reach server. Check internet.');
                }

                // 4. Mouse Check
                this.updateStatus('mouse', 'running', 'Move your mouse cursor...');
                
                let mouseDist = 0;
                let lastPos = null;
                const mouseHandler = (e) => {
                    if (lastPos) {
                        mouseDist += Math.sqrt(Math.pow(e.clientX - lastPos.x, 2) + Math.pow(e.clientY - lastPos.y, 2));
                    }
                    lastPos = { x: e.clientX, y: e.clientY };
                };
                window.addEventListener('mousemove', mouseHandler);
                await new Promise(r => setTimeout(r, 1500));
                window.removeEventListener('mousemove', mouseHandler);

                if (mouseDist > 50) {
                    this.updateStatus('mouse', 'pass', `Mouse detected (Activity: ${Math.round(mouseDist)}).`);
                } else {
                    const hasFinePointer = window.matchMedia('(pointer: fine)').matches;
                    this.updateStatus('mouse', 'pass', hasFinePointer ? 'Mouse detected (No movement).' : 'Touch input detected.');
                }

                // 5. Media Devices (Camera & Mic)
                this.updateStatus('camera', 'running', 'Requesting access...');
                this.updateStatus('microphone', 'running', 'Requesting access...');
                
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                    
                    // Camera Success
                    this.updateStatus('camera', 'pass', 'Camera access granted.');
                    await new Promise(r => setTimeout(r, 100)); // Wait for DOM update
                    const videoEl = document.getElementById('camera-preview');
                    if(videoEl) {
                        videoEl.srcObject = stream;
                        videoEl.play().catch(e => console.error(e));
                    }

                    // Mic Success
                    this.updateStatus('microphone', 'pass', 'Microphone access granted.');

                } catch (err) {
                    console.error(err);
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        this.updateStatus('camera', 'fail', 'Permission denied. Please allow camera access.');
                        this.updateStatus('microphone', 'fail', 'Permission denied. Please allow microphone access.');
                    } else {
                        this.updateStatus('camera', 'fail', 'Device not found or unavailable.');
                        this.updateStatus('microphone', 'fail', 'Device not found or unavailable.');
                    }
                }

                this.statusMessage = 'Diagnostics completed.';
            } catch (e) {
                console.error(e);
                this.statusMessage = 'An error occurred during checks.';
            } finally {
                this.isRunning = false;
            }
        },

        updateStatus(key, status, msg) {
            this.checks[key].status = status;
            this.checks[key].message = msg;
        }
    }
}
</script>
@endsection