<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use App\Models\UserExamAnswer;
use Illuminate\Http\Request;

class AdminResultController extends Controller
{
    public function pending()
    {
        $attempts = ExamAttempt::with(['user', 'exam'])
            ->where('approval_status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.results.pending', compact('attempts'));
    }

    public function approve($id)
    {
        $attempt = ExamAttempt::findOrFail($id);

        $attempt->update([
            'approval_status' => 'approved'
        ]);

        return back()->with('success', 'Result approved successfully');
    }

    public function reject($id)
    {
        $attempt = ExamAttempt::findOrFail($id);

        $attempt->update([
            'approval_status' => 'rejected'
        ]);

        return back()->with('success', 'Result rejected');
    }

    public function list(Request $request)
    {
        $query = ExamAttempt::with(['user', 'exam'])
            ->where('approval_status', 'approved');

        $attempts = $query->get();

        // If class not selected → show class cards
        if (!$request->filled('class')) {

            $classes = $attempts
                ->groupBy(fn($item) => $item->exam->class);

            return view('admin.results.list', compact('classes'));
        }

        // If class selected → show students of that class
        $class = $request->class;

        $students = $attempts
            ->where('exam.class', $class)
            ->groupBy('user_id');

        return view('admin.results.list', compact('students', 'class'));
    }

  public function attempts(Request $request)
{
    $query = ExamAttempt::with(['user','exam'])
        ->whereNotNull('submitted_at');

    $attempts = $query->get();

    // If class not selected → show class cards
    if(!$request->filled('class')){

        $classes = $attempts
            ->groupBy(fn($item) => $item->exam->class);

        return view('admin.results.attempts', compact('classes'));
    }

    // If class selected → show students of that class
    $class = $request->class;

    $students = $attempts
        ->where('exam.class',$class)
        ->groupBy('user_id');

    return view('admin.results.attempts', compact('students','class'));
}
    public function viewAttempt($id)
    {
        $attempt = ExamAttempt::with(['user', 'exam', 'answers'])->findOrFail($id);

        $questionIds = json_decode($attempt->question_order, true);

        $questions = \App\Models\Question::whereIn('id', $questionIds)->get();

        return view('admin.results.view-attempt', compact(
            'attempt',
            'questions'
        ));
    }

public function markCorrect($id)
{
    $answer = UserExamAnswer::with('question','attempt')->findOrFail($id);

    $answer->update([
        'is_correct' => 1,
        'marks_awarded' => $answer->question->marks,
        'admin_checked' => 1
    ]);

    $this->recalculateScore($answer->attempt);

    return response()->json([
        'status' => 'correct',
        'marks' => $answer->question->marks
    ]);
}

public function markWrong($id)
{
    $answer = UserExamAnswer::with('question','attempt')->findOrFail($id);

    $answer->update([
        'is_correct' => 0,
        'marks_awarded' => 0,
        'admin_checked' => 1
    ]);

    $this->recalculateScore($answer->attempt);

    return response()->json([
        'status' => 'wrong'
    ]);
}

private function recalculateScore($attempt)
{
    $total = $attempt->answers()->sum('marks_awarded');

    $attempt->update([
        'score' => $total
    ]);
}
}
