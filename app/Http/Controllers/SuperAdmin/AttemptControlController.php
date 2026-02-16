<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamAttempt;

class AttemptControlController extends Controller
{
    public function terminate(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::findOrFail($attemptId);

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
        $attempt = ExamAttempt::findOrFail($attemptId);
        $minutes = $request->input('minutes', 5);
        $seconds = $minutes * 60;
        $attempt->increment('extra_time_seconds', $seconds);
        return response()->json(['status' => 'extended', 'added_seconds' => $seconds]);
    }
}
