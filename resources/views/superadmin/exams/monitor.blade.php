@extends('layouts.superadmin')

@section('title', 'Live Monitor: ' . $exam->title)

@section('content')
<div class="h-[calc(100vh-140px)] flex flex-col" x-data="monitorApp()">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4 shrink-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Live Monitor</h1>
                <p class="text-sm text-gray-500">{{ $exam->title }} | Class {{ $exam->class }} | <span
                        class="font-semibold">{{ $exam->school->name }}</span></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('superadmin.exams.monitor-blocks.index', $exam->id) }}"
                    class="px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50">Manage Blocks</a>
                <div class="px-4 py-2 bg-white rounded-lg shadow-sm border flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-sm font-medium">Live</span>
                </div>
                <a href="{{ route('superadmin.exams.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Exit</a>
            </div>
        </div>

    <!-- Grid -->
    <div class="flex-1 overflow-y-auto bg-gray-100 p-4 rounded-xl border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                <template x-for="student in students" :key="student.id">
                    <div x-data="studentCard(student.attempt_id)"
                        class="bg-white rounded-xl shadow-sm border p-5 flex flex-col gap-4 transition-all"
                        :class="{
                            'border-red-500 ring-1 ring-red-200': student.violation_count > 2 || student
                                .status === 'terminated',
                            'border-yellow-400': student.is_idle && student.status === 'in_progress',
                            'opacity-75': student.status === 'submitted',
                            'ring-2 ring-green-500 bg-green-50': audioActive
                        }">

                    <!-- Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Video / Photo Area -->
                            <div
                                class="w-16 h-16 rounded-full bg-gray-100 overflow-hidden border border-gray-200 relative shrink-0">
                                <!-- Static Photo -->
                                <div x-show="!streaming" class="w-full h-full">
                                    <template x-if="student.photo_url">
                                        <img :src="student.photo_url" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!student.photo_url">
                                        <div class="w-full h-full flex items-center justify-center text-gray-500 font-bold text-xl"
                                            x-text="student.student_name.charAt(0)"></div>
                                    </template>
                                </div>

                                <!-- Live Video Mini Preview (Optional, or just indicator) -->
                                <div x-show="streaming"
                                    class="absolute inset-0 bg-black flex items-center justify-center">
                                    <i class="bi bi-camera-video-fill text-white animate-pulse"></i>
                                </div>
                            </div>

                                <div>
                                    <div class="font-bold text-gray-800 text-lg truncate w-48"
                                        x-text="student.student_name"></div>
                                    <div class="text-sm text-gray-500" x-text="student.admission_number"></div>
                                    <div class="text-xs font-mono mt-1 text-gray-400">
                                        Attempt: <span x-text="student.attempt_id ?? 'Not started'"></span>
                                    </div>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase"
                                :class="{
                                    'bg-green-100 text-green-700': student.status === 'in_progress',
                                    'bg-blue-100 text-blue-700': student.status === 'submitted',
                                    'bg-red-100 text-red-700': student.status === 'terminated' || student
                                        .status === 'expired',
                                    'bg-gray-100 text-gray-700': student.status === 'not_started'
                                }"
                                x-text="student.status.replace('_', ' ')"></span>
                        </div>

                    <!-- Live Camera Feed Area -->
                    <div x-show="streaming" x-transition
                        class="relative w-full bg-black rounded-lg overflow-hidden aspect-video border border-gray-800">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-contain"
                            x-show="!showScreen"></video>
                        <video x-ref="screen" autoplay playsinline class="w-full h-full object-contain"
                            x-show="showScreen"></video>
                        <audio x-ref="audio" autoplay playsinline></audio>

                        <div x-show="loading"
                            class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs">
                            <i class="bi bi-arrow-repeat animate-spin mr-2"></i> Connecting...
                        </div>

                            <button @click="toggleCamera(student.attempt_id)"
                                class="absolute top-2 right-2 bg-red-600/80 hover:bg-red-600 text-white p-1 rounded text-xs">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <div class="absolute bottom-2 left-2" x-show="hasScreen">
                                <button @click="showScreen = !showScreen"
                                    class="bg-gray-800/80 hover:bg-gray-700 text-white text-xs px-2 py-1 rounded flex items-center gap-1">
                                    <i class="bi" :class="showScreen ? 'bi-person-video' : 'bi-display'"></i> <span
                                        x-text="showScreen ? 'Show Camera' : 'Show Screen'"></span>
                                </button>
                            </div>
                        </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="text-gray-500">Violations</div>
                            <div class="font-bold text-lg"
                                :class="student.violation_count > 0 ? 'text-red-600' : 'text-gray-800'"
                                x-text="student.violation_count"></div>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <div class="text-gray-500">Time Left</div>
                            <div class="font-bold text-lg text-gray-800" x-text="formatTime(student.remaining_seconds)">
                            </div>
                        </div>
                    </div>

                    <!-- Status Text -->
                    <div class="text-xs text-center h-4">
                        <span x-show="student.is_idle" class="text-yellow-600 font-bold"><i
                                class="bi bi-exclamation-circle"></i> Idle for > 30s</span>
                        <span x-show="student.status === 'terminated'" class="text-red-600 truncate"
                            x-text="student.terminated_reason"></span>
                    </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-3 gap-3 mt-auto">
                            <button @click="toggleCamera(student.attempt_id)"
                                class="col-span-1 py-2 rounded text-sm font-medium transition-colors flex items-center justify-center gap-1"
                                :disabled="!student.attempt_id"
                                :class="streaming ? 'bg-gray-800 text-white hover:bg-gray-900' :
                                    (student.attempt_id ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed')">
                                <i class="bi" :class="streaming ? 'bi-eye-slash' : 'bi-camera-video'"></i>
                                <span x-text="streaming ? 'Close' : (student.attempt_id ? 'Watch' : 'Awaiting')"></span>
                            </button>
                            <button @click="extendTime(student.attempt_id)"
                                :disabled="!student.attempt_id"
                                :class="student.attempt_id ? 'bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-100' : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'"
                                class="col-span-1 py-2 border rounded text-sm font-medium">
                                +5 Min
                            </button>
                            <button @click="terminate(student.attempt_id)"
                                :disabled="!student.attempt_id"
                                :class="student.attempt_id ? 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'"
                                class="col-span-1 py-2 border rounded text-sm font-medium">
                                Stop
                            </button>
                        </div>
                    </div>
                </template>

        </div>
    </div>

</div>

<script>
    function monitorApp() {
            return {
                students: [],
                selectedAudioStudent: null,

                init() {
                    this.fetchData();
                    this.pollInterval = setInterval(() => this.fetchData(), 5000);
                },

                fetchData() {
                    fetch('{{ route('superadmin.exams.monitor.data', $exam->id) }}')
                        .then(res => res.json())
                        .then(data => {
                            this.students = data.attempts;
                        });
                },

                formatTime(seconds) {
                    const h = Math.floor(seconds / 3600);
                    const m = Math.floor((seconds % 3600) / 60);
                    const s = Math.floor(seconds % 60);
                    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },

                terminate(id) {
                    if (!id) return;
                    if (!confirm('Are you sure you want to force terminate this exam?')) return;

                    fetch(`/superadmin/attempts/${id}/terminate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                reason: 'SuperAdmin Manual Action'
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'terminated') {
                                alert('Exam terminated successfully.');
                                this.fetchData();
                            } else {
                                alert('Error: ' + (data.error || 'Unknown'));
                            }
                        });
                },

                extendTime(id) {
                    if (!id) return;
                    fetch(`/superadmin/attempts/${id}/extend`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                minutes: 5
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('Added 5 minutes.');
                            this.fetchData();
                        });
                }
            }
        }

        function studentCard(studentId) {
        function studentCard(studentId) {
            return {
                studentId: studentId,
                studentId: studentId,
                streaming: false,
                loading: false,
                pc: null,
                iceInterval: null,
                streamId: null,
                viewerSessionId: null,
                showScreen: false,
                hasScreen: false,
                audioContext: null,
                analyser: null,
                audioActive: false,
                audioCheckInterval: null,
                audioStarted: false,
                async toggleCamera(studentId) {
                    if (!studentId) return;
                    if (this.streaming) {
                        this.stopCamera();
                    } else {
                        await this.startCamera(studentId);
                    }
                },

                async startCamera(studentId) {
                    this.streaming = true;
                    this.loading = true;
                    this.showScreen = false;
                    this.hasScreen = false;
                    this.viewerSessionId =
                        `superadmin_${studentId}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

                    if (this.pc) this.pc.close();

                    const config = {
                        iceServers: [{
                            urls: 'stun:stun.l.google.com:19302'
                        }]
                    };
                    this.pc = new RTCPeerConnection(config);

                    this.pc.onicecandidate = (event) => {
                        if (event.candidate) {
                            fetch(`/superadmin/stream/${this.streamId}/signal`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    type: 'viewer_ice',
                                    payload: JSON.stringify(event.candidate)
                                })
                            });
                        }
                    };

                     this.pc.ontrack = (event) => {

                        const track = event.track;

                        // VIDEO TRACK
                        if (track.kind === "video") {

                            const videoStream = new MediaStream([track]);

                            if (!this.$refs.video.srcObject) {
                                this.$refs.video.srcObject = videoStream;
                            } else {
                                this.$refs.screen.srcObject = videoStream;
                                this.hasScreen = true;
                            }

                        }

                        // AUDIO TRACK
                        if (track.kind === "audio") {

                            console.log("Audio track received");

                            const audioStream = new MediaStream([track]);

                            this.$refs.audio.srcObject = audioStream;

                            this.$refs.audio.muted = false;
                            const app = Alpine.store('monitor');
                            this.$refs.audio.volume = (app.selectedAudioStudent === studentId) ? 1 : 0;   
                            this.$refs.audio.play().catch(e => console.log("Audio play blocked", e));

                            if (!this.audioStarted) {
                                this.audioStarted = true;
                                this.setupAudioDetection(audioStream,studentId);
                            }
                        }

                        this.loading = false;
                    };

                    try {
                        // 1. Request a stream
                        const reqRes = await fetch(`/superadmin/attempts/${studentId}/request-stream`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                session_id: this.viewerSessionId
                            })
                        });
                        const reqData = await reqRes.json();
                        this.streamId = reqData.stream_id;

                        // 2. Poll for offer and ICE
                        this.iceInterval = setInterval(async () => {
                            if (!this.streaming || !this.streamId) return;

                            const pollRes = await fetch(`/superadmin/stream/${this.streamId}/poll`);
                            const pollData = await pollRes.json();

                            // Handle offer
                            if (pollData.offer && !this.pc.currentRemoteDescription) {
                                await this.pc.setRemoteDescription(new RTCSessionDescription(JSON.parse(
                                    pollData.offer)));
                                const answer = await this.pc.createAnswer();
                                await this.pc.setLocalDescription(answer);

                                await fetch(`/superadmin/stream/${this.streamId}/signal`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        type: 'answer',
                                        payload: JSON.stringify(this.pc.localDescription)
                                    })
                                });
                            }

                            // Handle student ICE
                            if (pollData.ice_candidates) {
                                pollData.ice_candidates.split('||').forEach(c => {
                                    if (c) {
                                        this.pc.addIceCandidate(new RTCIceCandidate(JSON.parse(c)))
                                            .catch(e => console.error("ICE add error", e));
                                    }
                                });
                            }
                        }, 3000);

                    } catch (e) {
                        console.error("Start camera error:", e);
                        this.stopCamera();
                    }
                },
                setupAudioDetection(stream,studentId) {

                    try {

                        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();

                        const source = this.audioContext.createMediaStreamSource(stream);

                        this.analyser = this.audioContext.createAnalyser();
                        this.analyser.fftSize = 1024;

                        source.connect(this.analyser);

                        const dataArray = new Uint8Array(this.analyser.frequencyBinCount);

                        this.audioCheckInterval = setInterval(() => {

                            this.analyser.getByteTimeDomainData(dataArray);

                            let sum = 0;
                                for (let i = 0; i < dataArray.length; i++) {
                                const value = dataArray[i] - 128;
                                sum += Math.abs(value);
                            }

                            const average = sum / dataArray.length;

                            this.audioActive = average > 10;
                            const app = Alpine.store('monitor');
                            if (this.$refs.audio) {
                                this.$refs.audio.volume = (app.selectedAudioStudent === studentId) ? 1 : 0;
                            }
                            if (this.audioActive) {
                                console.log("Student speaking detected. Level:", average);
                            }
                    }, 200);

                            } catch (e) {
                                console.error("Audio detection error:", e);
                            }

                    },

                stopCamera() {
                    this.streaming = false;
                    this.loading = false;
                    this.$refs.video.srcObject = null;
                    this.$refs.screen.srcObject = null;
                    if (this.iceInterval) clearInterval(this.iceInterval);
                    this.iceInterval = null;
                    this.streamId = null;
                    if (this.pc) {
                        this.pc.close();
                        this.pc = null;
                    }
                },
                listenToStudent() {

                    const store = Alpine.store('monitor');

                    if (store.selectedAudioStudent === this.studentId) {
                        store.selectedAudioStudent = null;
                        this.$refs.audio.volume = 0;
                    } else {
                        store.selectedAudioStudent = this.studentId;
                        this.$refs.audio.volume = 1;
                    }
                }
            }
        }
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('monitor', {
            selectedAudioStudent: null
        });
    });
</script>
@endsection