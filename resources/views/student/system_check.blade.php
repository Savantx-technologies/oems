@extends('layouts.student')

@section('title', 'System Compatibility Check')

@section('content')
<div class="max-w-4xl mx-auto" x-data="systemCheck()">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">System Compatibility Check</h1>
        <p class="mt-2 text-gray-500">Ensure your device is ready for online exams by running the diagnostics below.</p>
    </div>  

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Diagnostic Status</h2>
                    <p class="text-sm text-gray-500 mt-1" x-text="statusMessage"></p>
                </div>
                <button @click="runChecks()"
                        :disabled="isRunning"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold text-white transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg disabled:shadow-none disabled:cursor-not-allowed"
                        :class="isRunning ? 'bg-gray-400' : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700'">
                    <i class="bi text-lg" :class="isRunning ? 'bi-arrow-repeat animate-spin' : 'bi-play-circle'"></i>
                    <span x-text="isRunning ? 'Running Diagnostics...' : 'Run System Check'"></span>
                </button>
            </div>
            <!-- Progress Bar -->
            <div x-show="isRunning" class="mt-5" x-cloak>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300" :style="`width: ${progressPercentage}%`"></div>
                </div>
            </div>
        </div>

        <!-- Checks List -->
        <div class="divide-y divide-gray-100">
            <template x-for="(check, key) in checks" :key="key">
                <div class="p-5">
                    <div class="flex items-center justify-between gap-4">
                        <!-- Left side: Icon and Text -->
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-xl transition-colors"
                                 :class="{
                                    'bg-green-100 text-green-600': check.status === 'pass',
                                    'bg-red-100 text-red-600': check.status === 'fail',
                                    'bg-indigo-100 text-indigo-600': check.status === 'running',
                                    'bg-gray-100 text-gray-500': check.status === 'pending'
                                 }">
                                <i class="bi" :class="getIcon(key, check.status)"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800" x-text="check.label"></h3>
                                <p class="text-sm mt-1" :class="{
                                    'text-green-700': check.status === 'pass',
                                    'text-red-700': check.status === 'fail',
                                    'text-indigo-700': check.status === 'running',
                                    'text-gray-500': check.status === 'pending'
                                }" x-html="check.message"></p>
                            </div>
                        </div>
                        <!-- Right side: Status Indicator -->
                        <div class="w-28 text-right">
                            <span x-show="check.status === 'pass'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200" x-cloak>
                                <i class="bi bi-check-circle-fill"></i> Pass
                            </span>
                            <span x-show="check.status === 'fail'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200" x-cloak>
                                <i class="bi bi-x-circle-fill"></i> Fail
                            </span>
                            <span x-show="check.status === 'running'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200" x-cloak>
                                <i class="bi bi-arrow-repeat animate-spin"></i> Running
                            </span>
                            <span x-show="check.status === 'pending'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                <i class="bi bi-hourglass-split"></i> Pending
                            </span>
                        </div>
                    </div>
                    <!-- Preview Area for Camera -->
                    <div x-show="key === 'camera' && check.status === 'pass'" class="mt-4 pl-14" x-cloak>
                        <video id="camera-preview" autoplay playsinline muted class="w-full max-w-sm h-40 object-cover rounded-lg bg-black border-2 border-gray-200 shadow-inner"></video>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function systemCheck() {
    return {
        stream: null,
        isRunning: false,
        statusMessage: 'Click the button to start checking your system compatibility.',
        progressPercentage: 0,
        completedChecks: 0,
        checks: {
            browser: { label: 'Browser Compatibility', status: 'pending', message: 'Waiting to be checked...' },
            resolution: { label: 'Screen Resolution', status: 'pending', message: 'Waiting to be checked...' },
            connection: { label: 'Internet Connection', status: 'pending', message: 'Waiting to be checked...' },
            mouse: { label: 'Mouse / Cursor', status: 'pending', message: 'Waiting to be checked...' },
            camera: { label: 'Webcam Access', status: 'pending', message: 'Waiting to be checked...' },
            microphone: { label: 'Microphone Access', status: 'pending', message: 'Waiting to be checked...' },
        },

        getIcon(key, status) {
            if (status === 'running') return 'bi-arrow-repeat';
            const icons = {
                browser: 'bi-globe2',
                resolution: 'bi-display',
                connection: 'bi-wifi',
                mouse: 'bi-mouse',
                camera: 'bi-webcam',
                microphone: 'bi-mic'
            };
            return icons[key] || 'bi-gear';
        },

        stopAllStreams() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            const videoEl = document.getElementById('camera-preview');
            if (videoEl && videoEl.srcObject) {
                videoEl.srcObject = null;
            }
        },

        async runChecks() {
            this.isRunning = true;
            this.statusMessage = 'Diagnostics in progress... Please wait.';
            this.progressPercentage = 0;
            this.completedChecks = 0;
            this.stopAllStreams();
            
            // Reset all checks to pending
            Object.keys(this.checks).forEach(k => {
                this.checks[k].status = 'pending';
                this.checks[k].message = 'Waiting to be checked...';
            });

            try {
                // 1. Browser Check
                this.updateStatus('browser', 'running', 'Checking browser...');
                await new Promise(r => setTimeout(r, 300)); // UX delay
                const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
                const isFirefox = /Firefox/.test(navigator.userAgent);
                if (isChrome || isFirefox) {
                    this.updateStatus('browser', 'pass', 'Supported browser detected (Chrome/Firefox).');
                } else {
                    this.updateStatus('browser', 'pass', 'Browser seems compatible, but Chrome or Firefox is recommended for best performance.');
                }

                // 2. Resolution Check
                this.updateStatus('resolution', 'running', 'Checking resolution...');
                await new Promise(r => setTimeout(r, 300));
                const width = window.screen.width;
                const height = window.screen.height;
                if (width >= 1024) {
                    this.updateStatus('resolution', 'pass', `Resolution: <b>${width}x${height}</b> (Suitable)`);
                } else {
                    this.updateStatus('resolution', 'fail', `Resolution: <b>${width}x${height}</b> (Too low, desktop recommended)`);
                }

                // 3. Connection Check
                this.updateStatus('connection', 'running', 'Pinging server...');
                const start = Date.now();
                try {
                    await fetch('{{ route("welcome") }}', { method: 'HEAD', cache: 'no-store', mode: 'no-cors' });
                    const latency = Date.now() - start;
                    this.updateStatus('connection', 'pass', `Connection is stable (Latency: ${latency}ms)`);
                } catch (e) {
                    this.updateStatus('connection', 'fail', 'Could not reach server. Please check your internet connection.');
                }

                // 4. Mouse Check
                this.updateStatus('mouse', 'running', 'Move your mouse cursor...');
                await new Promise(r => setTimeout(r, 300));
                const hasFinePointer = window.matchMedia('(pointer: fine)').matches;
                this.updateStatus('mouse', 'pass', hasFinePointer ? 'Pointing device (mouse/trackpad) detected.' : 'Touch input detected.');

                // 5. Media Devices (Camera & Mic)
                this.updateStatus('camera', 'running', 'Requesting access...');
                this.updateStatus('microphone', 'running', 'Requesting access...');
                
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                    
                    // Camera Success
                    this.updateStatus('camera', 'pass', 'Webcam access granted.');
                    await this.$nextTick(); // Wait for DOM update
                    const videoEl = document.getElementById('camera-preview');
                    if(videoEl) {
                        videoEl.srcObject = this.stream;
                        videoEl.play().catch(e => console.error(e));
                    }

                    // Mic Success
                    this.updateStatus('microphone', 'pass', 'Microphone access granted.');

                } catch (err) {
                    console.error("Media Error:", err);
                    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                        this.updateStatus('camera', 'fail', 'Permission denied. Please allow access in your browser settings.');
                        this.updateStatus('microphone', 'fail', 'Permission denied. Please allow access in your browser settings.');
                    } else {
                        this.updateStatus('camera', 'fail', 'Webcam not found or is unavailable.');
                        this.updateStatus('microphone', 'fail', 'Microphone not found or is unavailable.');
                    }
                }

                const allPassed = Object.values(this.checks).every(c => c.status === 'pass');
                this.statusMessage = allPassed ? 'All checks passed. Your system is ready.' : 'Some checks failed. Please review the items below.';

            } catch (e) {
                console.error("System Check Error:", e);
                this.statusMessage = 'An unexpected error occurred during the check.';
            } finally {
                this.isRunning = false;
            }
        },

        updateStatus(key, status, msg) {
            if (!this.checks[key]) return;

            this.checks[key].status = status;
            this.checks[key].message = msg;

            if (status === 'pass' || status === 'fail') {
                this.completedChecks++;
                this.progressPercentage = Math.round((this.completedChecks / Object.keys(this.checks).length) * 100);
            }
        }
    }
}
</script>
@endsection