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

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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

<body class="bg-gray-50 h-full">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-screen w-64 bg-gray-900 text-white flex flex-col z-50 transition-all duration-300">
        <div class="p-5 bg-white/5 border-b border-white/10 shrink-0">
            <div class="flex items-center justify-start">
                @if(auth()->user()?->school?->logo)
                <img src="{{ asset('storage/' . auth()->user()->school->logo) }}" alt="School Logo"
                    class="max-h-[50px] max-w-[60px] min-w-[40px] mr-4 bg-white rounded-md object-contain border border-gray-200">
                @endif
                <div class="flex flex-col items-start min-w-0">
                    <span
                        class="text-white font-bold text-lg mb-0.5 leading-tight break-all w-[150px] whitespace-nowrap overflow-hidden text-ellipsis"
                        title="{{ auth()->user()?->school?->name }}">
                        {{ Str::limit(auth()->user()?->school?->name ?? 'ExamPlatform Pro', 22) }}
                    </span>
                    <span
                        class="text-sm text-yellow-400 font-medium tracking-wide opacity-100 shadow-black drop-shadow-sm">
                        School Admin Panel
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto sidebar-scroll py-2">
            <ul class="flex flex-col space-y-1">
                @php
                $questionOpen = request()->is('admin/questions*');
                $examOpen = request()->is('admin/exams*');
                @endphp

                <!-- Dashboard -->
                <li>
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="{{ route('admin.dashboard') }}">
                        <div><i class="bi bi-speedometer2 mr-2"></i> Dashboard</div>
                    </a>
                </li>

                <!-- Admissions -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-person-plus mr-2"></i> Admissions</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="{{ route('admin.students.create') }}">New
                                    Admission</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="{{ route('admin.students.bulk_create') }}">Bulk Upload</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Signup Link</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Batch Assignment</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Users (School Only) -->
                <li x-data="{ open: {{ request()->is('admin/staff/create*') ? 'true' : 'false' }} }">
                    @php $isUsersActive = request()->is('admin/staff/create*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isUsersActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-people mr-2"></i> Users</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <div class="px-5 py-2 text-gray-500 text-xs uppercase font-bold mt-2">Staff Management
                                </div>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ $isUsersActive ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.staff.create.step1') }}">Add Staff</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="{{ route('admin.requests.staff.create') }}">Request Reset /
                                    Block</a>
                            </li>
                            <li>
                                <div class="px-5 py-2 text-gray-500 text-xs uppercase font-bold mt-2">Students</div>
                            </li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.students.index') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('admin.students.index') }}">View
                                    Students</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Reset Attempt</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Request Block Student</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Question Bank -->
                <li x-data="{ open: {{ $questionOpen ? 'true' : 'false' }} }">
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
                                <a href="{{ route('admin.questions.index') }}" class="block px-5 py-2 pl-11 text-sm
                    {{ request()->routeIs('admin.questions.index')
    ? 'text-white bg-white/10'
    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    All Questions (MCQ)
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.questions.create') }}" class="block px-5 py-2 pl-11 text-sm
                    {{ request()->routeIs('admin.questions.create')
    ? 'text-white bg-white/10'
    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Add New Question
                                </a>
                            </li>

                            {{-- future --}}
                            <li>
                                <a href="{{ route('admin.questions.bulk.form') }}"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Bulk Upload
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Exams -->
                <li x-data="{ open: {{ $examOpen ? 'true' : 'false' }} }">
                    <a class="flex items-center justify-between px-5 py-3
                            {{ $examOpen ? 'text-white bg-white/10 border-l-4 border-indigo-500' : 'text-gray-400 border-l-4 border-transparent' }}
                            hover:bg-white/5 hover:text-white transition-colors" href="#" @click.prevent="open = !open">
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
                                <a href="{{ route('admin.exams.create') }}" class="block px-5 py-2 pl-11 text-sm
                                    {{ request()->routeIs('admin.exams.create')
                                        ? 'text-white bg-white/10'
                                        : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Create Exam
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.exams.index') }}" class="block px-5 py-2 pl-11 text-sm
                                {{ request()->routeIs('admin.exams.index')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Manage Exams
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <!-- Live Exams -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-camera-video mr-2"></i> Live Exams</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a href="{{ route('admin.exams.index') }}" class="block px-5 py-2 pl-11 text-sm 
                                        {{ request()->routeIs('admin.exams.monitor')
                                    ? 'text-white bg-white/10'
                                    : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                                    Live Exam Monitor
                                </a>
                            </li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Student Camera Grid</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Issue Warning</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Remove Student</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Extend Time</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Practice / Demo Exams -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-laptop mr-2"></i> Practice / Demo</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a href="{{ route('admin.exams.practice') }}"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Practice Exam
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.exams.practice.solutions') }}"
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10">
                                    Solution View
                                </a>
                            </li>


                        </ul>
                    </div>
                </li>

                <!-- Evaluation & Results -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-check2-square mr-2"></i> Evaluation</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Auto Evaluation</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Manual Checking</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Result Approval</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Scorecards</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Certificates</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Reports -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-bar-chart mr-2"></i> Reports</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Exam Reports</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Performance Analytics</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Violation Reports</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Export PDF / Excel</a></li>
                        </ul>
                    </div>
                </li>

                <!-- School Settings -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-gear mr-2"></i> Settings</div>
                        <i class="bi bi-chevron-down text-xs transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">School Profile</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Branding</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Exam Rules</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Notification Settings</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Logs -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
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
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.security.logs') ? 'text-white bg-white/10' : '' }}" href="{{ route('admin.security.logs') }}">
                                    Login History
                                </a>
                            </li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10"
                                    href="#">Violation Logs</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 min-h-screen flex flex-col">
        <!-- Topbar -->
        <nav class="bg-white px-8 py-4 shadow-sm flex justify-between items-center">
            <h5 class="mb-0 text-gray-600 font-medium text-lg">@yield('title')</h5>
            <div class="flex items-center">
                <div class="text-right mr-4">
                    <small class="block text-gray-500 leading-tight">Welcome, {{ auth()->user()->name ?? 'Admin'
                        }}</small>
                    <span class="font-bold text-gray-800">{{ auth()->user()?->school?->name ?? 'School' }}</span>
                </div>
                <div class="mr-4 relative">
                    <i class="bi bi-bell text-xl text-gray-500"></i>
                    <span
                        class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 bg-red-500 text-white rounded-full text-[10px]">
                        5
                    </span>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border shadow-sm focus:outline-none"
                        @click="open = !open" @click.away="open = false">
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                    </button>
                    <ul x-show="open" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
                        <li class="px-4 py-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</span>
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
        <div class="p-8 flex-1">
            @yield('content')
        </div>
    </div>

</body>

</html>