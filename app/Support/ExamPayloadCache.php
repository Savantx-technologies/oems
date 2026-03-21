<?php

namespace App\Support;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Facades\Cache;

class ExamPayloadCache
{
    private const TTL_SECONDS = 3600;

    public static function key(int $examId): string
    {
        return "exam_payload:{$examId}";
    }

    public static function getOrWarm(Exam $exam): array
    {
        return Cache::remember(
            static::key($exam->id),
            now()->addSeconds(self::TTL_SECONDS),
            fn () => static::buildPayload($exam)
        );
    }

    public static function warm(Exam $exam): array
    {
        $payload = static::buildPayload($exam);

        Cache::put(static::key($exam->id), $payload, now()->addSeconds(self::TTL_SECONDS));

        return $payload;
    }

    public static function forget(int $examId): void
    {
        Cache::forget(static::key($examId));
    }

    private static function buildPayload(Exam $exam): array
    {
        $questionIds = $exam->selected_questions;

        if (is_string($questionIds)) {
            $questionIds = json_decode($questionIds, true) ?? [];
        }

        $questionIds = collect($questionIds ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        if (empty($questionIds)) {
            return [];
        }

        $questions = Question::whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        return collect($questionIds)
            ->map(function (int $questionId) use ($questions) {
                $question = $questions->get($questionId);

                if (!$question) {
                    return null;
                }

                return [
                    'id' => $question->id,
                    'text' => $question->question_text,
                    'marks' => $question->marks,
                    'correct_option' => $question->correct_option,
                    'options' => collect([
                        ['id' => 'A', 'text' => $question->option_a],
                        ['id' => 'B', 'text' => $question->option_b],
                        ['id' => 'C', 'text' => $question->option_c],
                        ['id' => 'D', 'text' => $question->option_d],
                    ])->filter(fn ($option) => filled($option['text']))->values()->all(),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
