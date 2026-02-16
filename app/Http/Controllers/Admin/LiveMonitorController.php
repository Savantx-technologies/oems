<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\DB;

class LiveMonitorController extends Controller
{
    public function index($id)
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);
        return view('admin.exams.monitor', compact('exam'));
    }

    public function data($id)
    {
        $admin = auth('admin')->user();
        // Verify ownership
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

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

                // ✅ Replacement Block
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
        $admin = auth('admin')->user();
        $attempt = ExamAttempt::where('school_id', $admin->school_id)->findOrFail($attemptId);

        $stream = DB::table('exam_streams')->where('attempt_id', $attemptId)->first();

        return response()->json([
            'offer' => $stream ? $stream->offer : null,
            'answer' => $stream ? $stream->answer : null,
            'student_ice_candidates' => $stream ? $stream->student_ice_candidates : null
        ]);
    }

    public function sendSignal(Request $request, $attemptId)
    {
        $admin = auth('admin')->user();
        $attempt = ExamAttempt::where('school_id', $admin->school_id)->findOrFail($attemptId);

        if ($request->type === 'answer') {
            DB::table('exam_streams')->updateOrInsert(
                ['attempt_id' => $attemptId],
                ['answer' => $request->payload, 'updated_at' => now()]
            );
            return response()->json(['status' => 'saved']);
        }

        if ($request->type === 'admin_ice') {
            DB::table('exam_streams')->updateOrInsert(
                ['attempt_id' => $attemptId],
                [
                    'admin_ice_candidates' => DB::raw("CONCAT(IFNULL(admin_ice_candidates,''), '" . addslashes($request->payload) . "||')"),
                    'updated_at' => now()
                ]
            );
            return response()->json(['status' => 'ice_saved']);
        }
    }
}
