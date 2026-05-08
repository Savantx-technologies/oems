<div x-data="{
    saveUrl: '{{ $soundPreferenceUpdateUrl }}',
    csrfToken: '{{ csrf_token() }}',
    selectedTone: '{{ $soundPreference['tone'] ?? 'chime' }}',
    customSoundName: @js($soundPreference['custom_sound_name'] ?? null),
    customSoundUrl: @js($soundPreference['custom_sound_url'] ?? null),
    savingTone: false,
    async persistTone(extraFields = {}) {
        this.savingTone = true;
        const formData = new FormData();
        formData.append('_token', this.csrfToken);
        formData.append('tone', extraFields.tone ?? this.selectedTone);
        if (extraFields.removeCustomSound) {
            formData.append('remove_custom_sound', '1');
        }
        if (extraFields.customFile) {
            formData.append('custom_sound', extraFields.customFile);
        }
        const response = await fetch(this.saveUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });
        const data = await response.json().catch(() => ({}));
        this.savingTone = false;
        if (!response.ok) {
            alert(data.message || 'Unable to save ringtone preference.');
            throw new Error(data.message || 'Unable to save ringtone preference.');
        }
        this.selectedTone = data.preference?.tone || 'chime';
        this.customSoundName = data.preference?.custom_sound_name || null;
        this.customSoundUrl = data.preference?.custom_sound_url || null;
    },
    async saveTone() {
        if (this.selectedTone === 'custom' && !this.customSoundUrl) {
            alert('Upload a custom ringtone first.');
            this.selectedTone = 'chime';
            return;
        }
        await this.persistTone();
    },
    async uploadCustomTone(event) {
        const file = event.target.files?.[0];
        if (!file) return;
        if (file.size > 512000) {
            alert('Please upload a short audio file under 500 KB.');
            event.target.value = '';
            return;
        }
        try {
            await this.persistTone({ tone: 'custom', customFile: file });
        } finally {
            event.target.value = '';
        }
    },
    async removeCustomTone() {
        const fallbackTone = this.selectedTone === 'custom' ? 'chime' : this.selectedTone;
        await this.persistTone({ tone: fallbackTone, removeCustomSound: true });
    },
    async playTone() {
        if (this.selectedTone === 'silent') {
            return;
        }
        if (this.selectedTone === 'custom') {
            if (!this.customSoundUrl) {
                alert('Upload a custom ringtone first.');
                return;
            }
            const audio = new Audio(this.customSoundUrl);
            audio.volume = 0.9;
            await audio.play().catch(() => {});
            return;
        }
        const context = new (window.AudioContext || window.webkitAudioContext)();
        const now = context.currentTime;
        const presets = {
            chime: {
                type: 'sine',
                notes: [
                    { frequency: 523.25, duration: 0.18, delay: 0, volume: 0.06 },
                    { frequency: 659.25, duration: 0.2, delay: 0.12, volume: 0.07 },
                    { frequency: 783.99, duration: 0.24, delay: 0.24, volume: 0.08 },
                ],
            },
            alert: {
                type: 'square',
                notes: [
                    { frequency: 880.0, duration: 0.09, delay: 0, volume: 0.05 },
                    { frequency: 880.0, duration: 0.09, delay: 0.14, volume: 0.05 },
                    { frequency: 659.25, duration: 0.14, delay: 0.28, volume: 0.06 },
                ],
            },
            bell: {
                type: 'triangle',
                notes: [
                    { frequency: 392.0, duration: 0.3, delay: 0, volume: 0.05 },
                    { frequency: 523.25, duration: 0.26, delay: 0.16, volume: 0.045 },
                    { frequency: 659.25, duration: 0.22, delay: 0.3, volume: 0.04 },
                ],
            },
            pop: {
                type: 'sawtooth',
                notes: [
                    { frequency: 330.0, duration: 0.06, delay: 0, volume: 0.035 },
                    { frequency: 495.0, duration: 0.06, delay: 0.08, volume: 0.04 },
                    { frequency: 660.0, duration: 0.08, delay: 0.16, volume: 0.045 },
                ],
            },
        };
        const preset = presets[this.selectedTone] || presets.chime;
        preset.notes.forEach((note) => {
            const oscillator = context.createOscillator();
            const gain = context.createGain();
            const startTime = now + note.delay;
            oscillator.type = preset.type;
            oscillator.frequency.value = note.frequency;
            gain.gain.setValueAtTime(0.0001, startTime);
            gain.gain.exponentialRampToValueAtTime(note.volume, startTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, startTime + note.duration);
            oscillator.connect(gain);
            gain.connect(context.destination);
            oscillator.start(startTime);
            oscillator.stop(startTime + note.duration + 0.02);
        });
    }
}" class="mx-auto max-w-7xl">
    <div class="mb-8 grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-900 to-blue-700 p-6 text-white shadow-xl">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Notification Center</p>
                    <h1 class="mt-2 text-3xl font-extrabold">Stay on top of every alert</h1>
                    <p class="mt-2 max-w-2xl text-sm text-blue-100/85">{{ $description }}</p>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ $markAllRoute }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/25">
                            <i class="bi bi-check2-all text-base"></i>
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-gray-900">Notification ringtone</p>
                    <p class="mt-1 text-xs text-gray-500">Saved to your account and available everywhere.</p>
                </div>
                <button type="button" @click="playTone()" class="inline-flex items-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-play-fill mr-1"></i> Test
                </button>
            </div>
            <select x-model="selectedTone" @change="saveTone()" :disabled="savingTone" class="mt-4 w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="chime">Chime</option>
                <option value="alert">Alert</option>
                <option value="bell">Bell</option>
                <option value="pop">Pop</option>
                <option value="custom">Custom upload</option>
                <option value="silent">Silent</option>
            </select>
            <label class="mt-3 flex cursor-pointer items-center justify-center rounded-xl border border-dashed border-gray-300 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">
                <i class="bi bi-upload mr-2"></i> Upload short audio
                <input type="file" class="hidden" accept="audio/*" @change="uploadCustomTone($event)">
            </label>
            <div class="mt-2 flex items-center justify-between gap-2 text-[11px] text-gray-500" x-show="customSoundName" x-cloak>
                <span class="truncate pr-3" x-text="customSoundName"></span>
                <button type="button" @click="removeCustomTone()" class="font-medium text-red-600 hover:text-red-700">Remove</button>
            </div>
        </div>
    </div>

    <div class="{{ $containerClass ?? 'bg-white rounded-3xl shadow-sm border border-gray-200' }} overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $notificationData = is_array($notification->data) ? $notification->data : [];
                $title = $notification->title ?? $notificationData['title'] ?? 'Notification';
                $message = $notification->message ?? $notificationData['message'] ?? 'You have a new notification.';
                $isStudentExam = $panel === 'student' && $notification->type === 'exam';
                $isSuperAdminExam = $panel === 'superadmin' && $notification->type === 'exam_created';
                $isAdminViolation = $panel === 'admin' && $notification->type === 'violation';
                $isAdminExam = $panel === 'admin' && $notification->type === 'exam_published';
                $examId = $notificationData['exam_id'] ?? null;
                $exam = in_array($panel, ['student', 'superadmin'], true) ? ($exams[$examId] ?? null) : null;

                $iconClass = 'bi-bell';
                $iconWrapperClass = $notification->is_read ? 'bg-gray-200 text-gray-400' : 'bg-indigo-100 text-indigo-600';

                if ($isAdminViolation) {
                    $iconClass = 'bi-exclamation-triangle-fill';
                    $iconWrapperClass = $notification->is_read ? 'bg-gray-200 text-gray-400' : 'bg-red-100 text-red-600';
                } elseif ($isAdminExam) {
                    $iconClass = 'bi-check-circle-fill';
                } elseif ($isStudentExam) {
                    $iconClass = 'bi-calendar-event';
                } elseif ($isSuperAdminExam) {
                    $iconClass = 'bi-file-earmark-plus';
                }
            @endphp
            <div class="flex flex-col gap-5 border-b border-gray-100 px-4 py-5 transition-colors last:border-b-0 sm:px-6 sm:py-6 {{ $notification->is_read ? 'bg-gray-50/60' : 'bg-white hover:bg-indigo-50/30' }} lg:flex-row lg:items-start">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl shadow-sm sm:h-14 sm:w-14 {{ $iconWrapperClass }}">
                        <i class="bi {{ $iconClass }} text-2xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-semibold {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }}">{{ $title }}</h3>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $notification->is_read ? 'bg-gray-200 text-gray-600' : 'bg-emerald-50 text-emerald-700' }}">{{ $notification->is_read ? 'Read' : 'Unread' }}</span>
                                </div>
                                <div class="mt-3 text-sm leading-relaxed {{ $notification->is_read ? 'text-gray-500' : 'text-gray-700' }}">
                                    @if($exam)
                                        <span class="block mb-1 font-medium text-indigo-600"><i class="bi bi-journal-text mr-1"></i> Subject: {{ $exam->subject }}</span>
                                        <span class="block"><i class="bi bi-hourglass-split mr-1"></i> Duration: <b>{{ $exam->duration_minutes }}</b> min(s)</span>
                                        @if($exam->schedule)
                                            <span class="mt-1 block text-[13px] text-gray-500"><i class="bi bi-calendar-week mr-1"></i> Scheduled: <b>{{ $exam->schedule->start_at->format('M d, h:i A') }}</b> - <b>{{ $exam->schedule->end_at->format('M d, h:i A') }}</b></span>
                                        @endif
                                    @elseif($panel === 'superadmin')
                                        {!! $message !!}
                                    @else
                                        {{ $message }}
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-start gap-2 sm:items-end">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $notification->is_read ? 'bg-gray-200 text-gray-500' : 'bg-blue-100 text-blue-600' }}"><i class="bi bi-clock mr-1 text-[12px]"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->is_read)
                                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">Fresh alert</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                            @if($isStudentExam || $isSuperAdminExam)
                                <a href="{{ route($readRoute, $notification->id) }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"><i class="bi bi-box-arrow-up-right mr-2"></i> View Exam</a>
                            @elseif($isAdminViolation)
                                <a href="{{ route($readRoute, $notification->id) }}" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-red-700"><i class="bi bi-camera-video mr-2"></i> Monitor Exam</a>
                            @elseif($isAdminExam)
                                <a href="{{ route($readRoute, $notification->id) }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"><i class="bi bi-eye mr-2"></i> View Exam</a>
                            @endif
                            @if($notification->is_read)
                                <form action="{{ route($markSingleUnreadRoute, $notification->id) }}" method="POST" class="m-0">@csrf<button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-2 text-sm font-medium text-amber-800 transition hover:bg-amber-100 sm:w-auto"><i class="bi bi-arrow-counterclockwise mr-2"></i> Mark as unread</button></form>
                            @else
                                <form action="{{ route($markSingleReadRoute, $notification->id) }}" method="POST" class="m-0">@csrf<button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 sm:w-auto"><i class="bi {{ $panel === 'admin' ? 'bi-check2' : 'bi-eye' }} mr-2"></i> Mark as read</button></form>
                            @endif
                            <form action="{{ route($deleteRoute, $notification->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this notification?');">@csrf @method('DELETE')<button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3.5 py-2 text-sm font-medium text-red-700 transition hover:bg-red-100 sm:w-auto"><i class="bi bi-trash3 mr-2"></i> Delete</button></form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center text-gray-400 flex flex-col items-center gap-2">
                <i class="bi bi-bell-slash text-5xl block text-gray-300 mb-3"></i>
                <p class="text-lg font-medium">{{ $emptyTitle }}</p>
                @if(!empty($emptySubtitle))
                    <span class="text-xs text-gray-400">{{ $emptySubtitle }}</span>
                @endif
            </div>
        @endforelse
    </div>
    <div class="mt-8 flex justify-center">{{ $notifications->links($paginationView ?? 'pagination::tailwind') }}</div>
</div>

