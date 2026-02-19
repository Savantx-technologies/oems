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
            ->with(['user' => function ($q) {
                $q->withTrashed();
            }])
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
                    $expiresAt = $attempt->expires_at->copy()->addSeconds($attempt->extra_time_seconds ?? 0);
                    $remaining = (int) now()->diffInSeconds($expiresAt, false);
                }
                $isIdle = $attempt->last_activity_at && now()->diffInSeconds($attempt->last_activity_at) > 30;
                if ($attempt->status !== 'in_progress') $isIdle = false;
                // Handle missing user gracefully
                $user = $attempt->user;
                return [
                    'id' => $attempt->id,
                    'student_name' => $user ? $user->name : 'Unknown',
                    'admission_number' => $user ? $user->admission_number : null,
                    'photo_url' => ($user && $user->photo) ? asset('storage/' . $user->photo) : null,
                    'status' => $attempt->status,
                    'started_at' => $attempt->started_at ? $attempt->started_at->format('H:i:s') : null,
                    'remaining_seconds' => max(0, $remaining),
                    'last_activity_ago' => $attempt->last_activity_at ? now()->diffInSeconds($attempt->last_activity_at) : null,
                    'is_idle' => $isIdle,
                    'violation_count' => $violationCount,
                    'terminated_reason' => $attempt->terminated_reason
                ];
            });

        return response()->json(['attempts' => $attempts]);
    }

    public function requestStream(Request $request, $attemptId)
    {
        $admin = auth('admin')->user();
        $attempt = ExamAttempt::where('school_id', $admin->school_id)->findOrFail($attemptId);

        $stream = \App\Models\ExamStream::create([
            'attempt_id' => $attempt->id,
            'viewer_id' => $admin->id,
            'viewer_type' => get_class($admin),
            'viewer_session_id' => $request->input('session_id'),
            'status' => 'requesting'
        ]);

        return response()->json(['stream_id' => $stream->id]);
    }

    public function viewerSignal(Request $request, $streamId)
    {
        $admin = auth('admin')->user();
        $stream = \App\Models\ExamStream::where('viewer_id', $admin->id)->where('viewer_type', get_class($admin))->findOrFail($streamId);

        if ($request->type === 'answer') {
            $stream->update(['answer' => $request->payload]);
            return response()->json(['status' => 'saved']);
        }

        if ($request->type === 'viewer_ice') {
            $stream->viewer_ice_candidates .= $request->payload . '||';
            $stream->save();
            return response()->json(['status' => 'ice_saved']);
        }

        return response()->json(['status' => 'invalid_type'], 400);
    }

    public function pollViewer(Request $request, $streamId)
    {
        $admin = auth('admin')->user();
        $stream = \App\Models\ExamStream::where('viewer_id', $admin->id)->where('viewer_type', get_class($admin))->findOrFail($streamId);

        $offer = $stream->offer;
        $ice = $stream->student_ice_candidates;

        // Consume the data after reading to prevent re-sending
        $stream->update(['student_ice_candidates' => null]);

        return response()->json(['offer' => $offer, 'ice_candidates' => $ice]);
    }
}
