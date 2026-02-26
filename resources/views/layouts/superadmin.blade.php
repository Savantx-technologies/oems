<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Super Admin | @yield('title')</title>
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-50 h-full" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-screen w-64 bg-gray-900 text-white flex flex-col z-50 transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="p-5 bg-white/5 border-b border-white/10 text-center shrink-0">
            <h5 class="mb-0 font-bold text-xl">ExamPlatform <span class="text-blue-500">Pro</span></h5>
        </div>
        <div class="flex-1 overflow-y-auto sidebar-scroll py-2">
            <ul class="flex flex-col space-y-1">

                <!-- Dashboard -->
                <li>
                    <a class="flex items-center px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ request()->is('superadmin') ? 'nav-link-active' : '' }}"
                        href="{{ route('superadmin.dashboard') }}">
                        <div><i class="bi bi-speedometer2 mr-2"></i> Dashboard</div>
                    </a>
                </li>

                <!-- School Management -->
                <li x-data="{ open: {{ request()->is('superadmin/schools*') ? 'true' : 'false' }} }">
                    @php $isSchoolActive = request()->is('superadmin/schools*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isSchoolActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-building mr-2"></i> School Management</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->is('superadmin/schools') || request()->is('superadmin/schools/*/edit') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('superadmin.schools.index') }}">All Schools</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->is('superadmin/schools/create*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('superadmin.schools.create') }}">Add School</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.schools.suspension') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.schools.suspension') }}">Suspension Control</a>
                            </li>
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">School Analytics</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Admin & Staff Control -->
                <li x-data="{ open: {{ (request()->is('superadmin/admins*') || request()->is('superadmin/staff-requests*') || request()->is('superadmin/admin-requests*')) ? 'true' : 'false' }} }">
                    @php $isAdminActive = request()->is('superadmin/admins*') ||
                    request()->is('superadmin/staff-requests*') || request()->is('superadmin/admin-requests*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isAdminActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-person-badge mr-2"></i> Admin & Staff</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->is('superadmin/admins*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('superadmin.admins.index') }}">All Admins & Staff</a></li>
                            <li><a
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->is('superadmin/staff-requests*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('superadmin.staff-requests.index') }}">Staff Approval Queue</a></li>
                            <li><a
                                    class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->is('superadmin/admin-requests*') ? 'text-white bg-white/10' : '' }}"
                                    href="{{ route('superadmin.admin-requests.index') }}">Block & unblock Account</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Roles & Permissions -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-shield-lock mr-2"></i> Roles & Permissions</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Role List</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Create Role</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Assign Permissions</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Clone Role</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Permission Matrix</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Student Control -->
                <li x-data="{ open: {{ request()->routeIs('superadmin.students.*') ? 'true' : 'false' }} }">
                    @php $isStudentActive = request()->routeIs('superadmin.students.*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isStudentActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-people mr-2"></i> Student Control</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.students.index') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.students.index') }}">View Students</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Exam Control -->
                <li x-data="{ open: {{ request()->routeIs('superadmin.exams.*') ? 'true' : 'false' }} }">
                    @php $isExamActive = request()->routeIs('superadmin.exams.*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isExamActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-file-earmark-text mr-2"></i> Exam Control</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.exams.index') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.exams.index') }}">View All Exams</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.exams.violation-summary') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.exams.violation-summary') }}">Exam Violation Summary</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Live Monitoring -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-camera-video mr-2"></i> Live Monitoring</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Live Camera Grid</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Ongoing Exam Status</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Violation Alerts</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Issue Warning</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Remove Student</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Extend Time (Individual)</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Reports & Analytics -->
                <li x-data="{ open: {{ request()->routeIs('superadmin.reports.*') ? 'true' : 'false' }} }">
                    @php $isReportsActive = request()->routeIs('superadmin.reports.*'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isReportsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-bar-chart mr-2"></i> Reports & Analytics</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.reports.exams') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.reports.exams') }}">Exam Reports</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.reports.analytics') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.reports.analytics') }}">Performance Analytics</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.reports.violations') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.reports.violations') }}">Violation Reports</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.reports.schools') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.reports.schools') }}">School-wise Reports</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Logs & Security -->
                <li x-data="{ open: {{ request()->routeIs('superadmin.security.logs') ? 'true' : 'false' }} }">
                    @php $isLogsActive = request()->routeIs('superadmin.security.logs'); @endphp
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent {{ $isLogsActive ? 'nav-link-active' : '' }}"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-journal-text mr-2"></i> Logs & Security</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li>
                                <a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10 {{ request()->routeIs('superadmin.security.logs') ? 'text-white bg-white/10' : '' }}" href="{{ route('superadmin.security.logs') }}">
                                    Login History
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- System Configuration -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-gear mr-2"></i> System Config</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Exam Rules Engine</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Proctoring Settings</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Camera Rules</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Anti-Cheat Settings</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Notification Templates</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Email / SMS Config</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Infrastructure -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-server mr-2"></i> Infrastructure</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Redis Status</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Queue Monitoring</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Worker Health</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Backup Status</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Failover Logs</a></li>
                        </ul>
                    </div>
                </li>

                <!-- AI & Advanced -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-cpu mr-2"></i> AI & Advanced</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">AI Proctoring Settings</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Face Recognition Rules</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Behavior Detection</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">AI Analytics</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Profile & Access -->
                <li x-data="{ open: false }">
                    <a class="flex items-center justify-between px-5 py-3 text-gray-400 hover:bg-white/5 hover:text-white transition-colors border-l-4 border-transparent"
                        href="#" @click.prevent="open = !open">
                        <div><i class="bi bi-person-circle mr-2"></i> Profile & Access</div>
                        <i class="bi bi-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </a>
                    <div x-show="open" x-collapse class="bg-black/20">
                        <ul class="flex flex-col py-1">
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">My Profile</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Change Password</a></li>
                            <li><a class="block px-5 py-2 pl-11 text-sm text-gray-400 hover:text-white hover:bg-white/10" href="#">Active Sessions</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar footer and logout button removed from here -->
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col transition-all duration-300"
        :class="sidebarOpen ? 'ml-64' : 'ml-0'">
        <!-- Topbar -->
        <nav class="bg-white px-8 py-4 shadow-sm flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h5 class="mb-0 text-gray-600 font-medium text-lg">@yield('title')</h5>
            </div>
            <div class="flex items-center">
                <div class="text-right mr-4">
                    <small class="block text-gray-500 leading-tight">Welcome,</small>
                    <span class="font-bold text-gray-800">{{ auth()->user()?->name ?? 'Super Admin' }}</span>
                </div>
                <div class="mr-4 relative" x-data="{
                    unreadCount: {{ $unreadNotificationsCount ?? 0 }},
                    checkNotifications() {
                        fetch('{{ route('superadmin.notifications.unreadCount') }}')
                            .then(res => res.json())
                            .then(data => { this.unreadCount = data.count; })
                            .catch(err => console.error(err));
                    }
                }" x-init="setInterval(() => checkNotifications(), 15000)">
                    <a href="{{ route('superadmin.notifications.index') }}" class="text-gray-500 hover:text-gray-700 relative block">
                        <i class="bi bi-bell text-xl"></i>
                        <span x-show="unreadCount > 0" x-text="unreadCount" x-cloak class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 bg-red-500 text-white rounded-full text-[10px]"></span>
                    </a>
                </div>
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button
                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border shadow-sm focus:outline-none"
                        @click="open = !open" @click.away="open = false">
                        <i class="bi bi-person-fill text-gray-500 text-xl"></i>
                    </button>
                    <ul x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100">
                        <li class="px-4 py-2 border-b border-gray-100">
                            <span class="font-bold text-gray-800">{{ auth()->user()?->name ?? 'Super Admin' }}</span>
                        </li>
                        <li>
                            <form id="logout-form" method="POST" action="{{ route('superadmin.logout') }}" class="m-0">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 flex items-center transition-colors" type="submit">
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