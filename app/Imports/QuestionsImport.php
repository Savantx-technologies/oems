<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Auth;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;  
class QuestionsImport implements ToModel, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped  = 0;

    protected $admin;

    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    public function model(array $row)
    {
        // minimal validation (same as store)
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

        $correct = strtoupper(trim($row['correct_option']));

        if (! in_array($correct, ['A','B','C','D'])) {
            $this->skipped++;
            return null;
        }

        $this->imported++;

        return new Question([
            'school_id' => $this->admin->school_id,
            'created_by' => $this->admin->id,

            // same fields as single store()
            'difficulty' => $row['difficulty'] ?? 'medium',

            'class' => $row['class'],
            'subject' => $row['subject'],
            'type' => 'mcq',

            'passage_id' => null,

            'question_text' => $row['question_text'],
            'marks' => $row['marks'],

            'option_a' => $row['option_a'] ?? null,
            'option_b' => $row['option_b'] ?? null,
            'option_c' => $row['option_c'] ?? null,
            'option_d' => $row['option_d'] ?? null,

            'correct_option' => $correct,
        ]);
    }
}
