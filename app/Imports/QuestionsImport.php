<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class QuestionsImport implements ToModel, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped = 0;

    protected $admin;

    public function __construct($admin)
    {
        $this->admin = $admin;
    }

   public function model(array $row)
{
    if (
        empty($row['class']) ||
        empty($row['subject']) ||
        empty($row['question_text']) ||
        empty($row['marks']) ||
        empty($row['correct_option'])
    ) {
        $this->skipped++;
        return null;
    }

    $optionA = $row['option_a'] ?? null;
    $optionB = $row['option_b'] ?? null;
    $optionC = $row['option_c'] ?? null;
    $optionD = $row['option_d'] ?? null;

    $correct = strtoupper(trim($row['correct_option']));

    // Validate correct option must be A/B/C/D
    if (!in_array($correct, ['A','B','C','D'])) {
        $this->skipped++;
        return null;
    }

    $this->imported++;

    return new Question([
        'school_id' => $this->admin->school_id,
        'created_by' => $this->admin->id,
        'difficulty' => $row['difficulty'] ?? 'medium',
        'class' => $row['class'],
        'subject' => $row['subject'],
        'type' => 'mcq',
        'passage_id' => null,
        'question_text' => $row['question_text'],
        'marks' => $row['marks'],
        'option_a' => $optionA,
        'option_b' => $optionB,
        'option_c' => $optionC,
        'option_d' => $optionD,

        // ✅ Store only A/B/C/D
        'correct_option' => $correct,
    ]);
}



}
