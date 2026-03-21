<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRequest;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\School;
use App\Models\SecurityLog;
use App\Models\StaffRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats Cards
        $totalSchools = School::count();
        $newSchools = School::where('created_at', '>=', now()->subMonth())->count();
        $totalSchoolsPrevious = $totalSchools - $newSchools;
        $schoolGrowthPercentage = $totalSchoolsPrevious > 0 ? ($newSchools / $totalSchoolsPrevious) * 100 : ($newSchools > 0 ? 100 : 0);

        $totalAdmins = Admin::whereIn('role', ['school_admin', 'sub_admin', 'staff'])->count();
        $totalStudents = User::where('role', 'student')->count();
        $newStudents = User::where('role', 'student')->where('created_at', '>=', now()->subMonth())->count();
        $totalStudentsPrevious = $totalStudents - $newStudents;
        $studentGrowthPercentage = $totalStudentsPrevious > 0 ? ($newStudents / $totalStudentsPrevious) * 100 : ($newStudents > 0 ? 100 : 0);

        $liveExams = Exam::where('status', 'published')
            ->whereHas('schedule', function ($query) {
                $query->where('start_at', '<=', now())
                    ->where('end_at', '>=', now());
            })
            ->count();

        $pendingStaffRequests = StaffRequest::where('status', 'pending_verification')->count();
        $pendingAdminRequests = AdminRequest::where('status', 'pending')->count();
        $pendingApprovals = $pendingStaffRequests + $pendingAdminRequests;

        $systemHealth = $this->systemHealthMetrics();
        $systemAlerts = collect($systemHealth)->filter(fn ($metric) => ($metric['state'] ?? 'healthy') === 'critical')->count();
        $participationTrend = $this->participationTrendMetrics();
        $peakParticipation = collect($participationTrend)->max('attempts') ?? 0;

        $recentActivities = SecurityLog::with('user')->latest()->limit(5)->get();

        return view('superadmin.dashboard', compact(
            'totalSchools', 'schoolGrowthPercentage',
            'totalAdmins',
            'totalStudents', 'studentGrowthPercentage',
            'liveExams', 'pendingApprovals', 'systemAlerts', 'systemHealth',
            'participationTrend', 'peakParticipation',
            'recentActivities'
        ));
    }

    private function participationTrendMetrics(): array
    {
        $days = collect(range(6, 0, -1))
            ->map(fn ($offset) => now()->subDays($offset)->startOfDay())
            ->push(now()->startOfDay())
            ->values();

        $attemptsByDate = ExamAttempt::query()
            ->selectRaw('DATE(COALESCE(started_at, created_at)) as exam_day, COUNT(*) as total_attempts')
            ->where(function ($query) use ($days) {
                $query->where('started_at', '>=', $days->first())
                    ->orWhere('created_at', '>=', $days->first());
            })
            ->groupBy('exam_day')
            ->pluck('total_attempts', 'exam_day');

        $peak = max(1, (int) ($attemptsByDate->max() ?? 0));

        return $days->map(function ($day) use ($attemptsByDate, $peak) {
            $dateKey = $day->toDateString();
            $count = (int) ($attemptsByDate[$dateKey] ?? 0);

            return [
                'label' => $day->format('D'),
                'full_date' => $day->format('d M'),
                'attempts' => $count,
                'height' => max(12, (int) round(($count / $peak) * 100)),
            ];
        })->all();
    }

    private function systemHealthMetrics(): array
    {
        $serverLoad = $this->serverLoadMetric();
        $databaseConnections = $this->databaseMetric();
        $redisStatus = $this->redisStatusMetric();
        $redisQueue = $this->redisQueueMetric();
        $activeLiveStudents = $this->activeLiveStudentsMetric();
        $failedJobs = $this->failedJobsMetric();
        $failedLogins = $this->failedLoginsMetric();
        $storageUsage = $this->storageUsageMetric();

        return [
            $serverLoad,
            $databaseConnections,
            $redisStatus,
            $redisQueue,
            $activeLiveStudents,
            $failedJobs,
            $failedLogins,
            $storageUsage,
        ];
    }

    private function serverLoadMetric(): array
    {
        $load = null;

        if (function_exists('sys_getloadavg')) {
            $averages = sys_getloadavg();
            $load = is_array($averages) ? ($averages[0] ?? null) : null;
        }

        $percentage = $load === null ? null : max(0, min(100, (int) round($load * 100)));

        return $this->makeMetric(
            label: 'Server Load',
            percentage: $percentage,
            fallbackLabel: $load === null ? 'Unavailable' : number_format((float) $load, 2),
            thresholds: ['healthy' => 40, 'warning' => 75]
        );
    }

    private function databaseMetric(): array
    {
        try {
            DB::select('SELECT 1');
            $currentConnections = $this->currentDatabaseConnections();
            $maxConnections = $this->maxDatabaseConnections();

            $percentage = $currentConnections !== null && $maxConnections
                ? (int) round(min(100, ($currentConnections / $maxConnections) * 100))
                : null;

            return $this->makeMetric(
                label: 'Database Connections',
                percentage: $percentage,
                fallbackLabel: $currentConnections !== null && $maxConnections
                    ? "{$currentConnections}/{$maxConnections}"
                    : 'Connected',
                thresholds: ['healthy' => 60, 'warning' => 85]
            );
        } catch (\Throwable $exception) {
            return [
                'label' => 'Database Connections',
                'value' => 'Down',
                'percentage' => 100,
                'color' => 'red',
                'state' => 'critical',
            ];
        }
    }

    private function redisQueueMetric(): array
    {
        try {
            $redisConnection = config('queue.connections.redis.connection', 'default');
            $queueName = config('queue.connections.redis.queue', 'default');
            $queueDepth = (int) Redis::connection($redisConnection)->llen("queues:{$queueName}");

            $percentage = min(100, $queueDepth === 0 ? 0 : (int) round(($queueDepth / 50) * 100));

            return $this->makeMetric(
                label: 'Redis Queue',
                percentage: $percentage,
                fallbackLabel: $queueDepth . ' jobs',
                thresholds: ['healthy' => 35, 'warning' => 75]
            );
        } catch (\Throwable $exception) {
            return [
                'label' => 'Redis Queue',
                'value' => 'Unavailable',
                'percentage' => 100,
                'color' => 'red',
                'state' => 'critical',
            ];
        }
    }

    private function redisStatusMetric(): array
    {
        try {
            $ping = strtolower((string) Redis::connection('default')->ping());

            return [
                'label' => 'Redis Status',
                'value' => $ping === 'pong' ? 'Connected' : strtoupper($ping),
                'percentage' => $ping === 'pong' ? 10 : 100,
                'color' => $ping === 'pong' ? 'green' : 'red',
                'state' => $ping === 'pong' ? 'healthy' : 'critical',
            ];
        } catch (\Throwable $exception) {
            return [
                'label' => 'Redis Status',
                'value' => 'Down',
                'percentage' => 100,
                'color' => 'red',
                'state' => 'critical',
            ];
        }
    }

    private function activeLiveStudentsMetric(): array
    {
        $count = ExamAttempt::where('status', 'in_progress')->count();
        $percentage = min(100, $count === 0 ? 0 : (int) round(($count / 100) * 100));

        return $this->makeMetric(
            label: 'Active Live Students',
            percentage: $percentage,
            fallbackLabel: $count . ' active',
            thresholds: ['healthy' => 50, 'warning' => 85]
        );
    }

    private function failedJobsMetric(): array
    {
        try {
            $count = DB::table('failed_jobs')->count();
            $percentage = min(100, $count === 0 ? 0 : (int) round(($count / 20) * 100));

            return $this->makeMetric(
                label: 'Failed Jobs',
                percentage: $percentage,
                fallbackLabel: $count . ' failed',
                thresholds: ['healthy' => 10, 'warning' => 30]
            );
        } catch (\Throwable $exception) {
            return [
                'label' => 'Failed Jobs',
                'value' => 'Unavailable',
                'percentage' => 0,
                'color' => 'gray',
                'state' => 'healthy',
            ];
        }
    }

    private function failedLoginsMetric(): array
    {
        $count = SecurityLog::where('event', 'failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $percentage = min(100, $count === 0 ? 0 : (int) round(($count / 25) * 100));

        return $this->makeMetric(
            label: 'Failed Logins (24h)',
            percentage: $percentage,
            fallbackLabel: $count . ' failed',
            thresholds: ['healthy' => 20, 'warning' => 50]
        );
    }

    private function storageUsageMetric(): array
    {
        $storagePath = storage_path();
        $total = @disk_total_space($storagePath);
        $free = @disk_free_space($storagePath);

        if (!$total || $free === false) {
            return $this->makeMetric(
                label: 'Storage Usage',
                percentage: null,
                fallbackLabel: 'Unavailable',
                thresholds: ['healthy' => 70, 'warning' => 90]
            );
        }

        $usedPercentage = (int) round((($total - $free) / $total) * 100);

        return $this->makeMetric(
            label: 'Storage Usage',
            percentage: $usedPercentage,
            fallbackLabel: $usedPercentage . '%',
            thresholds: ['healthy' => 70, 'warning' => 90]
        );
    }

    private function currentDatabaseConnections(): ?int
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $result = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            return isset($result[0]->Value) ? (int) $result[0]->Value : null;
        }

        return null;
    }

    private function maxDatabaseConnections(): ?int
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $result = DB::select("SHOW VARIABLES LIKE 'max_connections'");
            return isset($result[0]->Value) ? (int) $result[0]->Value : null;
        }

        return null;
    }

    private function makeMetric(string $label, ?int $percentage, string $fallbackLabel, array $thresholds): array
    {
        $value = $percentage === null ? $fallbackLabel : $percentage . '%';
        $state = 'healthy';
        $color = 'green';

        if ($percentage !== null) {
            if ($percentage >= $thresholds['warning']) {
                $state = 'critical';
                $color = 'red';
            } elseif ($percentage >= $thresholds['healthy']) {
                $state = 'warning';
                $color = 'yellow';
            } else {
                $color = match ($label) {
                    'Database Connections' => 'blue',
                    'Storage Usage' => 'cyan',
                    'Redis Queue' => 'amber',
                    'Active Live Students' => 'violet',
                    default => 'green',
                };
            }
        } else {
            $color = match ($label) {
                'Database Connections' => 'blue',
                'Storage Usage' => 'cyan',
                default => 'gray',
            };
        }

        return [
            'label' => $label,
            'value' => $value,
            'percentage' => $percentage ?? 0,
            'color' => $color,
            'state' => $state,
        ];
    }
}
