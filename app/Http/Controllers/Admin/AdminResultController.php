<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
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

    public function list()
{
    $attempts = ExamAttempt::with(['user', 'exam'])
        ->whereIn('approval_status', ['approved', 'rejected'])
        ->latest()
        ->get();

    return view('admin.results.list', compact('attempts'));
}
}
