<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Portal | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
    $school = auth()->user()?->school ?? (auth()->user()?->school_id ? \App\Models\School::find(auth()->user()->school_id) : null);
    @endphp

    @if($school?->logo)
    <link rel="icon" href="{{ asset('storage/' . $school->logo) }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
            background: rgba(255, 255, 255, 0.05);
            border-left-color: #3b82f6 !important;
            /* blue-500 */
        }
    </style>
</head>

<body class="bg-gray-50 h-full" x-data="{ sidebarOpen: false }" oncontextmenu="return false">

    <!-- Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-screen w-64 bg-gray-900 text-white flex flex-col z-50 transition-transform duration-300 transform lg:translate-x-0"
         :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
         x-cloak
    >
        <div class="p-5 bg-white/5 border-b border-white/10 shrink-0">
            <div class="flex items-center justify-start">
                @if($school?->logo)
                <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo"
                    class="max-h-[50px] max-w-[60px] min-w-[40px] mr-4 bg-white rounded-md object-contain border border-gray-200">
                @endif
                <div class="flex flex-col items-start min-w-0">
                    <span class="text-white font-bold text-lg mb-0.5 leading-tight break-all w-[150px] whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $school?->name }}">
                        {{ Str::limit($school?->name ?? 'ExamPlatform', 22) }}
                    </span>
                    <span class="text-sm text-blue-400 font-medium tracking-wide opacity-100 shadow-black drop-shadow-sm">
                        Student Portal
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto sidebar-scroll py-2">
            <ul class="flex flex-col space-y-1">

                <!-- Dashboard -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.dashboard') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.dashboard') ? route('student.dashboard') : '#' }}">
                        <div><i class="bi bi-speedometer2 mr-2"></i> Dashboard</div>
                    </a>
                </li>

                <!-- My Exams -->
                <li x-data="{ open: {{ request()->routeIs('student.exams.*') ? 'true' : 'false' }} }">
                    @php $isExamsActive = request()->routeIs('student.exams.*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isExamsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-pencil-square mr-2"></i> Exams</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.exams.index') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.exams.index') ? route('student.exams.index') : '#' }}">
                                    Upcoming Exams
                                </a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.exams.practice') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.exams.practice') ? route('student.exams.practice') : '#' }}">
                                    Demo / Practice
                                </a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.exams.history') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.exams.history') ? route('student.exams.history') : '#' }}">
                                    Exam History
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Results & Certificates -->
                <li x-data="{ open: {{ request()->routeIs('student.results.*') || request()->routeIs('student.solutions.*') || request()->routeIs('student.certificates.*') ? 'true' : 'false' }} }">
                    @php
                        $isResultsActive = request()->routeIs('student.results.*') || request()->routeIs('student.solutions.*') || request()->routeIs('student.certificates.*');
                    @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isResultsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-trophy mr-2"></i> Results & Certificates</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('student.results.*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ Route::has('student.results.index') ? route('student.results.index') : '#' }}">
                                    Results / Scorecard
                                </a>
                            </li>
                            <li>
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
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- System Check -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.system.check') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.system.check') ? route('student.system.check') : '#' }}">
                        <div><i class="bi bi-pc-display-horizontal mr-2"></i> System Check</div>
                    </a>
                </li>

                <!-- Notifications -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.notifications') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.notifications') ? route('student.notifications') : '#' }}">
                        <div><i class="bi bi-bell mr-2"></i> Notifications</div>
                    </a>
                </li>

                <!-- Profile -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.profile') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}">
                        <div><i class="bi bi-person-circle mr-2"></i> Profile</div>
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->routeIs('student.settings') ? 'nav-link-active' : '' }}"
                        href="{{ Route::has('student.settings') ? route('student.settings') : '#' }}">
                        <div><i class="bi bi-gear mr-2"></i> Settings</div>
                    </a>
                </li>

            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Topbar -->
        <nav class="bg-white px-4 sm:px-6 py-3 shadow-sm flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Hamburger for mobile -->
                <button @click.stop="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h5 class="mb-0 text-gray-600 font-medium text-base sm:text-lg">
                    @yield('title')
                </h5>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <div class="text-right mr-4">
                    <small class="block text-gray-500 leading-tight">Welcome,</small>
                    <span class="font-bold text-gray-800">{{ auth()->user()?->name ?? 'Student' }}</span>
                </div>
                <div class="mr-4 relative">
                    <i class="bi bi-bell text-xl text-gray-500"></i>
                    <!-- Notification Badge Example -->
                    <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 bg-red-500 text-white rounded-full text-[10px]">
                        2
                    </span>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border shadow-sm focus:outline-none overflow-hidden"
                        @click="open = !open" @click.away="open = false">
                        @if(auth()->user()?->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                        @endif
                    </button>
                    <ul x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
                        <li class="px-4 py-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800 block truncate">{{ auth()->user()?->name ?? 'Student' }}</span>
                            <span class="text-xs text-gray-500 block truncate">{{ auth()->user()?->email ?? '' }}</span>
                        </li>
                        <li>
                            <a href="{{ Route::has('student.profile') ? route('student.profile') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="bi bi-person mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form id="logout-form" method="POST" action="{{ Route::has('student.logout') ? route('student.logout') : (Route::has('logout') ? route('logout') : '#') }}" class="m-0">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 flex items-center transition-colors text-sm" type="submit">
                                    <i class="bi bi-box-arrow-right mr-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="p-4 sm:p-6 md:p-8 flex-1">
            @yield('content')
        </div>
    </div>

</body>

</html>