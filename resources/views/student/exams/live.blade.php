<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->title }} | Live Exam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        /* Custom scrollbar for question palette */
        .palette-scroll::-webkit-scrollbar { width: 6px; }
        .palette-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 h-screen flex flex-col overflow-hidden select-none" @if(!($preExamMode ?? false)) x-data="examApp()" x-init="init()" @endif>
    @if($preExamMode ?? false)
    <div class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 bg-slate-900 text-white">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Exam Instructions</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ $exam->title }}</h1>
                <p class="mt-2 text-sm text-slate-300">{{ $exam->subject }} | Class {{ $exam->class }}</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        @if(($instructionSource ?? 'none') === 'exam')
                            Exam Specific Instructions
                        @elseif(($instructionSource ?? 'none') === 'school')
                            School Exam Default Instructions
                        @elseif(($instructionSource ?? 'none') === 'global')
                            Global Default Instructions
                        @else
                            General Instructions
                        @endif
                    </span>
                    <span class="text-sm text-slate-500">Please read carefully before starting the exam.</span>
                </div>

                @if(!empty($instructionItems))
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <ol class="space-y-3 text-sm leading-6 text-slate-700 list-decimal list-inside">
                        @foreach($instructionItems as $instruction)
                        <li>{{ $instruction }}</li>
                        @endforeach
                    </ol>
                </div>
                @else
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-900">
                    No exam-specific or school default instructions were found for this exam.
                </div>
                @endif

                <div class="rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-base font-semibold text-slate-900">What happens next</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        After you continue, the system will ask for camera, microphone, and screen-sharing permissions where your browser supports screen sharing.
                        Your exam attempt will begin only after that step.
                    </p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                    <a href="{{ route('student.exams.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Back to Upcoming Exams
                    </a>
                    <a href="{{ route('student.exams.live', ['id' => $exam->id, 'begin' => 1]) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Continue to Start Exam
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    
    <!-- Permissions Setup Screen -->
    <div x-show="!isSetupComplete" class="fixed inset-0 z-[60] bg-white flex flex-col items-center justify-center p-4 text-center" x-cloak>
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Exam Security Check</h2>
            <p class="text-gray-600 mb-8">
                To ensure exam integrity, we require access to your <strong>camera</strong>, <strong>microphone</strong>, and <strong>screen</strong> where supported.
                Please grant permissions to continue.
            </p>

            <div x-show="permissionError" class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm border border-red-200">
                <i class="bi bi-exclamation-circle mr-1"></i> <span x-text="permissionError"></span>
            </div>

            <button @click="requestPermissions" 
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="bi bi-camera-video"></i> <i class="bi bi-mic"></i> <i class="bi bi-display"></i> Grant Access & Start
            </button>
        </div>  
    </div>

    <!-- Violation Warning Overlay -->
    <div x-show="showViolationWarning" 
         class="fixed inset-0 z-[70] bg-gray-900/95 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition.opacity
         x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Warning: Navigation Prohibited</h2>
            <p class="text-gray-600 mb-6" x-text="violationMessage"></p>
            <button x-show="violationCount <= maxViolations" @click="dismissViolationWarning" 
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                <span x-text="!isFullScreen ? 'Enter Full Screen' : 'I Understand, Return to Exam'"></span>
            </button>
        </div>
    </div>

    <!-- Submit Confirmation Overlay -->
    <div x-show="showSubmitConfirmation"
         class="fixed inset-0 z-[75] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition.opacity
         x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full text-center">
            <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="bi bi-check2-circle"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Submit Exam?</h2>
            <p class="text-gray-600 mb-6 text-sm">Are you sure you want to submit your exam?</p>
            <div class="flex gap-3">
                <button @click="showSubmitConfirmation = false"
                        class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition text-sm">
                    Cancel
                </button>
                <button @click="submitExam"
                        class="flex-1 py-2.5 rounded-xl bg-green-600 text-white font-semibold hover:bg-green-700 transition text-sm">
                    Yes, Submit
                </button>
            </div>
        </div>
    </div>

    <div x-show="isSetupComplete" class="flex h-full w-full flex-col" x-cloak>
    <!-- Top Bar -->
    <header class="shrink-0 border-b border-gray-200 bg-white px-4 py-3 z-20 sm:px-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0 flex items-center gap-3">
            <button @click="paletteOpen = true" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-700 shadow-sm lg:hidden">
                <i class="bi bi-grid"></i>
            </button>
            <div class="min-w-0">
                <div class="truncate font-bold text-base text-gray-800 sm:text-lg">{{ $exam->title }}</div>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-medium text-gray-600">{{ $exam->subject }}</span>
                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700 lg:hidden">
                        Q <span x-text="currentIndex + 1"></span>/<span x-text="questions.length"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4 sm:justify-end sm:gap-6">
            <!-- Timer -->
            <div class="flex items-center gap-2 text-lg font-mono font-bold sm:text-xl" 
                 :class="remainingSeconds < 300 ? 'text-red-600 animate-pulse' : 'text-blue-600'">
                <i class="bi bi-stopwatch"></i>
                <span x-text="formatTime(remainingSeconds)"></span>
            </div>
            
            <div class="hidden h-8 w-px bg-gray-300 sm:block"></div>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-medium text-gray-700 hidden md:block">{{ auth()->user()->name }}</span>
            </div>
        </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        <div x-show="paletteOpen" @click="paletteOpen = false" class="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-sm lg:hidden" x-cloak></div>
        
        <!-- Question Area -->
        <main class="flex-1 flex flex-col h-full relative">
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 lg:p-10" id="question-container">
                <div class="mx-auto min-h-[400px] max-w-3xl rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:rounded-3xl sm:p-8">
                    
                    <!-- Question Header -->
                    <div class="mb-6 flex flex-col gap-3 border-b border-gray-100 pb-4 sm:flex-row sm:items-start sm:justify-between">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                            Question <span x-text="currentIndex + 1"></span> of <span x-text="questions.length"></span>
                        </span>
                        <span class="text-sm font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded">
                            <span x-text="currentQuestion.marks"></span> Marks
                        </span>
                    </div>

                    <!-- Question Text -->
                    <div class="mb-8 text-base font-medium leading-relaxed text-gray-900 sm:text-lg" x-text="currentQuestion.text"></div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <template x-for="option in currentQuestion.options" :key="option.id">
                            <label class="flex cursor-pointer items-start rounded-xl border p-4 transition-all hover:bg-gray-50 sm:items-center"
                                   :class="answers[currentQuestion.id] === option.id ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200'">
                                <input type="radio" :name="'q_' + currentQuestion.id" :value="option.id" 
                                       x-model="answers[currentQuestion.id]" class="mt-1 h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 sm:mt-0">
                                <span class="ml-3 text-sm text-gray-700 sm:text-base" x-text="option.text"></span>
                            </label>
                        </template>
                    </div>

                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="shrink-0 border-t border-gray-200 bg-white p-4">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <button @click="prevQuestion" :disabled="currentIndex === 0"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 sm:text-base">
                    <i class="bi bi-arrow-left"></i> Previous
                </button>

                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-center xl:flex-1">
                    <button @click="clearSelection" class="rounded-xl px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-800">
                        Clear Selection
                    </button>
                    <button @click="markForReview" class="inline-flex items-center justify-center gap-1 rounded-xl px-4 py-2 text-sm font-medium text-yellow-600 hover:bg-yellow-50 hover:text-yellow-800">
                        <i class="bi" :class="isMarkedForReview(currentQuestion.id) ? 'bi-flag-fill' : 'bi-flag'"></i>
                        <span x-text="isMarkedForReview(currentQuestion.id) ? 'Unmark Review' : 'Mark for Review'"></span>
                    </button>
                    <button @click="paletteOpen = true" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 lg:hidden">
                        <i class="bi bi-grid"></i> Open Palette
                    </button>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                    <button @click="nextQuestion" x-show="currentIndex < questions.length - 1"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 sm:text-base">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>

                    <form id="autoSubmitForm" x-show="currentIndex === questions.length - 1" method="POST" action="{{ route('student.exams.submit', $exam->id) }}" x-cloak @submit.prevent="openSubmitConfirm">
                        @csrf
                        <input type="hidden" name="set_code" value="A">
                        <input type="hidden" name="answers" :value="JSON.stringify(answers)">
                        <input type="hidden" name="session_token" :value="sessionToken">
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 sm:text-base">
                            Submit Exam <i class="bi bi-check-lg"></i>
                        </button>
                    </form>
                </div>
                </div>
            </div>
        </main>

        <!-- Sidebar (Question Palette) -->
        <aside class="fixed inset-y-0 right-0 z-40 flex w-80 max-w-[88vw] flex-col border-l border-gray-200 bg-white shadow-2xl transition-transform duration-300 lg:static lg:w-72 lg:max-w-none lg:translate-x-0 lg:shadow-none"
               :class="paletteOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'">
            <div class="flex items-center justify-between border-b border-gray-100 p-4">
                <div>
                    <div class="font-semibold text-gray-700">Question Palette</div>
                    <div class="mt-1 text-xs text-gray-500"><span x-text="Object.keys(answers).length"></span> answered</div>
                </div>
                <button @click="paletteOpen = false" class="rounded-xl p-2 text-gray-500 hover:bg-gray-100 lg:hidden">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 palette-scroll">
                <div class="grid grid-cols-4 gap-3">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button @click="currentIndex = index"
                                class="relative flex h-10 w-10 items-center justify-center rounded-lg border text-sm font-semibold transition-colors"
                                @click="paletteOpen = false"
                                :class="getPaletteClass(index, q.id)">
                            <span x-text="index + 1"></span>
                            <!-- Review Indicator -->
                            <div x-show="isMarkedForReview(q.id)" class="absolute top-0 right-0 -mt-1 -mr-1 w-2.5 h-2.5 bg-yellow-400 rounded-full border border-white"></div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 bg-gray-50 text-xs space-y-2">
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-green-500"></div> Answered</div>
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-yellow-100 border border-yellow-400"></div> Marked for Review</div>
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-gray-100 border border-gray-300"></div> Not Visited</div>
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded bg-blue-600"></div> Current</div>
            </div>
        </aside>
    </div>
    </div>

    <script>
        function examApp() {
            return {
                questions: @json($questionsData),
                currentIndex: 0,
                answers: {},
                reviewList: [],
                remainingSeconds: {{ $remainingSeconds }},
                sessionToken: '{{ $sessionToken }}',
                isFullScreen: false,
                violationCount: 0,
                maxViolations: 3,
                screenStopCount: 0,
                isHandlingViolation: false,
                isSubmitting: false,
                showSubmitConfirmation: false,
                isSetupComplete: false,
                permissionError: null,
                mediaStream: null,
                screenStream: null,
                isScreenSharingSupported: !!(navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia),
                peerConnection: null,
                showViolationWarning: false,
                violationMessage: '',
                paletteOpen: false,

                peerConnections: {}, // { stream_id: pc }
                get currentQuestion() {
                    return this.questions[this.currentIndex];
                },

                init() {
                    // Wait for permissions
                },

                async requestPermissions() {
                    this.permissionError = null;
                    try {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            throw new Error('Camera and microphone access is not supported in this browser.');
                        }

                        // Request Camera & Microphone
                        this.mediaStream = await navigator.mediaDevices.getUserMedia({ video: true,  audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true,
                        channelCount: 2,
                        sampleRate: 48000
                    } });
                        
                        // Request screen sharing only on browsers that support it.
                        if (this.isScreenSharingSupported) {
                            this.screenStream = await this.requestScreenShare();
                        } else {
                            console.warn('Screen sharing is not supported on this browser. Continuing with camera and microphone only.');
                        }
                        
                        // If successful
                        this.isSetupComplete = true;
                        this.startExam();

                    } catch (err) {
                        console.error("Permission error:", err);
                        this.permissionError = this.getPermissionErrorMessage(err);
                        
                        // Cleanup if partial success
                        if(this.mediaStream) this.mediaStream.getTracks().forEach(t => t.stop());
                        if(this.screenStream) this.screenStream.getTracks().forEach(t => t.stop());
                        this.mediaStream = null;
                        this.screenStream = null;
                    }
                },

                async requestScreenShare() {
                    if (!this.isScreenSharingSupported) {
                        return null;
                    }

                    return await navigator.mediaDevices.getDisplayMedia({ video: true });
                },

                getPermissionErrorMessage(err) {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        return 'This browser does not support camera and microphone access. Please use an updated browser.';
                    }

                    if (err && (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError')) {
                        return 'Access denied. Please allow camera and microphone permissions to proceed.';
                    }

                    return 'Access denied: ' + ((err && err.message) || 'Unable to access required devices') + '. Please allow access to proceed.';
                },

                startExam() {
                    this.initTimer();
                    this.initSecurity();
                    this.initHeartbeat();
                    this.initSignaling();
                    
                    // Monitor screen stream stop
                    if(this.screenStream) {
                        this.screenStream.getVideoTracks()[0].addEventListener('ended', () => {
                            this.handleScreenStop();
                        });
                    }
                },

                initTimer() {
                    // Attempt to enter full screen on load (browser may block this without interaction)
                    this.triggerFullScreen();
                    
                    setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        } else {
                            this.submitExam();
                        }
                    }, 1000);
                },
                
                initHeartbeat() {
                    const heartbeatInterval = setInterval(() => {
                        fetch('{{ route("student.exams.heartbeat", $exam->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ session_token: this.sessionToken })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'terminated' || data.status === 'expired' || data.status === 'submitted') {
                                clearInterval(heartbeatInterval);
                                window.location.href = '{{ route("student.exams.index") }}';
                            }
                            // Sync timer if needed, or handle extra time
                            if (data.remaining_seconds !== undefined) {
                                // Optional: sync local timer if drift is large
                                if (Math.abs(this.remainingSeconds - data.remaining_seconds) > 5) {
                                    this.remainingSeconds = data.remaining_seconds;
                                }
                            }
                        });
                    }, 15000); // 15 seconds
                },
                
                initSecurity() {
                    // 1. Full Screen Enforcement
                    this.checkFullScreen();
                    document.addEventListener('fullscreenchange', () => {
                        this.checkFullScreen();
                    });

                    // 2. Prevent Right Click
                    document.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                    });

                    // 3. Disable Keyboard (Only Mouse Allowed)
                    const blockKeyboard = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    };

                    window.addEventListener('keydown', blockKeyboard, true);
                    window.addEventListener('keypress', blockKeyboard, true);
                    window.addEventListener('keyup', blockKeyboard, true);

                    // 4. Detect Tab Switching
                    document.addEventListener('visibilitychange', () => {
                        // When tab is hidden and we are not already handling a violation
                        if (this.isSubmitting) return;
                        if (document.visibilityState === 'hidden' && !this.isHandlingViolation) {
                            this.handleViolation('tab_switch');
                        }
                    });
                },

                checkFullScreen() {
                    if (this.isSubmitting) return;
                    const isNowFullScreen = !!document.fullscreenElement;
                    
                    // If we were full screen, setup is done, and now we are not -> Violation
                    if (this.isFullScreen && !isNowFullScreen && this.isSetupComplete) {
                        this.handleViolation('fullscreen_exit');
                    }
                    
                    this.isFullScreen = isNowFullScreen;
                },

                triggerFullScreen() {
                    document.documentElement.requestFullscreen().then(() => {
                        // Attempt to lock system keys (Chrome/Edge only)
                        if (navigator.keyboard && navigator.keyboard.lock) {
                            navigator.keyboard.lock();
                        }
                    }).catch(err => {
                        console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                    });
                },

                handleScreenStop() {
                    if (this.isSubmitting) return;
                    this.screenStopCount++;
                    if (this.screenStopCount >= 2) {
                        this.handleViolation('screen_stop', true);
                    } else {
                        this.violationMessage = "Warning: You stopped screen sharing. Do not stop it again or the exam will be submitted. Click below to restart screen sharing.";
                        this.showViolationWarning = true;
                        this.isHandlingViolation = true;
                    }
                },

                handleViolation(type = 'unknown', forceSubmit = false) {
                    if (this.isSubmitting) return;
                    if (this.isHandlingViolation) return;
                    this.isHandlingViolation = true;

                    // Show warning immediately to prevent overlay conflict
                    this.violationMessage = "Verifying exam security...";
                    this.showViolationWarning = true;

                    // Send AJAX to backend
                    fetch('{{ route("student.exams.violation", $exam->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ type: type, session_token: this.sessionToken })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'terminate' || forceSubmit) {
                            this.violationMessage = forceSubmit 
                                ? "Screen sharing stopped. Your exam will be submitted automatically." 
                                : data.message;
                            this.showViolationWarning = true;
                            setTimeout(() => this.submitExam(), 3000);
                        } else {
                            this.violationCount = data.count ?? (this.violationCount + 1);
                            this.violationMessage = `Warning: Suspicious activity detected (${type}). This is violation ${this.violationCount} of ${this.maxViolations}.`;
                            this.showViolationWarning = true;
                        }
                    })
                    .then(() => {
                        // If status was 409 (Conflict), the fetch throws or returns error json
                        // You might want to handle specific status codes here if fetch doesn't reject on 409 automatically
                        // (Fetch only rejects on network error usually)
                    })
                    .catch(err => {
                        console.error('Violation log failed', err);
                        // Fallback: increment locally
                        this.violationCount++;
                        
                        if (forceSubmit || this.violationCount > this.maxViolations) {
                             this.violationMessage = "Screen sharing stopped or violation limit exceeded. Exam submitting...";
                             this.showViolationWarning = true;
                             setTimeout(() => this.submitExam(), 3000);
                        } else {
                             this.showViolationWarning = true;
                        }
                    });
                },

                dismissViolationWarning() {
                    // Check if screen share needs to be restored (if stopped once)
                    if (this.screenStopCount === 1 && this.screenStream && this.screenStream.getVideoTracks()[0].readyState === 'ended') {
                        this.requestScreenShare()
                            .then(stream => {
                                if (!stream) {
                                    this.screenStopCount = 0;
                                    this.showViolationWarning = false;
                                    setTimeout(() => { this.isHandlingViolation = false; }, 100);
                                    return;
                                }

                                this.screenStream = stream;
                                // Re-attach listener
                                this.screenStream.getVideoTracks()[0].addEventListener('ended', () => {
                                    this.handleScreenStop();
                                });
                                
                                // Log warning to backend
                                fetch('{{ route("student.exams.violation", $exam->id) }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    body: JSON.stringify({ type: 'screen_stop_warning', session_token: this.sessionToken })
                                }).catch(e => console.error(e));

                                this.showViolationWarning = false;
                                setTimeout(() => { this.isHandlingViolation = false; }, 100);
                            })
                            .catch(err => {
                                console.error(err);
                                this.violationMessage = "Screen sharing is required. Please try again.";
                            });
                        return;
                    }

                    if (!this.isFullScreen) {
                        this.triggerFullScreen();
                    }

                    this.showViolationWarning = false;
                    // Reset flag after user acknowledges, with a small delay
                    setTimeout(() => {
                        this.isHandlingViolation = false;
                    }, 100);
                },

                openSubmitConfirm() {
                    if (this.isSubmitting) return;
                    this.showSubmitConfirmation = true;
                },

                submitExam() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    this.showViolationWarning = false;
                    this.showSubmitConfirmation = false;
                    this.isHandlingViolation = false;
                    document.getElementById('autoSubmitForm').submit();
                },

                formatTime(seconds) {
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = Math.floor(seconds % 60);
                    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },

                nextQuestion() { if (this.currentIndex < this.questions.length - 1) this.currentIndex++; },
                prevQuestion() { if (this.currentIndex > 0) this.currentIndex--; },
                clearSelection() { delete this.answers[this.currentQuestion.id]; },
                
                markForReview() {
                    const id = this.currentQuestion.id;
                    if (this.reviewList.includes(id)) this.reviewList = this.reviewList.filter(i => i !== id);
                    else this.reviewList.push(id);
                },
                isMarkedForReview(id) { return this.reviewList.includes(id); },

                getPaletteClass(index, id) {
                    if (this.currentIndex === index) return 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-200';
                    if (this.answers[id]) return 'bg-green-500 text-white border-green-600';
                    if (this.reviewList.includes(id)) return 'bg-yellow-50 text-yellow-700 border-yellow-400';
                    return 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                },

                // New WebRTC Signaling Logic
                initSignaling() {
                    // Poll for signals from viewers (new requests, answers, ICE)
                    setInterval(() => this.pollSignals(), 3000);
                },

                async pollSignals() {
                    const res = await fetch('{{ route("student.exams.pollSignals", $attempt->id) }}');
                    const signals = await res.json();

                    for (const signal of signals) {
                        switch(signal.type) {
                            case 'new_viewer':
                                await this.handleNewViewer(signal.stream_id);
                                break;
                            case 'answer':
                                await this.handleAnswer(signal.stream_id, signal.payload);
                                break;
                            case 'viewer_ice':
                                this.handleViewerIce(signal.stream_id, signal.payload);
                                break;
                        }
                    }
                },

                async handleNewViewer(streamId) {
                    if (this.peerConnections[streamId] || !this.mediaStream) return;
                    console.log('New viewer requested stream. ID:', streamId);
                    
                    const config = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };
                    const pc = new RTCPeerConnection(config);
                    this.peerConnections[streamId] = pc;

                    pc.onicecandidate = (event) => {
                        if (event.candidate) {
                            this.sendStudentSignal(streamId, 'student_ice', JSON.stringify(event.candidate));
                        }
                    };

                    pc.onconnectionstatechange = () => {
                        if (pc.connectionState === 'disconnected' || pc.connectionState === 'closed' || pc.connectionState === 'failed') {
                            pc.close();
                            delete this.peerConnections[streamId];
                        }
                    };

                    // Add all media tracks to the new peer connection
                    this.mediaStream.getTracks().forEach(track => pc.addTrack(track, this.mediaStream));
                    if (this.screenStream) {
                        this.screenStream.getTracks().forEach(track => pc.addTrack(track, this.screenStream));
                    }

                    const offer = await pc.createOffer();
                    await pc.setLocalDescription(offer);

                    await this.sendStudentSignal(streamId, 'offer', JSON.stringify(pc.localDescription));
                },

                async handleAnswer(streamId, answerPayload) {
                    const pc = this.peerConnections[streamId];
                    if (pc && !pc.currentRemoteDescription) {
                        console.log('Received answer from viewer:', streamId);
                        await pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(answerPayload)));
                    }
                },

                handleViewerIce(streamId, icePayload) {
                    const pc = this.peerConnections[streamId];
                    if (pc) {
                        icePayload.split('||').forEach(candidateStr => {
                            if (candidateStr) {
                                pc.addIceCandidate(new RTCIceCandidate(JSON.parse(candidateStr))).catch(e => console.error("ICE Add Error:", e));
                            }
                        });
                    }
                },

                sendStudentSignal(streamId, type, payload) {
                    return fetch('{{ route("student.exams.signal", $attempt->id) }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({
                                    stream_id: streamId,
                                    type: type,
                                    payload: payload
                                })
                            });
                }
            }
        }
    </script>
    @endif
</body>
</html>
