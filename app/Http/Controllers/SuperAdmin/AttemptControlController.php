<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamAttempt;
use App\Support\ExamMonitorAccess;

class AttemptControlController extends Controller
{
    public function terminate(Request $request, $attemptId)
    {
        $superadmin = auth('superadmin')->user();
        $attempt = ExamAttempt::findOrFail($attemptId);
        abort_unless(ExamMonitorAccess::canSuperAdminAccessAttempt($superadmin, $attempt), 403, 'You are not assigned to this student.');

        if ($attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Exam not in progress'], 400);
        }

        $attempt->update([
            'status' => 'terminated',
            'terminated_reason' => 'Terminated by SuperAdmin: ' . ($request->reason ?? 'Manual Action'),
            'submitted_at' => now()
        ]);

        return response()->json(['status' => 'terminated']);
    }

    public function extendTime(Request $request, $attemptId)
    {
        $superadmin = auth('superadmin')->user();
        $attempt = ExamAttempt::findOrFail($attemptId);
        abort_unless(ExamMonitorAccess::canSuperAdminAccessAttempt($superadmin, $attempt), 403, 'You are not assigned to this student.');
        $minutes = $request->input('minutes', 5);
        $seconds = $minutes * 60;
        $attempt->increment('extra_time_seconds', $seconds);
        return response()->json(['status' => 'extended', 'added_seconds' => $seconds]);
    }
}
