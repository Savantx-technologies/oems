<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamScheduleController extends Controller
{
    public function create($id)
    {
        $admin = auth('admin')->user();

        $exam = Exam::with('schedule')
            ->where('school_id', $admin->school_id)
            ->findOrFail($id);

        return view('admin.exams.schedule', compact('exam'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'start_at' => 'required',
            'end_at' => 'required',
        ]);

        $startAt = Carbon::parse($request->start_at);
        $endAt = Carbon::parse($request->end_at);

        if ($endAt->lte($startAt)) {
            return back()
                ->withErrors(['end_at' => 'End time must be after start time'])
                ->withInput();
        }

        $admin = auth('admin')->user();

        $exam = Exam::where('school_id', $admin->school_id)
            ->findOrFail($id);

        $data = [
            'start_at' => $startAt,
            'end_at' => $endAt,
            'late_entry_allowed' => $request->boolean('late_entry_allowed'),
            'late_entry_minutes' => $request->input('late_entry_minutes', 0),
            'max_attempts' => $request->input('max_attempts', 1),
        ];

        if ($exam->schedule) {
            $exam->schedule->update($data);
        } else {
            $exam->schedule()->create($data);
        }

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Exam schedule saved successfully.');
    }


}



