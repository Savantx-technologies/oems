<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamStream;

class WebRTCController extends Controller
{
    public function storeOffer(Request $request)
    {
        $stream = ExamStream::updateOrCreate(
            ['attempt_id' => $request->attempt_id],
            ['offer' => $request->offer]
        );

        return response()->json(['status' => 'offer_saved']);
    }

    public function getOffer($attemptId)
    {
        $stream = ExamStream::where('attempt_id', $attemptId)->first();
        return response()->json($stream);
    }

    public function storeAnswer(Request $request)
    {
        $stream = ExamStream::where('attempt_id', $request->attempt_id)->first();
        $stream->update(['answer' => $request->answer]);

        return response()->json(['status' => 'answer_saved']);
    }

    public function getAnswer($attemptId)
    {
        $stream = ExamStream::where('attempt_id', $attemptId)->first();
        return response()->json($stream);
    }
}