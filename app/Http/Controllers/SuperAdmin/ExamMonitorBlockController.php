<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamMonitorBlock;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamMonitorBlockController extends Controller
{
    public function index($examId)
    {
        $exam = Exam::with([
            'monitorBlocks' => fn($q) => $q->where('assignee_type', SuperAdmin::class),
            'monitorBlocks.attempts.user',
            'monitorBlocks.students',
            'attempts.user',
            'attempts.monitorBlock',
            'school',
        ])->findOrFail($examId);

        $rosterStudents = User::where('role', 'student')
            ->where('school_id', $exam->school_id)
            ->where('grade', $exam->class)
            ->orderBy('name')
            ->get();

        $attemptsByUser = ExamAttempt::where('exam_id', $exam->id)
            ->get()
            ->keyBy('user_id');

        $assignableSubSuperAdmins = SuperAdmin::where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->filter(fn (SuperAdmin $user) => $user->canAccessSection('live_monitoring'))
            ->values();

        return view('superadmin.exams.monitor-blocks', compact('exam', 'assignableSubSuperAdmins', 'rosterStudents', 'attemptsByUser'));
    }

    public function store(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'assignee_id' => [
                'nullable',
                Rule::exists('super_admins', 'id')->where(fn ($query) => $query->where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)),
            ],
            'attempt_ids' => ['nullable', 'array'],
            'attempt_ids.*' => [
                Rule::exists('exam_attempts', 'id')->where(fn ($query) => $query->where('exam_id', $exam->id)),
            ],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => [
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'student')
                    ->where('school_id', $exam->school_id)
                    ->where('grade', $exam->class)),
            ],
        ]);

        $block = ExamMonitorBlock::create([
            'exam_id' => $exam->id,
            'name' => $request->name,
            'assignee_type' => SuperAdmin::class,
            'assignee_id' => $request->assignee_id,
        ]);

        $studentIds = collect($request->input('student_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (!empty($studentIds)) {
            \DB::table('exam_monitor_block_student')
                ->join('exam_monitor_blocks', 'exam_monitor_blocks.id', '=', 'exam_monitor_block_student.exam_monitor_block_id')
                ->where('exam_monitor_blocks.exam_id', $exam->id)
                ->whereIn('exam_monitor_block_student.user_id', $studentIds)
                ->delete();

            $block->students()->syncWithoutDetaching($studentIds);

            ExamAttempt::where('exam_id', $exam->id)
                ->whereIn('user_id', $studentIds)
                ->update(['monitor_block_id' => $block->id]);
        }

        if ($request->filled('attempt_ids')) {
            ExamAttempt::whereIn('id', $request->attempt_ids)
                ->where('exam_id', $exam->id)
                ->update(['monitor_block_id' => $block->id]);
        }

        return back()->with('success', 'Monitoring block created successfully.');
    }

    public function update(Request $request, $examId, ExamMonitorBlock $block)
    {
        $exam = Exam::findOrFail($examId);

        abort_unless($block->exam_id === $exam->id, 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'assignee_id' => [
                'nullable',
                Rule::exists('super_admins', 'id')->where(fn ($query) => $query->where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)),
            ],
        ]);

        $block->update([
            'name' => $request->name,
            'assignee_type' => SuperAdmin::class,
            'assignee_id' => $request->assignee_id,
        ]);

        return back()->with('success', 'Monitoring block updated successfully.');
    }

    public function moveAttempt(Request $request, $examId, ExamAttempt $attempt)
    {
        $exam = Exam::findOrFail($examId);

        abort_unless($attempt->exam_id === $exam->id, 404);

        $request->validate([
            'monitor_block_id' => [
                'nullable',
                Rule::exists('exam_monitor_blocks', 'id')->where(fn ($query) => $query->where('exam_id', $exam->id)),
            ],
        ]);

        $attempt->update([
            'monitor_block_id' => $request->monitor_block_id,
        ]);

        return back()->with('success', 'Student moved successfully.');
    }

    public function moveStudent(Request $request, $examId, User $student)
    {
        $exam = Exam::findOrFail($examId);

        abort_unless(
            $student->role === 'student'
            && (int) $student->school_id === (int) $exam->school_id
            && (string) $student->grade === (string) $exam->class,
            404
        );

        $request->validate([
            'monitor_block_id' => [
                'nullable',
                Rule::exists('exam_monitor_blocks', 'id')->where(fn ($query) => $query->where('exam_id', $exam->id)),
            ],
        ]);

        \DB::table('exam_monitor_block_student')
            ->join('exam_monitor_blocks', 'exam_monitor_blocks.id', '=', 'exam_monitor_block_student.exam_monitor_block_id')
            ->where('exam_monitor_blocks.exam_id', $exam->id)
            ->where('exam_monitor_block_student.user_id', $student->id)
            ->delete();

        if ($request->filled('monitor_block_id')) {
            $block = ExamMonitorBlock::where('exam_id', $exam->id)->findOrFail($request->monitor_block_id);
            $block->students()->syncWithoutDetaching([$student->id]);
        }

        ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $student->id)
            ->update(['monitor_block_id' => $request->monitor_block_id]);

        return back()->with('success', 'Student block updated successfully.');
    }

    public function bulkMoveStudents(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => [
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'student')
                    ->where('school_id', $exam->school_id)
                    ->where('grade', $exam->class)),
            ],
            'monitor_block_id' => [
                'nullable',
                Rule::exists('exam_monitor_blocks', 'id')->where(fn ($query) => $query->where('exam_id', $exam->id)),
            ],
        ]);

        $studentIds = collect($request->input('student_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        \DB::table('exam_monitor_block_student')
            ->join('exam_monitor_blocks', 'exam_monitor_blocks.id', '=', 'exam_monitor_block_student.exam_monitor_block_id')
            ->where('exam_monitor_blocks.exam_id', $exam->id)
            ->whereIn('exam_monitor_block_student.user_id', $studentIds)
            ->delete();

        if ($request->filled('monitor_block_id')) {
            $block = ExamMonitorBlock::where('exam_id', $exam->id)->findOrFail($request->monitor_block_id);
            $block->students()->syncWithoutDetaching($studentIds);
        }

        ExamAttempt::where('exam_id', $exam->id)
            ->whereIn('user_id', $studentIds)
            ->update(['monitor_block_id' => $request->monitor_block_id]);

        return back()->with('success', count($studentIds) . ' students assigned successfully.');
    }

    public function autoAssign(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        $request->validate([
            'students_per_block' => ['required', 'integer', 'min:1', 'max:100'],
            'assignee_ids' => ['nullable', 'array'],
            'assignee_ids.*' => [
                Rule::exists('super_admins', 'id')->where(fn ($query) => $query->where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)),
            ],
        ]);

        $students = User::where('role', 'student')
            ->where('school_id', $exam->school_id)
            ->where('grade', $exam->class)
            ->orderBy('id')
            ->get()
            ->values();

        ExamAttempt::where('exam_id', $exam->id)->update(['monitor_block_id' => null]);
        ExamMonitorBlock::where('exam_id', $exam->id)->delete();

        $chunks = $students->chunk((int) $request->students_per_block);
        $assigneeIds = array_values($request->input('assignee_ids', []));

        foreach ($chunks as $index => $chunk) {
            $block = ExamMonitorBlock::create([
                'exam_id' => $exam->id,
                'name' => 'Block ' . $this->alphabetLabel($index + 1),
                'assignee_type' => !empty($assigneeIds) ? SuperAdmin::class : null,
                'assignee_id' => !empty($assigneeIds) ? $assigneeIds[$index % count($assigneeIds)] : null,
            ]);

            $studentIds = $chunk->pluck('id')->all();
            $block->students()->syncWithoutDetaching($studentIds);

            ExamAttempt::where('exam_id', $exam->id)
                ->whereIn('user_id', $studentIds)
                ->update([
                'monitor_block_id' => $block->id,
            ]);
        }

        return back()->with('success', 'Students auto-assigned into monitoring blocks successfully.');
    }

    public function destroy($examId, ExamMonitorBlock $block)
    {
        $exam = Exam::findOrFail($examId);

        abort_unless($block->exam_id === $exam->id, 404);

        ExamAttempt::where('monitor_block_id', $block->id)->update(['monitor_block_id' => null]);
        $block->students()->detach();
        $block->delete();

        return back()->with('success', 'Monitoring block removed successfully.');
    }

    private function alphabetLabel(int $position): string
    {
        $label = '';

        while ($position > 0) {
            $position--;
            $label = chr(65 + ($position % 26)) . $label;
            $position = intdiv($position, 26);
        }

        return $label;
    }
}
