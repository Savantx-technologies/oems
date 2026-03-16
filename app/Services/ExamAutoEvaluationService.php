<?php

namespace App\Services;
use App\Models\Exam;
use App\Models\Question;
use App\Models\UserExamAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\ExamAttempt;

class ExamAutoEvaluationService
{
    /**
     * Create a new class instance.
     */
    public function evaluate(ExamAttempt $attempt, array $answers)
    {
        if ($attempt->submitted_at) {
            return $attempt;
        }

        return DB::transaction(function () use ($attempt, $answers) {

            $exam = Exam::findOrFail($attempt->exam_id);

            $questionOrder = json_decode($attempt->question_order, true);

            $questions = Question::whereIn('id', $questionOrder)
                ->get()
                ->keyBy('id');

            // IMPORTANT
            $answers = array_values($answers);

            $totalQuestions = count($questionOrder);
            $totalCorrect = 0;
            $totalScore = 0;

            foreach ($questionOrder as $index => $questionId) {

                $question = $questions[$questionId] ?? null;

                if (!$question) {
                    continue;
                }

                // correct mapping
                $selectedOption = $answers[$index] ?? null;

                $isCorrect = $selectedOption === $question->correct_option;

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

                UserExamAnswer::create([
                    'school_id' => $attempt->school_id,
                    'attempt_id' => $attempt->id,
                    'user_id' => $attempt->user_id,
                    'exam_id' => $attempt->exam_id,
                    'question_id' => $questionId,
                    'selected_option' => $selectedOption,
                    'is_correct' => $isCorrect,
                    'marks_awarded' => $marksAwarded,
                ]);
            }

            $totalScore = max($totalScore, 0);

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
