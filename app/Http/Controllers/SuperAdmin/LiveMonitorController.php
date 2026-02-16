<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\DB;

class LiveMonitorController extends Controller
{
    public function index($id)
    {
        $exam = Exam::with('school')->findOrFail($id);
        return view('superadmin.exams.monitor', compact('exam'));
    }

    public function data($id)
    {
        $exam = Exam::findOrFail($id);

        $attempts = ExamAttempt::where('exam_id', $id)
            ->with('user:id,name,admission_number,photo')
            ->select([
                'id',
                'user_id',
                'status',
                'started_at',
                'expires_at',
                'last_activity_at',
                'extra_time_seconds',
                'terminated_reason'
            ])
            ->get()
            ->map(function ($attempt) {
                $violationCount = DB::table('exam_violations')->where('attempt_id', $attempt->id)->count();

                $remaining = 0;

                if ($attempt->expires_at) {
                    $expiresAt = $attempt->expires_at->copy()
                        ->addSeconds($attempt->extra_time_seconds ?? 0);

                    $remaining = (int) now()->diffInSeconds($expiresAt, false);
                }

                $isIdle = $attempt->last_activity_at && now()->diffInSeconds($attempt->last_activity_at) > 30;
                if ($attempt->status !== 'in_progress') $isIdle = false;

                return [
                    'id' => $attempt->id,
                    'student_name' => $attempt->user->name,
                    'admission_number' => $attempt->user->admission_number,
                    'photo_url' => $attempt->user->photo ? asset('storage/' . $attempt->user->photo) : null,
                    'status' => $attempt->status,
                    'started_at' => $attempt->started_at->format('H:i:s'),
                    'remaining_seconds' => max(0, $remaining),
                    'last_activity_ago' => $attempt->last_activity_at ? now()->diffInSeconds($attempt->last_activity_at) : null,
                    'is_idle' => $isIdle,
                    'violation_count' => $violationCount,
                    'terminated_reason' => $attempt->terminated_reason
                ];
            });

        return response()->json(['attempts' => $attempts]);
    }

    public function stream($attemptId)
    {
        $stream = DB::table('exam_streams')->where('attempt_id', $attemptId)->first();

        return response()->json([
            'offer' => $stream ? $stream->offer : null,
            'answer' => $stream ? $stream->answer : null,
            'student_ice_candidates' => $stream ? $stream->student_ice_candidates : null
        ]);
    }

    public function sendSignal(Request $request, $attemptId)
    {
        // SuperAdmin generally views streams (receives offers/answers) but might need to send ICE candidates if initiating connection
        // For viewing, the logic is handled mostly client-side via the offer/answer exchange initiated by the viewer.
        return response()->json(['status' => 'ok']);
    }
}
