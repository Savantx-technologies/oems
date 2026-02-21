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

            $validQuestionIds = is_string($exam->selected_questions)
                ? json_decode($exam->selected_questions, true)
                : $exam->selected_questions;

            $questions = Question::whereIn('id', $validQuestionIds)
                ->get()
                ->keyBy('id');

            $totalQuestions = $questions->count();
            $totalCorrect = 0;
            $totalScore = 0;

            foreach ($questions as $question) {

                $selectedOption = $answers[$question->id] ?? null;

                if (!in_array($selectedOption, ['A', 'B', 'C', 'D'])) {
                    $selectedOption = null;
                }

                if ($selectedOption === null) {
                    continue;
                }

                $isCorrect = $selectedOption === $question->correct_option;
                $marksAwarded = 0;

                if ($isCorrect) {
                    $marksAwarded = $question->marks;
                    $totalCorrect++;
                } else {
                    if ($exam->negative_marking) {
                        $marksAwarded = -abs($exam->negative_marks);
                    }
                }

                $totalScore += $marksAwarded;

                UserExamAnswer::create([
                    'school_id' => $attempt->school_id,
                    'attempt_id' => $attempt->id,
                    'user_id' => $attempt->user_id,
                    'exam_id' => $attempt->exam_id,
                    'question_id' => $question->id,
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
