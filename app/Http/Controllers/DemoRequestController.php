<?php

namespace App\Http\Controllers;

use App\Mail\DemoRequestReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'institution' => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'role'        => 'nullable|string|max:100',
            'message'     => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $details = $validator->validated();

        try {
            // Ensure you have a default mailer and from address configured in .env
            Mail::to(config('mail.from.address'))->send(new DemoRequestReceived($details));
        } catch (\Exception $e) {
            Log::error('Demo request email failed to send: ' . $e->getMessage());
            return response()->json(['message' => 'Could not process your request at this time. Please try again later.'], 500);
        }

        return response()->json(['message' => 'Thank you! Your request has been submitted. We will get back to you shortly.']);
    }
}
