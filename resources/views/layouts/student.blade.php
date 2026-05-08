<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Portal | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
    $school = auth()->user()?->school ?? (auth()->user()?->school_id ?
    \App\Models\School::find(auth()->user()->school_id) : null);
    @endphp

    @if($school?->logo)
    <link rel="icon" href="{{ asset('storage/' . $school->logo) }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <!-- Load Alpine.js before collapse plugin to avoid re-initialization/blink issues (fixes sidebar blink) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        function sidebarDropdown(key, isActive = false) {
            return {
                open: false,
                init() {
                    const savedState = localStorage.getItem(key);
                    this.open = savedState === null ? isActive : savedState === 'true';

                    if (isActive) {
                        this.open = true;
                    }

                    this.$watch('open', (value) => {
                        localStorage.setItem(key, value ? 'true' : 'false');
                    });
                }
            };
        }

        function studentLayout() {
            return {
                sidebarOpen: window.innerWidth >= 1024,
                isDesktop: window.innerWidth >= 1024,
                syncSidebarState() {
                    this.isDesktop = window.innerWidth >= 1024;
                    this.sidebarOpen = this.isDesktop;
                },
                init() {
                    this.syncSidebarState();
                    window.addEventListener('resize', () => this.syncSidebarState());
                }
            };
        }
    </script>

    <script>
        document.addEventListener('keydown', function(e) {
            // Block F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C, Ctrl+U
            if (
                e.key === 'F12' || e.keyCode === 123 ||
                (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) ||
                (e.ctrlKey && e.key.toUpperCase() === 'U')
            ) {
                e.preventDefault();
            }
        });
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Prevent sidebar blink when using x-cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .nav-link-active {
            color: #fff !important;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.22), rgba(59, 130, 246, 0.05));
            border-left-color: #60a5fa !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .student-shell {
            background:
                radial-gradient(circle at top, rgba(59, 130, 246, 0.12), transparent 28%),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
    </style>
</head>

<body class="student-shell h-full text-gray-800" x-data="studentLayout()" x-cloak
    oncontextmenu="return false">

    <!-- Sidebar Backdrop -->
    <div x-show="sidebarOpen && !isDesktop" @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-sm lg:hidden"
        x-cloak></div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 z-50 flex h-screen w-72 max-w-[86vw] flex-col overflow-hidden bg-slate-950 text-white shadow-2xl transition-transform duration-300 transform lg:w-64"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
        <div class="shrink-0 border-b border-white/10 bg-white/5 px-4 py-4 sm:px-5">
            <div class="flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-sm font-bold text-white ring-1 ring-white/10 sm:hidden">
                        {{ strtoupper(Str::substr($school?->name ?? 'SP', 0, 2)) }}
                    </div>
                    @if($school?->logo)
                    <div class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/95 p-2 shadow-sm sm:flex">
                        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo"
                            class="h-full w-full object-contain">
                    </div>
                    @endif
                    <div class="min-w-0">
                        <span
                            class="block truncate text-sm font-semibold text-white sm:text-base"
                            title="{{ $school?->name }}">
                            {{ Str::limit($school?->name ?? 'ExamPlatform', 24) }}
                        </span>
                        <span class="mt-0.5 block text-xs font-medium uppercase tracking-[0.2em] text-blue-200/80">
                            Student Portal
                        </span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="rounded-xl p-2 text-white/70 transition hover:bg-white/10 hover:text-white lg:hidden">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto sidebar-scroll px-3 py-3">
            <ul class="flex flex-col space-y-1">

                <!-- Dashboard -->
                <li
                    x-data="sidebarDropdown('student-dashboard', {{ request()->routeIs('student.dashboard') || request()->routeIs('student.elearning') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('student.dashboard') || request()->routeIs('student.elearning') ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-speedometer2 mr-2"></i> Dashboard</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="mt-1 rounded-2xl bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.dashboard') ? route('student.dashboard') : '#' }}">Overview</a>
                            </li>
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.elearning') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.elearning') ? route('student.elearning') : '#' }}">
                                    Study Materials
                                </a>
                            </li>
                            <!-- <li>
                                <a href="https://www.oems.saneoverseas.in/e-leaning" target="_blank" rel="noopener"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    style="white-space: nowrap;">
                                    E-Learning Content
                                    <i class="bi bi-box-arrow-up-right ml-1"></i>
                                </a>
                            </li> -->

                            <!-- <li>
                                <a href="{{ route('student.elearning') }}"
    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
    E-Learning Content
</a>
                            </li> -->
                        </ul>
                    </div>
                </li>

                <!-- My Exams -->
                <li x-data="sidebarDropdown('student-exams', {{ request()->routeIs('student.exams.*') ? 'true' : 'false' }})">
                    @php $isExamsActive = request()->routeIs('student.exams.*'); @endphp
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ $isExamsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-pencil-square mr-2"></i> Exams</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="mt-1 rounded-2xl bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.exams.index') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.exams.index') ? route('student.exams.index') : '#' }}">
                                    Upcoming Exams
                                </a>
                            </li>
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.exams.mock') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.exams.mock') ? route('student.exams.mock') : '#' }}">
                                    Mock Exams
                                </a>
                            </li>
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.exams.history') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.exams.history') ? route('student.exams.history') : '#' }}">
                                    Exam History
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Results & Certificates -->
                <li
                    x-data="sidebarDropdown('student-results', {{ request()->routeIs('student.results.*') || request()->routeIs('student.solutions.*') || request()->routeIs('student.certificates.*') ? 'true' : 'false' }})">
                    @php
                    $isResultsActive = request()->routeIs('student.results.*') ||
                    request()->routeIs('student.solutions.*') || request()->routeIs('student.certificates.*');
                    @endphp
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ $isResultsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-trophy mr-2"></i> Results </div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="mt-1 rounded-2xl bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block rounded-xl px-4 py-2.5 pl-11 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white {{ request()->routeIs('student.results.*') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ Route::has('student.results.index') ? route('student.results.index') : '#' }}">
                                    Results / Scorecard
                                </a>
                            </li>
                            <!-- <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.solutions.*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.solutions.index') ? route('student.solutions.index') : '#' }}">
                                    Solutions <span class="text-xs text-yellow-300 ml-1">(if allowed)</span>
                                </a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.certificates.*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.certificates.index') ? route('student.certificates.index') : '#' }}">
                                    Certificates (PDF)
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </li>

                <!-- System Check -->
                <li>
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('student.system.check') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.system.check') ? route('student.system.check') : '#' }}">
                        <div><i class="bi bi-pc-display-horizontal mr-2"></i> System Check</div>
                    </a>
                </li>

                <!-- Instructions -->
                <li>
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('student.instructions') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.instructions') ? route('student.instructions') : '#' }}">
                        <div><i class="bi bi-info-circle mr-2"></i> Instructions</div>
                    </a>
                </li>

                <!-- Notifications -->
                <li>
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('student.notifications') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.notifications') ? route('student.notifications') : '#' }}">
                        <div><i class="bi bi-bell mr-2"></i> Notifications</div>
                    </a>
                </li>

                <!-- Profile -->
                <li>
                    <a class="flex items-center justify-between rounded-2xl border-l-4 border-transparent px-4 py-3 text-sm font-medium text-slate-300 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('student.profile') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}">
                        <div><i class="bi bi-person-circle mr-2"></i> Profile</div>
                    </a>
                </li>

                <!-- Settings -->
                <!-- <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.settings') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.settings') ? route('student.settings') : '#' }}">
                        <div><i class="bi bi-gear mr-2"></i> Settings</div>
                    </a>
                </li> -->

            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col transition-all duration-300" :class="isDesktop ? 'lg:ml-64' : ''">
        <!-- Topbar -->
        <nav class="sticky top-0 z-30 border-b border-white/70 bg-white/85 px-4 py-3 shadow-sm backdrop-blur sm:px-6">
            <div class="flex items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <!-- Hamburger for mobile -->
                <button @click.stop="sidebarOpen = !sidebarOpen" class="rounded-xl border border-gray-200 bg-white p-2 text-gray-600 shadow-sm transition hover:bg-gray-50 focus:outline-none lg:hidden">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-indigo-500/80">Student Area</p>
                    <h5 class="truncate text-base font-semibold text-gray-800 sm:text-lg">
                        @yield('title')
                    </h5>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <div class="hidden text-right sm:block">
                    <small class="block text-gray-500 leading-tight">Welcome back,</small>
                    <span class="font-bold text-gray-800">{{ auth()->user()?->name ?? 'Student' }}</span>
                </div>
                @include('partials.notification-dropdown', [
                'notifications' => $notificationPreviewItems ?? collect(),
                'unreadCount' => $unreadNotificationsCount ?? 0,
                'unreadCountRoute' => route('student.notifications.unreadCount'),
                'allNotificationsUrl' => route('student.notifications'),
                'refreshInterval' => 10000,
                'soundPreference' => $notificationSoundPreference ?? ['tone' => 'chime', 'custom_sound_name' => null,
                'custom_sound_url' => null],
                'soundPreferenceUpdateUrl' => route('student.notifications.soundPreference.update'),
                ])
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100 shadow-sm focus:outline-none"
                        @click="open = !open" @click.away="open = false">
                        @if(auth()->user()?->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile"
                            class="w-full h-full object-cover">
                        @else
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                        @endif
                    </button>
                    <ul x-show="open" x-cloak
                        class="absolute right-0 mt-2 w-56 rounded-2xl border border-gray-100 bg-white py-2 shadow-lg z-50">
                        <li class="px-4 py-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800 block truncate">{{ auth()->user()?->name ?? 'Student'
                                }}</span>
                            <span class="text-xs text-gray-500 block truncate">{{ auth()->user()?->email ?? '' }}</span>
                        </li>
                        <li>
                            <a href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}"
                                class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-50">
                                <i class="bi bi-person mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form id="logout-form" method="POST"
                                action="{{ Route::has('student.logout') ? route('student.logout') : (Route::has('logout') ? route('logout') : '#') }}"
                                class="m-0">
                                @csrf
                                <button
                                    class="flex w-full items-center px-4 py-2 text-left text-sm text-red-600 transition-colors hover:bg-red-50"
                                    type="submit">
                                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="flex-1 px-4 py-5 sm:px-6 sm:py-6 md:px-8">
            @yield('content')
        </div>
    </div>

</body>

</html>
