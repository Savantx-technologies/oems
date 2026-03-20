<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SecurityLog;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = SecurityLog::query()
            ->when($request->filled('guard'), fn ($query) => $query->where('guard', $request->string('guard')->toString()))
            ->latest()
            ->paginate(50);

        $this->hydrateActorDetails($logs);
        $this->hydrateEnvironmentDetails($logs);

        return view('superadmin.security.logs', compact('logs'));
    }

    public function export(Request $request)
    {
        $guardFilter = $request->string('guard')->toString();
        $fileName = 'platform_activity_logs_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () use ($guardFilter) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Time',
                'Guard',
                'Role',
                'Name',
                'Email',
                'User ID',
                'Event',
                'IP',
                'Description',
                'Route',
                'Method',
                'Payload',
            ]);

            SecurityLog::query()
                ->when($guardFilter !== '', fn ($query) => $query->where('guard', $guardFilter))
                ->latest()
                ->chunk(500, function ($logs) use ($handle) {
                    $this->hydrateActorDetails($logs);

                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->created_at,
                            $log->guard,
                            $log->actor_role,
                            $log->actor_name,
                            $log->actor_email,
                            $log->user_id,
                            $log->event,
                            $log->ip_address,
                            $log->description,
                            data_get($log->payload, 'route_name'),
                            data_get($log->payload, 'method'),
                            $log->payload ? json_encode($log->payload) : null,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    private function hydrateActorDetails(iterable $logs): void
    {
        $groupedIds = [
            'superadmin' => [],
            'admin' => [],
            'student' => [],
            'web' => [],
        ];

        foreach ($logs as $log) {
            if ($log->user_id && array_key_exists($log->guard, $groupedIds)) {
                $groupedIds[$log->guard][] = $log->user_id;
            }
        }

        $superAdmins = SuperAdmin::whereIn('id', array_unique($groupedIds['superadmin']))->get()->keyBy('id');
        $admins = Admin::whereIn('id', array_unique($groupedIds['admin']))->get()->keyBy('id');
        $students = User::whereIn('id', array_unique(array_merge($groupedIds['student'], $groupedIds['web'])))->get()->keyBy('id');

        foreach ($logs as $log) {
            $actor = match ($log->guard) {
                'superadmin' => $superAdmins->get($log->user_id),
                'admin' => $admins->get($log->user_id),
                'student', 'web' => $students->get($log->user_id),
                default => null,
            };

            $log->actor_name = $actor?->name ?? 'Unknown';
            $log->actor_email = $actor?->email ?? '-';
            $log->actor_role = $actor?->role ?? ($log->guard === 'student' ? 'student' : $log->guard);
        }
    }

    private function hydrateEnvironmentDetails(iterable $logs): void
    {
        foreach ($logs as $log) {
            $agent = new Agent();
            $agent->setUserAgent($log->user_agent);

            $log->browser = $agent->browser();
            $log->browser_version = $agent->version($log->browser);
            $log->platform = $agent->platform();

            if (!$log->ip_address || $log->ip_address === '127.0.0.1' || $log->ip_address === '::1') {
                $log->location = 'Localhost';
                continue;
            }

            $position = Location::get($log->ip_address);
            $log->location = $position ? $position->cityName . ', ' . $position->countryName : 'Unknown';
        }
    }
}
