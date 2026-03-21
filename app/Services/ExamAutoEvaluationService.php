<?php

namespace App\Services;
use App\Models\Exam;
use App\Models\Question;
use App\Models\UserExamAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\ExamAttempt;
use Illuminate\Support\Carbon;

class ExamAutoEvaluationService
{
    /**
     * Create a new class instance.
     */
  public function evaluate(ExamAttempt $attempt, array $answers)
{
    // If already submitted then skip
    if ($attempt->submitted_at) {
        return $attempt;
    }

    return DB::transaction(function () use ($attempt, $answers) {

        $exam = Exam::findOrFail($attempt->exam_id);

        // Decode question order
        $questionOrder = json_decode($attempt->question_order, true);

        // Safety: convert to integer ids
        $questionOrder = array_map('intval', $questionOrder);

        // Get questions
        $questions = Question::whereIn('id', $questionOrder)
            ->get()
            ->keyBy('id');

        $totalQuestions = count($questionOrder);
        $totalCorrect = 0;
        $totalScore = 0;

        $rows = [];
        $timestamp = Carbon::now();

        foreach ($questionOrder as $questionId) {

            $question = $questions[$questionId] ?? null;

            if (!$question) {
                continue;
            }

            // Get selected option from answers
            $selectedOption = $answers[$questionId] ?? null;

            // Check correct
            $isCorrect = $selectedOption && ($selectedOption == $question->correct_option);

            $marksAwarded = 0;

            if ($selectedOption !== null) {

                if ($isCorrect) {
                    $marksAwarded = $question->marks;
                    $totalCorrect++;
                } elseif ($exam->negative_marking) {
                    $marksAwarded = -abs($exam->negative_marks);
                }

                $totalScore += $marksAwarded;
            }

            // Save student answer
            $rows[] = [
                'school_id' => $attempt->school_id,
                'attempt_id' => $attempt->id,
                'user_id' => $attempt->user_id,
                'exam_id' => $attempt->exam_id,
                'question_id' => $questionId,
                'selected_option' => $selectedOption,
                'is_correct' => $isCorrect ? 1 : 0,
                'admin_checked' => 0,
                'marks_awarded' => $marksAwarded,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        if (!empty($rows)) {
            UserExamAnswer::insert($rows);
        }

        // Score cannot be negative
        $totalScore = max($totalScore, 0);

        // Update attempt result
        $attempt->update([
            'total_questions' => $totalQuestions,
            'total_correct' => $totalCorrect,
            'score' => $totalScore,
            'submitted_at' => now(),
            'status' => 'evaluated',
            'approval_status' => 'pending',
        ]);

        return $attempt;
    });
}
}
