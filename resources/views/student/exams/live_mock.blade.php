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

    <!-- Finish Confirmation Modal -->
    <div x-show="showFinishConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform transition-all scale-100" @click.away="showFinishConfirmation = false">
            <div class="mb-5">
                <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto text-indigo-600 text-3xl">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Submit Exam?</h3>
            <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                Are you sure you want to finish the mock exam?
            </p>
            
            <div class="flex gap-3">
                <button @click="showFinishConfirmation = false" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 hover:text-gray-800 transition text-sm">Cancel</button>
                <button @click="confirmFinish()" class="flex-1 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 text-sm">Yes, Submit</button>
            </div>
        </div>
    </div>

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
    <header class="shrink-0 border-b border-gray-200 bg-white px-4 py-3 z-20 sm:px-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0 flex items-center gap-3">
            <button @click="paletteOpen = true" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-700 shadow-sm lg:hidden">
                <i class="bi bi-grid"></i>
            </button>
            <div class="min-w-0">
                <div class="truncate font-bold text-base text-gray-800 sm:text-lg">
                    {{ $exam->title }} <span class="ml-2 rounded-full bg-yellow-100 px-2.5 py-1 text-[11px] text-yellow-800 align-middle">MOCK</span>
                </div>
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
                                   :class="getOptionClass(option.id)">
                                <input type="radio" :name="'q_' + currentQuestion.id" :value="option.id" :disabled="reviewMode"
                                       x-model="answers[currentQuestion.id]" class="mt-1 h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500 sm:mt-0">
                                <span class="ml-3 text-sm text-gray-700 sm:text-base" x-text="option.text"></span>
                                
                                <!-- Review Indicators -->
                                <span x-show="reviewMode && option.id === currentQuestion.correct_option" class="ml-auto pl-3 text-xs font-bold text-green-600 sm:text-sm"><i class="bi bi-check-circle-fill"></i> Correct</span>
                                <span x-show="reviewMode && answers[currentQuestion.id] === option.id && option.id !== currentQuestion.correct_option" class="ml-auto pl-3 text-xs font-bold text-red-600 sm:text-sm"><i class="bi bi-x-circle-fill"></i> Your Answer</span>
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

                    <button x-show="currentIndex === questions.length - 1 && !reviewMode" @click="finishMockExam()"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 sm:text-base">
                        Finish Mock Exam <i class="bi bi-check-lg"></i>
                    </button>
                    <a x-show="reviewMode" href="{{ route('student.exams.mock') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-700 sm:text-base">
                        Exit Review <i class="bi bi-box-arrow-right"></i>
                    </a>
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
                showResult: false,
                showFinishConfirmation: false,
                reviewMode: false,
                score: 0,
                totalMarks: 0,
                correctCount: 0,
                paletteOpen: false,
                
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
                    if (force) {
                        this.processFinish();
                    } else {
                        this.showFinishConfirmation = true;
                    }
                },

                confirmFinish() {
                    this.showFinishConfirmation = false;
                    this.processFinish();
                },

                processFinish() {
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
