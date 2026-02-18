<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passage;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = Question::where('school_id', $admin->school_id);

        // Apply search if entered
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // If numeric → filter by class
                if (is_numeric($search)) {
                    $q->where('class', $search);
                }

                // Always allow subject search
                $q->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->paginate(20);

        // Difficulty enum logic (unchanged)
        $type = DB::selectOne("
        SHOW COLUMNS FROM questions WHERE Field = 'difficulty'
    ");

        preg_match("/^enum\((.*)\)$/", $type->Type, $matches);

        $difficulties = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return view('admin.questions.index', compact('questions', 'difficulties'));
    }


    public function create()
    {
        $admin = auth('admin')->user();

        $recentIds = session()->get('recent_questions', []);

        $questions = Question::where('school_id', $admin->school_id)
            ->whereIn('id', $recentIds)
            ->latest()
            ->get();

        $total = Question::where('school_id', $admin->school_id)->count();

        $passages = Passage::where('school_id', $admin->school_id)
            ->latest()
            ->get();
        $type = DB::selectOne("
        SHOW COLUMNS FROM questions WHERE Field = 'difficulty'
    ");

        preg_match("/^enum\((.*)\)$/", $type->Type, $matches);

        $difficulties = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return view('admin.questions.create', compact(
            'questions',
            'total',
            'passages',
            'difficulties'
        ));
    }



    public function store(Request $request)
    {
        $request->validate([

            'class' => 'required|string',
            'subject' => 'required|string',
            'type' => 'required|in:mcq,subjective,summary',

            'passage_id' => 'required_if:type,summary|nullable|exists:passages,id',

            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'difficulty' => 'required',
            // only for mcq
            'option_a' => 'required_if:type,mcq',
            'option_b' => 'required_if:type,mcq',
            'option_c' => 'required_if:type,mcq',
            'option_d' => 'required_if:type,mcq',
            'correct_option' => 'required_if:type,mcq|string'

        ]);

        $admin = auth('admin')->user();

        DB::beginTransaction();

        try {
            $correctOptionText = null;

            if ($request->type === 'mcq') {

                $selected = $request->correct_option;

                $options = [
                    'A' => $request->option_a,
                    'B' => $request->option_b,
                    'C' => $request->option_c,
                    'D' => $request->option_d,
                ];

                $correctOptionText = $options[$selected] ?? null;
            }


            $question = Question::forceCreate([

                'school_id' => $admin->school_id,
                'created_by' => $admin->id,
                'difficulty' => $request->difficulty,
                'class' => $request->class,
                'subject' => $request->subject,
                'type' => $request->type,

                'passage_id' => $request->passage_id,

                'question_text' => $request->question_text,
                'marks' => $request->marks,

                'option_a' => $request->type == 'mcq' ? $request->option_a : null,
                'option_b' => $request->type == 'mcq' ? $request->option_b : null,
                'option_c' => $request->type == 'mcq' ? $request->option_c : null,
                'option_d' => $request->type == 'mcq' ? $request->option_d : null,

                'correct_option' => $correctOptionText,


            ]);
            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;

        }

        /* Store recent questions for current session */
        $recent = session()->get('recent_questions', []);
        $recent[] = $question->id;
        session()->put('recent_questions', array_unique($recent));


        /* Save & Add More (AJAX) */
        if ($request->ajax() && $request->has('save_add_more')) {

            return response()->json([
                'id' => $question->id,
                'class' => $question->class,
                'subject' => $question->subject,
                'type' => $question->type,
                'question_text' => $question->question_text,
                'marks' => $question->marks,
            ]);

        }

        /* Normal submit */
        return redirect()->route(
            $request->has('save_add_more')
            ? 'admin.questions.create'
            : 'admin.questions.index'
        );
    }



    public function bulkForm()
    {
        return view('admin.questions.bulk-upload');
    }
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx'
        ]);

        $admin = auth('admin')->user();

        $import = new QuestionsImport($admin);

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('admin.questions.index')
            ->with(
                'success',
                "Questions imported successfully. 
             Imported: {$import->imported}, 
             Skipped: {$import->skipped}"
            );
    }


    public function edit(Question $question)
    {
        $admin = auth('admin')->user();

        abort_if($question->school_id != $admin->school_id, 403);

        $type = DB::selectOne("
        SHOW COLUMNS FROM questions WHERE Field = 'difficulty'
    ");

        preg_match("/^enum\((.*)\)$/", $type->Type, $matches);

        $difficulties = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return view('admin.questions.edit', compact('question', 'difficulties'));
    }


    public function update(Request $request, Question $question)
    {
        $admin = auth('admin')->user();

        abort_if($question->school_id != $admin->school_id, 403);

        $request->validate([
            'class' => 'required',
            'subject' => 'required',
            'type' => 'required|in:mcq,subjective,summary',
            'question_text' => 'required',
            'marks' => 'required|integer|min:1',

            'option_a' => 'required_if:type,mcq',
            'option_b' => 'required_if:type,mcq',
            'option_c' => 'required_if:type,mcq',
            'option_d' => 'required_if:type,mcq',
            'difficulty' => 'required',
            'correct_option' => 'required_if:type,mcq|string'
        ]);

        DB::transaction(function () use ($request, $question) {

            $type = in_array($request->type, ['mcq', 'subjective', 'summary'])
                ? $request->type
                : 'mcq';
            $correctOptionText = null;

            if ($type === 'mcq') {

                $options = [
                    'A' => $request->option_a,
                    'B' => $request->option_b,
                    'C' => $request->option_c,
                    'D' => $request->option_d,
                ];

                $correctOptionText = $options[$request->correct_option] ?? null;
            }

            $question->forceFill([

                'class' => $request->class,
                'subject' => $request->subject,
                'type' => $type,
                'question_text' => $request->question_text,
                'marks' => $request->marks,
                'difficulty' => $request->difficulty,
                'option_a' => $type === 'mcq' ? $request->option_a : null,
                'option_b' => $type === 'mcq' ? $request->option_b : null,
                'option_c' => $type === 'mcq' ? $request->option_c : null,
                'option_d' => $type === 'mcq' ? $request->option_d : null,

                // already text (because of your JS sync)
                'correct_option' => $correctOptionText,

            ])->save();

        });


        return redirect()
            ->route('admin.questions.index')
            ->with('success', 'Question updated successfully');
    }


    public function destroy(Question $question)
    {
        $admin = auth('admin')->user();

        abort_if($question->school_id != $admin->school_id, 403);

        $question->delete();

        return back()->with('success', 'Question deleted');
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=questions_sample.csv',
        ];

        $columns = [
            'class',
            'subject',
            'difficulty',
            'question_text',
            'marks',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_option'
        ];

        $callback = function () use ($columns) {

            $file = fopen('php://output', 'w');

            // header row
            fputcsv($file, $columns);

            // example row
            fputcsv($file, [
                '8',
                'Science',
                'medium',
                'What is photosynthesis?',
                '2',
                'Process by which plants make food',
                'Process of breathing',
                'Water cycle',
                'Soil erosion',
                'A'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
