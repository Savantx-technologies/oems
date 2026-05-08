<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.reports.exams');
    }

    public function exams(Request $request)
    {
        $admin = auth('admin')->user();

        $query = Exam::where('school_id', $admin->school_id);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Assuming 'attempts' relationship exists on Exam model
        $exams = $query->withCount('attempts')->latest()->paginate(15);

        return view('admin.reports.exams', compact('exams'));
    }

    public function examDetail($id)
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $attemptsQuery = ExamAttempt::where('exam_id', $id)
            ->with('user')
            ->latest();

        $attempts = (clone $attemptsQuery)->paginate(20);

        $rankedAttempts = ExamAttempt::where('exam_id', $id)
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('score')
            ->orderBy('submitted_at')
            ->orderBy('id')
            ->get();

        $rankingMeta = $this->buildRankingMeta($rankedAttempts, $exam->total_marks);

        $totalAttempts = ExamAttempt::where('exam_id', $id)->count();
        
        // Statistics
        $avgScore = ExamAttempt::where('exam_id', $id)->avg('score') ?? 0;
        $maxScore = ExamAttempt::where('exam_id', $id)->max('score') ?? 0;
        $minScore = ExamAttempt::where('exam_id', $id)->min('score') ?? 0;
        $passMarks = is_numeric($exam->pass_marks) ? (float) $exam->pass_marks : null;
        $passed = $passMarks !== null
            ? ExamAttempt::where('exam_id', $id)->where('score', '>=', $passMarks)->count()
            : 0;

        return view('admin.reports.exam_detail', compact(
            'exam',
            'attempts',
            'totalAttempts',
            'avgScore',
            'maxScore',
            'minScore',
            'passed',
            'rankingMeta'
        ));
    }

    public function analytics(Request $request)
    {
        $admin = auth('admin')->user();
        $schoolId = $admin->school_id;

        // Overall Stats
        $examIds = Exam::where('school_id', $schoolId)->pluck('id');

        $totalExams = $examIds->count();
        $totalAttempts = ExamAttempt::whereIn('exam_id', $examIds)->count();
        $overallAvgScore = ExamAttempt::whereIn('exam_id', $examIds)->avg('score');
        $totalStudents = User::where('school_id', $schoolId)->where('role', 'student')->count();

        // Performance by Subject
        $subjectPerformance = DB::table('exams')
            ->join('exam_attempts', 'exams.id', '=', 'exam_attempts.exam_id')
            ->where('exams.school_id', $schoolId)
            ->where('exams.total_marks', '>', 0)
            ->select(
                'exams.subject',
                DB::raw('AVG(exam_attempts.score / exams.total_marks * 100) as average_percentage')
            )
            ->groupBy('exams.subject')
            ->orderBy('average_percentage', 'desc')
            ->limit(10) // Limit for display
            ->get();

        // Performance by Class
        $classPerformance = DB::table('exams')
            ->join('exam_attempts', 'exams.id', '=', 'exam_attempts.exam_id')
            ->where('exams.school_id', $schoolId)
            ->where('exams.total_marks', '>', 0)
            ->select(
                'exams.class',
                DB::raw('AVG(exam_attempts.score / exams.total_marks * 100) as average_percentage')
            )
            ->groupBy('exams.class')
            ->orderBy('exams.class')
            ->get();

        return view('admin.reports.analytics', compact(
            'totalExams', 'totalAttempts', 'overallAvgScore', 'totalStudents', 'subjectPerformance', 'classPerformance'
        ));
    }

    public function exportExamDetail($id)
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $attempts = ExamAttempt::where('exam_id', $id)
            ->with('user')
            ->latest()
            ->get();

        $rankedAttempts = ExamAttempt::where('exam_id', $id)
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('score')
            ->orderBy('submitted_at')
            ->orderBy('id')
            ->get();

        $rankingMeta = $this->buildRankingMeta($rankedAttempts, $exam->total_marks);

        $fileName = 'exam_report_' . $exam->id . '_' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($attempts, $exam, $rankingMeta) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Student Name',
                'Admission Number',
                'Level',
                'Overall Position',
                'Level Position',
                'Status',
                'Score',
                'Total Marks',
                'Percentage',
                'Submitted At',
            ]);

            // Data rows
            foreach ($attempts as $attempt) {
                $percentage = $exam->total_marks > 0 ? (($attempt->score ?? 0) / $exam->total_marks) * 100 : 0;
                $attemptRank = $rankingMeta['attemptRanks'][$attempt->id] ?? null;
                fputcsv($file, [
                    $attempt->user->name ?? 'Unknown Student',
                    $attempt->user->admission_number ?? '-',
                    $attemptRank['level'] ?? ($attempt->user->grade ?? 'Unassigned'),
                    $attemptRank['overall_rank'] ?? '-',
                    $attemptRank['level_rank'] ?? '-',
                    ucfirst(str_replace('_', ' ', $attempt->status)),
                    $attempt->score ?? 0,
                    $exam->total_marks,
                    round($percentage, 2) . '%',
                    $attempt->updated_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildRankingMeta($rankedAttempts, $totalMarks): array
    {
        $attemptRanks = [];
        $levelGroups = [];
        $overallTopper = null;

        foreach ($rankedAttempts->values() as $index => $attempt) {
            $level = $attempt->user?->grade ?: 'Unassigned';
            $levelGroups[$level] ??= collect();

            $levelRank = $levelGroups[$level]->count() + 1;
            $overallRank = $index + 1;
            $percentage = $totalMarks > 0
                ? round((($attempt->score ?? 0) / $totalMarks) * 100, 2)
                : 0;

            $rankPayload = [
                'attempt' => $attempt,
                'overall_rank' => $overallRank,
                'level_rank' => $levelRank,
                'level' => $level,
                'percentage' => $percentage,
            ];

            $attemptRanks[$attempt->id] = $rankPayload;
            $levelGroups[$level]->push($rankPayload);

            if ($overallTopper === null) {
                $overallTopper = $rankPayload;
            }
        }

        return [
            'attemptRanks' => $attemptRanks,
            'levelGroups' => collect($levelGroups)->sortKeys(),
            'overallTopper' => $overallTopper,
            'rankedCount' => count($attemptRanks),
        ];
    }

    public function examViolations($id)
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $violations = DB::table('exam_violations')
            ->join('exam_attempts', 'exam_violations.attempt_id', '=', 'exam_attempts.id')
            ->join('users', 'exam_violations.user_id', '=', 'users.id')
            ->where('exam_attempts.exam_id', $id)
            ->select(
                'exam_violations.*',
                'users.name as student_name',
                'users.admission_number',
                'users.email'
            )
            ->orderBy('exam_violations.created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.exam_violations', compact('exam', 'violations'));
    }

    public function exportExamViolations($id)
    {
        $admin = auth('admin')->user();
        $exam = Exam::where('school_id', $admin->school_id)->findOrFail($id);

        $violations = DB::table('exam_violations')
            ->join('exam_attempts', 'exam_violations.attempt_id', '=', 'exam_attempts.id')
            ->join('users', 'exam_violations.user_id', '=', 'users.id')
            ->where('exam_attempts.exam_id', $id)
            ->select(
                'exam_violations.*',
                'users.name as student_name',
                'users.admission_number',
                'users.email'
            )
            ->orderBy('exam_violations.created_at', 'desc')
            ->get();

        $fileName = 'violation_report_' . $exam->id . '_' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($violations) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Student Name',
                'Admission Number',
                'Email',
                'Violation Type',
                'Timestamp',
                'IP Address',
            ]);

            // Data rows
            foreach ($violations as $violation) {
                fputcsv($file, [
                    $violation->student_name,
                    $violation->admission_number,
                    $violation->email,
                    ucfirst(str_replace('_', ' ', $violation->type)),
                    \Carbon\Carbon::parse($violation->occurred_at)->format('d M Y H:i:s'),
                    $violation->ip_address,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
