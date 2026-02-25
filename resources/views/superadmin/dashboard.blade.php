@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
    <!-- Total Schools -->
    <a href="{{ route('superadmin.schools.index') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Total Schools</h6>
                <div class="bg-blue-100 bg-opacity-10 text-blue-600 rounded p-2">
                    <i class="bi bi-building text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalSchools }}</h3>
            @if($schoolGrowthPercentage > 0)
                <small class="text-green-600 font-medium"><i class="bi bi-arrow-up-short"></i> {{ round($schoolGrowthPercentage) }}% New</small>
            @endif
        </div>
    </a>

    <!-- Total Admins -->
    <a href="{{ route('superadmin.admins.index') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Total Admins</h6>
                <div class="bg-cyan-100 bg-opacity-10 text-cyan-600 rounded p-2">
                    <i class="bi bi-person-badge text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $totalAdmins }}</h3>
            <small class="text-gray-500 font-medium">Active Staff</small>
        </div>
    </a>

    <!-- Total Students -->
    <a href="{{ route('superadmin.students.index') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Total Students</h6>
                <div class="bg-green-100 bg-opacity-10 text-green-600 rounded p-2">
                    <i class="bi bi-people text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ number_format($totalStudents) }}</h3>
            @if($studentGrowthPercentage > 0)
                <small class="text-green-600 font-medium"><i class="bi bi-arrow-up-short"></i> {{ round($studentGrowthPercentage) }}% Growth</small>
            @endif
        </div>
    </a>

    <!-- Active Exams -->
    <a href="{{ route('superadmin.exams.index') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Active Exams</h6>
                <div class="bg-yellow-100 bg-opacity-10 text-yellow-600 rounded p-2">
                    <i class="bi bi-file-earmark-text text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $liveExams }}</h3>
            <small class="text-red-600 font-medium">Live Now</small>
        </div>
    </a>

    <!-- Pending Approvals -->
    <a href="{{ route('superadmin.staff-requests.index') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Pending</h6>
                <div class="bg-gray-100 bg-opacity-10 text-gray-600 rounded p-2">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $pendingApprovals }}</h3>
            <small class="text-yellow-600 font-medium">Requires Action</small>
        </div>
    </a>

    <!-- System Alerts -->
    <a href="{{ route('superadmin.security.logs') }}" class="block bg-white rounded-lg border border-gray-200 shadow-sm h-full hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-gray-500 text-xs uppercase font-bold">Alerts</h6>
                <div class="bg-red-100 bg-opacity-10 text-red-600 rounded p-2">
                    <i class="bi bi-exclamation-triangle text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $systemAlerts }}</h3>
            <small class="text-red-600 font-medium">Critical</small>
        </div>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <!-- Analytics Placeholder -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm h-full">
            <div class="px-6 py-3 border-b border-gray-200 bg-white">
                <h6 class="text-lg font-bold">Exam Participation Trends</h6>
            </div>
            <div class="p-6 flex items-center justify-center bg-gray-50" style="min-height: 300px;">
                <div class="text-center text-gray-500">
                    <i class="bi bi-bar-chart-line text-5xl"></i>
                    <p class="mt-2">Analytics Chart Placeholder</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm h-full">
            <div class="px-6 py-3 border-b border-gray-200 bg-white">
                <h6 class="text-lg font-bold">System Health</h6>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium">Server Load</span>
                        <span class="text-sm text-green-600">24%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-green-600 h-1.5 rounded-full" style="width: 24%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium">Database Connections</span>
                        <span class="text-sm text-blue-600">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium">Redis Queue</span>
                        <span class="text-sm text-yellow-600">68%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-yellow-600 h-1.5 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium">Storage Usage</span>
                        <span class="text-sm text-cyan-600">82%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-cyan-600 h-1.5 rounded-full" style="width: 82%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="px-6 py-3 border-b border-gray-200 bg-white flex justify-between items-center">
        <h6 class="text-lg font-bold">Recent Activity</h6>
        <a href="{{-- route('superadmin.security.logs') --}}" class="text-sm px-3 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User / Entity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentActivities as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-gray-100 rounded-full p-2 mr-3 border border-gray-200">
                                    <i class="bi bi-person text-gray-600"></i>
                                </div>
                                <span class="font-medium">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->event }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            @if($log->event === 'login')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Success</span>
                            @elseif($log->event === 'failed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Info</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">
                            No recent activity.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
