<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamStream;
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
                $user = $attempt->user;
                return [
                    'id' => $attempt->id,
                    'student_name' => $user ? $user->name : 'Unknown',
                    'admission_number' => $user ? $user->admission_number : null,
                    'photo_url' => ($user && $user->photo) ? asset('storage/' . $user->photo) : null,
                    'status' => $attempt->status,
                    'remaining_seconds' => max(0, $remaining),
                    'is_idle' => $isIdle,
                    'violation_count' => $violationCount,
                    'terminated_reason' => $attempt->terminated_reason
                ];
            });

        return response()->json(['attempts' => $attempts]);
    }

    public function requestStream(Request $request, $attemptId)
    {
        $superadmin = auth('superadmin')->user();
        $attempt = ExamAttempt::findOrFail($attemptId);

        $stream = ExamStream::create([
            'attempt_id' => $attempt->id,
            'viewer_id' => $superadmin->id,
            'viewer_type' => get_class($superadmin),
            'viewer_session_id' => $request->input('session_id'),
            'status' => 'requesting'
        ]);

        return response()->json(['stream_id' => $stream->id]);
    }

    public function viewerSignal(Request $request, $streamId)
    {
        $superadmin = auth('superadmin')->user();
        $stream = ExamStream::where('viewer_id', $superadmin->id)->where('viewer_type', get_class($superadmin))->findOrFail($streamId);

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
        $superadmin = auth('superadmin')->user();
        $stream = ExamStream::where('viewer_id', $superadmin->id)->where('viewer_type', get_class($superadmin))->findOrFail($streamId);

        $offer = $stream->offer;
        $ice = $stream->student_ice_candidates;

        if ($ice) {
            $stream->update(['student_ice_candidates' => null]);
        }

        return response()->json(['offer' => $offer, 'ice_candidates' => $ice]);
    }
}
