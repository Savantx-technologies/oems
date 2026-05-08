<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>School Admin | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

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
            background: rgba(255, 255, 255, 0.05);
            border-left-color: #3b82f6 !important;
        }

        .admin-glass {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
        }

        .mobile-safe-scroll {
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>

<body class="bg-gray-50 h-full text-gray-900"
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        isDesktop: window.innerWidth >= 1024,
        init() {
            const syncLayout = () => {
                this.isDesktop = window.innerWidth >= 1024;
                if (this.isDesktop) {
                    this.sidebarOpen = true;
                }
            };

            syncLayout();
            window.addEventListener('resize', syncLayout);
        },
        closeSidebarOnMobile() {
            if (!this.isDesktop) {
                this.sidebarOpen = false;
            }
        }
    }" x-cloak>

    <div x-show="sidebarOpen && !isDesktop" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-[2px] lg:hidden"></div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 z-50 flex h-screen w-72 max-w-[88vw] flex-col bg-slate-950 text-white shadow-2xl transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="shrink-0 border-b border-white/10 bg-gradient-to-b from-white/8 to-transparent px-4 py-4 sm:px-5">
            <div class="flex items-center justify-between gap-3 lg:justify-start">
                <div class="flex min-w-0 items-center justify-start">
                @if(auth()->user()?->school?->logo)
                <div class="mr-3 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-[10px] font-semibold uppercase tracking-wide text-cyan-200 sm:hidden">
                    {{ strtoupper(substr(auth()->user()?->school?->name ?? 'EP', 0, 2)) }}
                </div>
                <img src="{{ asset('storage/' . auth()->user()->school->logo) }}" alt="School Logo"
                    class="mr-3 hidden h-11 w-11 shrink-0 rounded-xl border border-white/10 bg-white/90 object-contain p-1 shadow-sm sm:block">
                @else
                <div class="mr-3 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-[10px] font-semibold uppercase tracking-wide text-cyan-200 sm:h-11 sm:w-11 sm:text-xs">
                    {{ strtoupper(substr(auth()->user()?->school?->name ?? 'EP', 0, 2)) }}
                </div>
                @endif
                <div class="flex flex-col items-start min-w-0">
                    <span
                        class="mb-0.5 max-w-[145px] overflow-hidden text-ellipsis whitespace-nowrap text-sm font-semibold leading-tight text-white sm:max-w-[165px] sm:text-base"
                        title="{{ auth()->user()?->school?->name }}">
                        {{ Str::limit(auth()->user()?->school?->name ?? 'ExamPlatform Pro', 18) }}
                    </span>
                    <span class="text-[11px] font-medium uppercase tracking-[0.22em] text-cyan-300/85 sm:text-xs">
                        School Admin Panel
                    </span>
                </div>
                </div>
                <button type="button" @click="sidebarOpen = false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
        </div>
        <div class="mobile-safe-scroll flex-1 overflow-y-auto sidebar-scroll py-3">
            <ul class="flex flex-col space-y-1">
                @php
                $adminUser = auth('admin')->user();
                $questionOpen = request()->is('admin/questions*');
                $examOpen = request()->is('admin/exams*');
                $usersOpen = request()->is('admin/staff*') || request()->is('admin/students*') ||
                request()->is('admin/requests/staff*');
                $showDashboard = $adminUser?->canAccessSidebarSection('dashboard');
                $showUsersSection = $adminUser?->canAccessSidebarSection('users');
                $canManageStudents = $adminUser?->canAccessSidebarSection('admissions');
                $canManageStaffRequests = $showUsersSection && $adminUser?->canManageStaffRequests();
                $canManageQuestionBank = $adminUser?->canAccessSidebarSection('question_bank');
                $canManageExams = $adminUser?->canAccessSidebarSection('exams');
                $canMonitorExams = $adminUser?->canAccessSidebarSection('live_exams');
                $canViewPracticeDemo = $adminUser?->canAccessSidebarSection('practice_demo');
                $canElearning = $adminUser?->canAccessSidebarSection('elearning');
                $canViewEvaluation = $adminUser?->canAccessSidebarSection('evaluation');
                $canViewReports = $adminUser?->canAccessSidebarSection('reports');
                $canManageSettings = $adminUser?->canAccessSidebarSection('settings');
                $canViewLogs = $adminUser?->canAccessSidebarSection('logs');

                @endphp

                <!-- Dashboard -->
                @if($showDashboard)
                <li>
                    <a class="flex items-center justify-between border-l-4 border-transparent px-5 py-3 text-gray-400 transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}"
                        href="{{ route('admin.dashboard') }}" @click="closeSidebarOnMobile()">
                        <div><i class="bi bi-speedometer2 mr-2"></i> Dashboard</div>
                    </a>
                </li>
                @endif

                <!-- Admissions -->
                @if($canManageStudents)
                <li
                    x-data="sidebarDropdown('admin-admissions', {{ request()->routeIs('admin.students.create') || request()->routeIs('admin.students.bulk_create') || request()->routeIs('admin.students.batch.assign') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('admin.students.create') || request()->routeIs('admin.students.bulk_create') || request()->routeIs('admin.students.batch.assign') ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-person-plus mr-2"></i> Admissions</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.students.create') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.students.create') }}" @click="closeSidebarOnMobile()">New
                                    Admission</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.students.bulk_create') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.students.bulk_create') }}" @click="closeSidebarOnMobile()">Bulk Upload</a></li>

                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.students.batch.assign') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.students.batch.assign') }}" @click="closeSidebarOnMobile()">Batch Assignment</a></li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Users (School Only) -->
                @if($showUsersSection && ($canManageStudents || $canManageStaffRequests))
                <li x-data="sidebarDropdown('admin-users', {{ $usersOpen ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $usersOpen ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-people mr-2"></i> Users</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            @if($canManageStaffRequests)
                            <li>
                                <div class="px-5 py-2 text-gray-500 text-xs uppercase font-bold mt-2">Staff Management
                                </div>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.staff.index') || request()->routeIs('admin.staff.edit') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.staff.index') }}" @click="closeSidebarOnMobile()">Manage Staff</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.staff.create.*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.staff.create.step1') }}" @click="closeSidebarOnMobile()">Add Staff</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="{{ route('admin.requests.staff.create') }}" @click="closeSidebarOnMobile()">Request Reset /
                                    Block</a>
                            </li>
                            @endif
                            @if($canManageStudents)
                            <li>
                                <div class="px-5 py-2 text-gray-500 text-xs uppercase font-bold mt-2">Students</div>
                            </li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.students.index') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.students.index') }}" @click="closeSidebarOnMobile()">View
                                    Students</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Reset Attempt</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Request Block Student</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Question Bank -->
                @if($canManageQuestionBank)
                <li x-data="sidebarDropdown('admin-question-bank', {{ $questionOpen ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3
                        {{ $questionOpen ? 'text-white bg-white/10 border-l-4 border-indigo-500' : 'text-gray-400 border-l-4 border-transparent' }}
                        hover:bg-white/5 hover:text-white transition-colors" href="#" @click.prevent="open = !open">
                        <div>
                            <i class="bi bi-collection mr-2"></i>
                            Question Bank
                        </div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>

                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">

                            <li>
                                <a href="{{ route('admin.questions.index') }}" @click="closeSidebarOnMobile()" class="block px-5 py-2 pl-11 text-sm
                                {{ request()->routeIs('admin.questions.index')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    All Questions (MCQ)
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.questions.create') }}" @click="closeSidebarOnMobile()" class="block px-5 py-2 pl-11 text-sm
                                  {{ request()->routeIs('admin.questions.create')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Add New Question
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Exams -->
                @if($canManageExams)
                <li x-data="sidebarDropdown('admin-exams', {{ $examOpen ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3
                            {{ $examOpen ? 'text-white bg-white/10 border-l-4 border-indigo-500' : 'text-gray-400 border-l-4 border-transparent' }}
                            hover:bg-white/5 hover:text-white transition-colors" href="#"
                        @click.prevent="open = !open">
                        <div>
                            <i class="bi bi-file-earmark-text mr-2"></i>
                            Exams
                        </div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>

                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">

                            <li>
                                <a href="{{ route('admin.exams.create') }}" @click="closeSidebarOnMobile()" class="block px-5 py-2 pl-11 text-sm
                                    {{ request()->routeIs('admin.exams.create')
                                        ? 'text-white bg-white/10'
                                        : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Create Exam
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.exams.index') }}" @click="closeSidebarOnMobile()" class="block px-5 py-2 pl-11 text-sm
                                {{ request()->routeIs('admin.exams.index')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Manage Exams
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                @endif


                <!-- Live Exams -->
                @if($canMonitorExams)
                <li x-data="sidebarDropdown('admin-live-exams', {{ (request()->routeIs('admin.live-exams.index') || request()->routeIs('admin.exams.monitor') || request()->routeIs('admin.exams.monitor.data')) ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ (request()->routeIs('admin.live-exams.index') || request()->routeIs('admin.exams.monitor') || request()->routeIs('admin.exams.monitor.data')) ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-camera-video mr-2"></i> Live Exams</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a href="{{ route('admin.live-exams.index', ['filter' => 'live']) }}" @click="closeSidebarOnMobile()" class="block px-5 py-2 pl-11 text-sm 
                                        {{ request()->routeIs('admin.exams.monitor') || request()->routeIs('admin.live-exams.index')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Live Exam Monitor
                                </a>
                            </li>
                            <!-- <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Student Camera Grid</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Issue Warning</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Remove Student</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Extend Time</a></li> -->
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Practice / Demo Exams -->
                @if($canViewPracticeDemo)
                <li x-data="sidebarDropdown('admin-practice-demo', {{ (request()->routeIs('admin.exams.practice') || request()->routeIs('admin.exams.solution') || request()->routeIs('admin.exams.practice.solutions')) ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ (request()->routeIs('admin.exams.practice') || request()->routeIs('admin.exams.solution') || request()->routeIs('admin.exams.practice.solutions')) ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-laptop mr-2"></i> Practice / Demo</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a href="{{ route('admin.exams.practice') }}" @click="closeSidebarOnMobile()"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Practice Exam
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.exams.practice.solutions') }}" @click="closeSidebarOnMobile()"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Solution View
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>
                @endif
                <!-- Elearning -->
                @if(isset($canElearning) && $canElearning)
                <li x-data="sidebarDropdown('admin-elearning', {{ request()->routeIs('admin.elearning.*') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('admin.elearning.*') ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-laptop mr-2"></i> Elearning</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>

                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a href="{{ route('admin.elearning.create') }}" @click="closeSidebarOnMobile()"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Add Elearning
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Evaluation & Results -->
                @if($canViewEvaluation)
                <li x-data="sidebarDropdown('admin-evaluation', {{ (request()->routeIs('admin.results.*') || request()->routeIs('admin.results.attempts') || request()->routeIs('admin.results.view')) ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ (request()->routeIs('admin.results.*') || request()->routeIs('admin.results.attempts') || request()->routeIs('admin.results.view')) ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-check2-square mr-2"></i> Evaluation</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.results.*') ? 'bg-white/10 text-white' : '' }}"
                                    href="{{ route('admin.results.pending') }}" @click="closeSidebarOnMobile()">
                                    Auto Evaluation
                                </a>
                            </li>

                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="{{ route('admin.results.attempts') }}" @click="closeSidebarOnMobile()">
                                    Manual Checking
                                </a>
                            </li>
                            <!-- <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Scorecards</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Certificates</a></li> -->
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Reports -->
                @if($canViewReports)
                <li x-data="sidebarDropdown('admin-reports', {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('admin.reports.*') ? 'text-white bg-white/10 border-l-4 border-indigo-500' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-bar-chart mr-2"></i> Reports</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.reports.index') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.reports.index') }}" @click="closeSidebarOnMobile()">Overview</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.reports.exams') || request()->routeIs('admin.reports.exams.detail') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.reports.exams') }}" @click="closeSidebarOnMobile()">Exam Reports</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.reports.analytics') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.reports.analytics') }}" @click="closeSidebarOnMobile()">Performance Analytics</a></li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- School Settings -->
                @if($canManageSettings)
                <li
                    x-data="sidebarDropdown('admin-settings', {{ request()->routeIs('admin.settings.school') || request()->routeIs('admin.settings.exam_rules') || request()->routeIs('admin.settings.notifications') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('admin.settings.school') || request()->routeIs('admin.settings.exam_rules') || request()->routeIs('admin.settings.notifications') ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-gear mr-2"></i> Settings</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.settings.school') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.settings.school') }}" @click="closeSidebarOnMobile()">School Profile</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.settings.exam_rules') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.settings.exam_rules') }}" @click="closeSidebarOnMobile()">Exam Rules</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.settings.notifications') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.settings.notifications') }}" @click="closeSidebarOnMobile()">Notification Settings</a></li>
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Logs -->
                @if($canViewLogs)
                <li x-data="sidebarDropdown('admin-logs', {{ request()->routeIs('admin.security.logs') ? 'true' : 'false' }})">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('admin.security.logs') ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-journal-text mr-2"></i> Logs</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Activity Logs</a></li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.security.logs') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.security.logs') }}" @click="closeSidebarOnMobile()">
                                    Login History
                                </a>
                            </li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Violation Logs</a></li>
                        </ul>
                    </div>
                </li>
                @endif

            </ul>
        </div>
        </div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col transition-all duration-300"
        :class="sidebarOpen && isDesktop ? 'lg:ml-72' : 'lg:ml-0'">
        <!-- Topbar -->
        <nav class="admin-glass sticky top-0 z-30 flex items-center justify-between border-b border-white/60 px-4 py-3 shadow-sm sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-gray-600 shadow-sm transition hover:border-slate-300 hover:text-slate-900 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">Admin Panel</p>
                    <h5 class="mb-0 truncate text-base font-semibold text-slate-800 sm:text-lg">@yield('title')</h5>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <div class="hidden text-right sm:block">
                    <small class="block leading-tight text-gray-500">Welcome, {{ auth()->user()->name ?? 'Admin'
                        }}</small>
                    <span class="block max-w-[220px] truncate font-semibold text-gray-800">{{ auth()->user()?->school?->name ?? 'School' }}</span>
                </div>
                @include('partials.notification-dropdown', [
                'notifications' => $notificationPreviewItems ?? collect(),
                'unreadCount' => $unreadNotificationsCount ?? 0,
                'unreadCountRoute' => route('admin.notifications.unreadCount'),
                'allNotificationsUrl' => route('admin.notifications'),
                'refreshInterval' => 10000,
                'soundPreference' => $notificationSoundPreference ?? ['tone' => 'chime', 'custom_sound_name' => null,
                'custom_sound_url' => null],
                'soundPreferenceUpdateUrl' => route('admin.notifications.soundPreference.update'),
                ])
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm focus:outline-none"
                        @click="open = !open" @click.away="open = false">
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                    </button>
                    <ul x-show="open" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
                        <li class="px-4 py-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</span>
                        </li>
                        <li>
                            <a href="{{ route('admin.profile') }}"
                                class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 flex items-center transition-colors">
                                <i class="bi bi-person-gear mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" class="m-0">
                                @csrf
                                <button
                                    class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 flex items-center transition-colors"
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
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </div>
    </div>

</body>
@stack('script')

</html>
