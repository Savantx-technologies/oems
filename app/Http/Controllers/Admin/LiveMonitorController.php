<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Support\ExamMonitorAccess;

class LiveMonitorController extends Controller
{
    public function index($id)  
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);
        
        // School admins can always access.
        if ($admin->role === Admin::ROLE_SCHOOL_ADMIN) {
            return view('admin.exams.monitor', compact('exam'));
        }

        // Check if there are any assigned blocks for this exam at all.
        $anyBlocksAssigned = \App\Models\ExamMonitorBlock::where('exam_id', $exam->id)
            ->where('assignee_type', Admin::class)
            ->whereNotNull('assignee_id')
            ->exists();

        // If no blocks are assigned to anyone, allow access for now to match existing behavior.
        if (!$anyBlocksAssigned) {
            return view('admin.exams.monitor', compact('exam'));
        }

        // If blocks ARE assigned, check if the current user is an assignee.
        $isCurrentUserAssigned = \App\Models\ExamMonitorBlock::where('exam_id', $exam->id)
            ->where('assignee_type', Admin::class)
            ->where('assignee_id', $admin->id)
            ->exists();

        if (!$isCurrentUserAssigned) {
            abort(403, 'You are not assigned to any monitoring block for this exam.');
        }
        return view('admin.exams.monitor', compact('exam'));
    }

    public function data($id)
    {
        $admin = auth('admin')->user();
        // Verify ownership
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $allowedStudentIds = ExamMonitorAccess::adminStudentScope($admin, $exam->id);
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
            ->keyBy('user_id');

        if ($allowedStudentIds === null) {
            $students = User::where('role', 'student')
                ->where('school_id', $exam->school_id)
                ->where('grade', $exam->class)
                ->orderBy('name')
                ->get();
        } else {
            $students = User::whereIn('id', $allowedStudentIds)
                ->orderBy('name')
                ->get();
        }

        $roster = $students->map(function ($user) use ($attempts) {
            $attempt = $attempts->get($user->id);
            $violationCount = $attempt ? DB::table('exam_violations')->where('attempt_id', $attempt->id)->count() : 0;
            $remaining = 0;

            if ($attempt && $attempt->expires_at) {
                $expiresAt = $attempt->expires_at->copy()->addSeconds($attempt->extra_time_seconds ?? 0);
                $remaining = (int) now()->diffInSeconds($expiresAt, false);
            }

            $isIdle = $attempt && $attempt->last_activity_at && now()->diffInSeconds($attempt->last_activity_at) > 30;
            if (!$attempt || $attempt->status !== 'in_progress') {
                $isIdle = false;
            }

            return [
                'id' => $user->id,
                'attempt_id' => $attempt?->id,
                'student_name' => $user->name,
                'admission_number' => $user->admission_number,
                'photo_url' => $user->photo ? asset('storage/' . $user->photo) : null,
                'status' => $attempt?->status ?? 'not_started',
                'started_at' => $attempt?->started_at?->format('H:i:s'),
                'remaining_seconds' => max(0, $remaining),
                'last_activity_ago' => $attempt?->last_activity_at ? now()->diffInSeconds($attempt->last_activity_at) : null,
                'is_idle' => $isIdle,
                'violation_count' => $violationCount,
                'terminated_reason' => $attempt?->terminated_reason,
                'has_started' => (bool) $attempt,
            ];
        })->values();

        return response()->json(['attempts' => $roster]);
    }

    public function requestStream(Request $request, $attemptId)
    {
        $admin = auth('admin')->user();
        $attempt = ExamAttempt::where('school_id', $admin->school_id)->findOrFail($attemptId);

        abort_unless(ExamMonitorAccess::canAdminAccessAttempt($admin, $attempt), 403, 'You are not assigned to this student.');

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

        abort_unless(ExamMonitorAccess::canAdminAccessAttempt($admin, $stream->attempt), 403, 'You are not assigned to this student.');

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

        abort_unless(ExamMonitorAccess::canAdminAccessAttempt($admin, $stream->attempt), 403, 'You are not assigned to this student.');

        $offer = $stream->offer;
        $ice = $stream->student_ice_candidates;

        // Consume the data after reading to prevent re-sending
        $stream->update(['student_ice_candidates' => null]);

        return response()->json(['offer' => $offer, 'ice_candidates' => $ice]);
    }
}
