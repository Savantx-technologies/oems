<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exam->title }} | Mock Exam</title>
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

    <!-- Result Modal -->
    <div x-show="showResult" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 text-center transform transition-all scale-100">
            <div class="mb-4">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto text-blue-600 text-4xl">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Mock Exam Completed!</h2>
            <p class="text-gray-500 mb-6">Here is how you performed</p>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Score</div>
                    <div class="text-2xl font-bold text-gray-900"><span x-text="score"></span> / <span x-text="totalMarks"></span></div>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <div class="text-sm text-gray-500">Correct</div>
                    <div class="text-2xl font-bold text-green-600"><span x-text="correctCount"></span> / <span x-text="questions.length"></span></div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('student.exams.mock') }}" class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">Exit</a>
                <button @click="showResult = false; reviewMode = true; currentIndex = 0" class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">Review Answers</button>
            </div>
        </div>
    </div>

    <div class="flex flex-col h-full w-full">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 shrink-0 z-20">
        <div class="flex items-center gap-4">
            <div class="font-bold text-lg text-gray-800">{{ $exam->title }} <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded ml-2">MOCK</span></div>
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
                                   :class="getOptionClass(option.id)">
                                <input type="radio" :name="'q_' + currentQuestion.id" :value="option.id" :disabled="reviewMode"
                                       x-model="answers[currentQuestion.id]" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700" x-text="option.text"></span>
                                
                                <!-- Review Indicators -->
                                <span x-show="reviewMode && option.id === currentQuestion.correct_option" class="ml-auto text-green-600 font-bold text-sm"><i class="bi bi-check-circle-fill"></i> Correct</span>
                                <span x-show="reviewMode && answers[currentQuestion.id] === option.id && option.id !== currentQuestion.correct_option" class="ml-auto text-red-600 font-bold text-sm"><i class="bi bi-x-circle-fill"></i> Your Answer</span>
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

                <button x-show="currentIndex === questions.length - 1 && !reviewMode" @click="finishMockExam()"
                        class="px-6 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 flex items-center gap-2">
                    Finish Mock Exam <i class="bi bi-check-lg"></i>
                </button>
                <a x-show="reviewMode" href="{{ route('student.exams.mock') }}" class="px-6 py-2 rounded-lg bg-gray-600 text-white font-medium hover:bg-gray-700 flex items-center gap-2">
                    Exit Review <i class="bi bi-box-arrow-right"></i>
                </a>
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
                showResult: false,
                reviewMode: false,
                score: 0,
                totalMarks: 0,
                correctCount: 0,
                
                get currentQuestion() {
                    return this.questions[this.currentIndex];
                },

                init() {
                    this.initTimer();
                    this.initHeartbeat();
                },

                initTimer() {
                    setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        } else {
                            this.finishMockExam(true);
                        }
                    }, 1000);
                },
                
                initHeartbeat() {
                    setInterval(() => {
                        fetch('{{ route("student.exams.heartbeat", $exam->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ session_token: this.sessionToken })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'terminated' || data.status === 'expired' || data.status === 'submitted') {
                                window.location.href = '{{ route("student.exams.mock") }}';
                            }
                            if (data.remaining_seconds !== undefined) {
                                if (Math.abs(this.remainingSeconds - data.remaining_seconds) > 5) {
                                    this.remainingSeconds = data.remaining_seconds;
                                }
                            }
                        });
                    }, 15000); // 15 seconds
                },

                finishMockExam(force = false) {
                    if (!force && !confirm('Are you sure you want to finish the mock exam?')) return;
                    
                    this.score = 0;
                    this.totalMarks = 0;
                    this.correctCount = 0;

                    this.questions.forEach(q => {
                        this.totalMarks += q.marks;
                        if (this.answers[q.id] === q.correct_option) {
                            this.score += q.marks;
                            this.correctCount++;
                        }
                    });

                    this.showResult = true;
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
                    if (this.reviewMode) {
                        const q = this.questions[index];
                        const userAnswer = this.answers[id];
                        if (userAnswer === q.correct_option) return 'bg-green-500 text-white border-green-600';
                        if (userAnswer && userAnswer !== q.correct_option) return 'bg-red-500 text-white border-red-600';
                        return 'bg-gray-200 text-gray-500 border-gray-300';
                    }

                    if (this.currentIndex === index) return 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-200';
                    if (this.answers[id]) return 'bg-green-500 text-white border-green-600';
                    if (this.reviewList.includes(id)) return 'bg-yellow-50 text-yellow-700 border-yellow-400';
                    return 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                },

                getOptionClass(optionId) {
                    if (this.reviewMode) {
                        if (optionId === this.currentQuestion.correct_option) return 'bg-green-50 border-green-500 ring-1 ring-green-500';
                        if (this.answers[this.currentQuestion.id] === optionId) return 'bg-red-50 border-red-500 ring-1 ring-red-500';
                    }
                    return this.answers[this.currentQuestion.id] === optionId ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200';
                }
            }
        }
    </script>
</body>
</html>
