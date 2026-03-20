<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamStream;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Support\ExamMonitorAccess;

class LiveMonitorController extends Controller
{
    public function index($id)
    {
        $exam = Exam::with('school')->findOrFail($id);
        $superadmin = auth('superadmin')->user();

        // Super admins (main role) can always access.
        if ($superadmin->role === SuperAdmin::ROLE_SUPERADMIN) {
            return view('superadmin.exams.monitor', compact('exam'));
        }

        // For sub-superadmins, check assignments.
        $anyBlocksAssigned = \App\Models\ExamMonitorBlock::where('exam_id', $exam->id)
            ->where('assignee_type', SuperAdmin::class)
            ->whereNotNull('assignee_id')
            ->exists();

        // If no blocks are assigned to anyone, allow access to match existing behavior.
        if (!$anyBlocksAssigned) {
            return view('superadmin.exams.monitor', compact('exam'));
        }

        // If blocks ARE assigned, check if the current user is an assignee.
        $isCurrentUserAssigned = \App\Models\ExamMonitorBlock::where('exam_id', $exam->id)
            ->where('assignee_type', SuperAdmin::class)
            ->where('assignee_id', $superadmin->id)
            ->exists();

        if (!$isCurrentUserAssigned) {
            abort(403, 'You are not assigned to any monitoring block for this exam.');
        }
        return view('superadmin.exams.monitor', compact('exam'));
    }

    public function data($id)
    {
        $superadmin = auth('superadmin')->user();
        $exam = Exam::findOrFail($id);
        $allowedStudentIds = ExamMonitorAccess::superAdminStudentScope($superadmin, $exam->id);
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
                'remaining_seconds' => max(0, $remaining),
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
        $superadmin = auth('superadmin')->user();
        $attempt = ExamAttempt::findOrFail($attemptId);

        abort_unless(ExamMonitorAccess::canSuperAdminAccessAttempt($superadmin, $attempt), 403, 'You are not assigned to this student.');

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

        abort_unless(ExamMonitorAccess::canSuperAdminAccessAttempt($superadmin, $stream->attempt), 403, 'You are not assigned to this student.');

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

        abort_unless(ExamMonitorAccess::canSuperAdminAccessAttempt($superadmin, $stream->attempt), 403, 'You are not assigned to this student.');

        $offer = $stream->offer;
        $ice = $stream->student_ice_candidates;

        if ($ice) {
            $stream->update(['student_ice_candidates' => null]);
        }

        return response()->json(['offer' => $offer, 'ice_candidates' => $ice]);
    }
}
