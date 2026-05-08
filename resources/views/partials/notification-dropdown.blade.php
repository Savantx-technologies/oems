<div class="relative" x-data="{
    open: false,
    unreadCount: {{ $unreadCount ?? 0 }},
    previousCount: {{ $unreadCount ?? 0 }},
    saveUrl: '{{ $soundPreferenceUpdateUrl }}',
    csrfToken: '{{ csrf_token() }}',
    selectedTone: '{{ $soundPreference['tone'] ?? 'chime' }}',
    customSoundName: @js($soundPreference['custom_sound_name'] ?? null),
    customSoundUrl: @js($soundPreference['custom_sound_url'] ?? null),
    savingTone: false,
    init() {
        setInterval(() => this.checkNotifications(), {{ $refreshInterval ?? 10000 }});
    },
    checkNotifications() {
        fetch('{{ $unreadCountRoute }}')
            .then(res => res.json())
            .then(data => {
                const nextCount = Number(data.count || 0);
                if (nextCount > this.previousCount) {
                    this.playTone();
                }
                this.previousCount = nextCount;
                this.unreadCount = nextCount;
            })
            .catch(err => console.error(err));
    },
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
}" x-init="init()" @click.away="open = false">
    <button type="button" class="text-gray-500 hover:text-gray-700 relative block" @click="open = !open">
        <i class="bi bi-bell text-xl"></i>
        <span x-show="unreadCount > 0" x-text="unreadCount" x-cloak class="absolute -top-1 -right-1 flex items-center justify-center min-w-[16px] h-4 px-1 bg-red-500 text-white rounded-full text-[10px]"></span>
    </button>

    <div x-show="open" x-cloak x-transition
        class="fixed inset-x-3 top-16 z-50 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl sm:absolute sm:right-0 sm:top-auto sm:inset-x-auto sm:mt-3 sm:w-80 sm:max-w-[calc(100vw-2rem)]">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-900">Notifications</p>
                <p class="text-xs text-gray-500">Recent updates</p>
            </div>
            <span x-show="unreadCount > 0" x-text="`${unreadCount} new`" x-cloak class="rounded-full bg-red-50 px-2 py-1 text-[11px] font-semibold text-red-600"></span>
        </div>

        <div class="max-h-[52vh] overflow-y-auto sm:max-h-80">
            @forelse(($notifications ?? collect()) as $notification)
                <a href="{{ $notification['url'] }}" class="block border-b border-gray-100 px-4 py-3 transition hover:bg-gray-50 {{ $notification['is_read'] ? 'bg-white' : 'bg-blue-50/50' }}">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $notification['is_read'] ? 'bg-gray-300' : 'bg-blue-500' }}"></span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <p class="truncate text-sm font-semibold text-gray-900">{{ $notification['title'] }}</p>
                                <span class="flex-shrink-0 text-[11px] text-gray-400">{{ $notification['time'] }}</span>
                            </div>
                            <p class="mt-1 max-h-10 overflow-hidden text-xs leading-5 text-gray-600">{{ $notification['message'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <i class="bi bi-bell-slash text-2xl text-gray-300"></i>
                    <p class="mt-2 text-sm font-medium text-gray-700">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-gray-100 bg-gray-50 px-4 py-3 space-y-3">
            <div class="rounded-xl border border-gray-200 bg-white p-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-gray-900">Notification ringtone</p>
                        <p class="text-[11px] text-gray-500">Saved to your account</p>
                    </div>
                    <button type="button" @click="playTone()" class="inline-flex shrink-0 items-center rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-play-fill mr-1"></i> Test
                    </button>
                </div>
                <select x-model="selectedTone" @change="saveTone()" :disabled="savingTone" class="mt-3 w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="chime">Chime</option>
                    <option value="alert">Alert</option>
                    <option value="bell">Bell</option>
                    <option value="pop">Pop</option>
                    <option value="custom">Custom upload</option>
                    <option value="silent">Silent</option>
                </select>
                <label class="mt-3 flex cursor-pointer items-center justify-center rounded-lg border border-dashed border-gray-300 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">
                    <i class="bi bi-upload mr-2"></i> Upload short audio
                    <input type="file" class="hidden" accept="audio/*" @change="uploadCustomTone($event)">
                </label>
                <div class="mt-2 flex items-center justify-between text-[11px] text-gray-500" x-show="customSoundName" x-cloak>
                    <span class="truncate pr-3" x-text="customSoundName"></span>
                    <button type="button" @click="removeCustomTone()" class="font-medium text-red-600 hover:text-red-700">Remove</button>
                </div>
            </div>

            <a href="{{ $allNotificationsUrl }}" class="block rounded-xl bg-gray-900 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-gray-800">
                All notifications
            </a>
        </div>
    </div>
</div>

