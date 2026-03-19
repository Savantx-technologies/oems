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
<body class="bg-gray-50 h-screen flex flex-col overflow-hidden select-none" x-data="examApp()" x-init="init()">
    
    <!-- Permissions Setup Screen -->
    <div x-show="!isSetupComplete" class="fixed inset-0 z-[60] bg-white flex flex-col items-center justify-center p-4 text-center" x-cloak>
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Exam Security Check</h2>
            <p class="text-gray-600 mb-8">
                To ensure exam integrity, we require access to your <strong>camera</strong>, <strong>microphone</strong>, and <strong>screen</strong>. 
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

    <div x-show="isSetupComplete" class="flex flex-col h-full w-full" x-cloak>
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <div class="font-bold text-lg text-gray-800">{{ $exam->title }}</div>
            <span class="px-2 py-1 bg-gray-100 text-xs rounded text-gray-600">{{ $exam->subject }}</span>
        </div>

        <div class="flex items-center gap-6">
            <!-- Timer -->
            <div class="flex items-center gap-2 text-xl font-mono font-bold" 
                 :class="remainingSeconds < 300 ? 'text-red-600 animate-pulse' : 'text-blue-600'">
                <i class="bi bi-stopwatch"></i>
                <span x-text="formatTime(remainingSeconds)"></span>
            </div>
            
            <div class="h-8 w-px bg-gray-300"></div>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-medium text-gray-700 hidden md:block">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Question Area -->
        <main class="flex-1 flex flex-col h-full relative">
            <div class="flex-1 overflow-y-auto p-6 md:p-10" id="question-container">
                <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8 min-h-[400px]">
                    
                    <!-- Question Header -->
                    <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                            Question <span x-text="currentIndex + 1"></span> of <span x-text="questions.length"></span>
                        </span>
                        <span class="text-sm font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded">
                            <span x-text="currentQuestion.marks"></span> Marks
                        </span>
                    </div>

                    <!-- Question Text -->
                    <div class="text-lg text-gray-900 font-medium mb-8 leading-relaxed" x-text="currentQuestion.text"></div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <template x-for="option in currentQuestion.options" :key="option.id">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all hover:bg-gray-50"
                                   :class="answers[currentQuestion.id] === option.id ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200'">
                                <input type="radio" :name="'q_' + currentQuestion.id" :value="option.id" 
                                       x-model="answers[currentQuestion.id]" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700" x-text="option.text"></span>
                            </label>
                        </template>
                    </div>

                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="bg-white border-t border-gray-200 p-4 flex justify-between items-center shrink-0">
                <button @click="prevQuestion" :disabled="currentIndex === 0"
                        class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Previous
                </button>

                <div class="flex gap-3">
                    <button @click="clearSelection" class="px-4 py-2 text-sm text-red-600 hover:text-red-800 font-medium">
                        Clear Selection
                    </button>
                    <button @click="markForReview" class="px-4 py-2 text-sm text-yellow-600 hover:text-yellow-800 font-medium flex items-center gap-1">
                        <i class="bi" :class="isMarkedForReview(currentQuestion.id) ? 'bi-flag-fill' : 'bi-flag'"></i>
                        <span x-text="isMarkedForReview(currentQuestion.id) ? 'Unmark Review' : 'Mark for Review'"></span>
                    </button>
                </div>

                <button @click="nextQuestion" x-show="currentIndex < questions.length - 1"
                        class="px-6 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 flex items-center gap-2">
                    Next <i class="bi bi-arrow-right"></i>
                </button>

                <form id="autoSubmitForm" x-show="currentIndex === questions.length - 1" method="POST" action="{{ route('student.exams.submit', $exam->id) }}" x-cloak @submit.prevent="openSubmitConfirm">
                    @csrf
                    <input type="hidden" name="set_code" value="A">
                    <input type="hidden" name="answers" :value="JSON.stringify(answers)">
                    <input type="hidden" name="session_token" :value="sessionToken">
                    <button type="submit"
                            class="px-6 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 flex items-center gap-2">
                        Submit Exam <i class="bi bi-check-lg"></i>
                    </button>
                </form>
            </div>
        </main>

        <!-- Sidebar (Question Palette) -->
        <aside class="w-72 bg-white border-l border-gray-200 flex flex-col shrink-0 z-10">
            <div class="p-4 border-b border-gray-100 font-semibold text-gray-700">Question Palette</div>
            
            <div class="flex-1 overflow-y-auto p-4 palette-scroll">
                <div class="grid grid-cols-4 gap-3">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button @click="currentIndex = index"
                                class="w-10 h-10 rounded-lg text-sm font-semibold flex items-center justify-center transition-colors border"
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
                peerConnection: null,
                showViolationWarning: false,
                violationMessage: '',

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
                        // Request Camera & Microphone
                        this.mediaStream = await navigator.mediaDevices.getUserMedia({ video: true,  audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true,
                        channelCount: 2,
                        sampleRate: 48000
                    } });
                        
                        // Request Screen
                        this.screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
                        
                        // If successful
                        this.isSetupComplete = true;
                        this.startExam();

                    } catch (err) {
                        console.error("Permission error:", err);
                        this.permissionError = "Access denied: " + err.message + ". Please allow access to proceed.";
                        
                        // Cleanup if partial success
                        if(this.mediaStream) this.mediaStream.getTracks().forEach(t => t.stop());
                        if(this.screenStream) this.screenStream.getTracks().forEach(t => t.stop());
                        this.mediaStream = null;
                        this.screenStream = null;
                    }
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
                        navigator.mediaDevices.getDisplayMedia({ video: true })
                            .then(stream => {
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
</body>
</html>
