<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SecurityLog;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminSecurityLogController extends Controller
{
    public function index()
    {
        $logs = SecurityLog::where('guard', 'admin')
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

        return view('admin.security.logs', compact('logs'));
    }

    public function download()
    {
        view('');
    }


    public function export()
    {
        $fileName = 'admin_security_logs_' . now()->format('Ymd_His') . '.csv';

        return new StreamedResponse(function () {

            $handle = fopen('php://output', 'w');

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

            SecurityLog::where('guard', 'admin')
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

    public function studentIndex()
    {
        $admin = auth('admin')->user();

        // Join with users table to filter by school and get student names
        $logs = SecurityLog::whereIn('guard', ['web', 'student'])
            ->join('users', 'security_logs.user_id', '=', 'users.id')
            ->where('users.school_id', $admin->school_id)
            ->select('security_logs.*', 'users.name as student_name', 'users.email as student_email')
            ->latest('security_logs.created_at')
            ->paginate(50);

        return view('admin.security.student_logs', compact('logs'));
    }

    public function studentExport()
    {
        $fileName = 'student_activity_logs_' . now()->format('Ymd_His') . '.csv';
        $admin = auth('admin')->user();

        return new StreamedResponse(function () use ($admin) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Time', 'Student Name', 'Email', 'Event', 'IP', 'Description']);

            SecurityLog::whereIn('guard', ['web', 'student'])
                ->join('users', 'security_logs.user_id', '=', 'users.id')
                ->where('users.school_id', $admin->school_id)
                ->select('security_logs.*', 'users.name as student_name', 'users.email as student_email')
                ->latest('security_logs.created_at')
                ->chunk(500, function ($logs) use ($handle) {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->created_at,
                            $log->student_name,
                            $log->student_email,
                            $log->event,
                            $log->ip_address,
                            $log->description,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}
