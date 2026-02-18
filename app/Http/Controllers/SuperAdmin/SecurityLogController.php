<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SecurityLog;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecurityLogController extends Controller
{

    public function index()
    {
        $logs = SecurityLog::where('guard', 'superadmin')
            ->latest()
            ->paginate(50);

        foreach ($logs as $log) {

            // Browser & OS
            $agent = new Agent();
            $agent->setUserAgent($log->user_agent);

            $log->browser = $agent->browser();
            $log->browser_version = $agent->version($log->browser);
            $log->platform = $agent->platform();

            // Location
            if ($log->ip_address !== '127.0.0.1') {
                $position = Location::get($log->ip_address);

                if ($position) {
                    $log->location = $position->cityName . ', ' . $position->countryName;
                } else {
                    $log->location = 'Unknown';
                }
            } else {
                $log->location = 'Localhost';
            }
        }

        return view('superadmin.security.logs', compact('logs'));
    }
    public function export()
    {
        $fileName = 'superadmin_security_logs_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () {

            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Time',
                'Guard',
                'User ID',
                'Event',
                'IP',
                'User Agent',
                'Description',
                'Payload'
            ]);

            SecurityLog::where('guard', 'superadmin')
                ->latest()
                ->chunk(500, function ($logs) use ($handle) {

                    foreach ($logs as $log) {

                        fputcsv($handle, [
                            $log->created_at,
                            $log->guard,
                            $log->user_id,
                            $log->event,
                            $log->ip_address,
                            $log->user_agent,
                            $log->description,
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

}
