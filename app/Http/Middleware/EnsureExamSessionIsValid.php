<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamAttempt;
use Symfony\Component\HttpFoundation\Response;

class EnsureExamSessionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $examId = $request->route('id'); // Assuming route is /exams/{id}/...
        $token = $request->input('session_token') ?? $request->header('X-Session-Token');

        if (!$user || !$examId || !$token) {
             // Allow request to proceed if it's the initial load (GET live), 
             // but block actions (POST) if token is missing.
             if ($request->isMethod('post')) {
                 return response()->json(['message' => 'Session token missing'], 400);
             }
             return $next($request);
        }

        $attempt = ExamAttempt::where('user_id', $user->id)->where('exam_id', $examId)->first();

        if ($attempt && $attempt->session_token !== $token) {
            return response()->json(['message' => 'Session expired. Exam opened in another tab.'], 409);
        }

        return $next($request);
    }
}
