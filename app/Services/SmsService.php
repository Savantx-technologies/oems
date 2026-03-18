<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public static function sendOtp($mobile, $otp)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authkey' => env('SMS_API_KEY'),
            ])->post('http://103.153.58.130/api/v2/SendSMS', [
                'sender' => env('SMS_SENDER_ID'),
                'route' => '4',
                'country' => '91',
                'sms' => [
                    [
                        //  TEMPLATE FORMAT REQUIRED
                        'message' => "Your OTP for login is (#var). Do not share it.",
                        'to' => [$mobile],

                        //  REQUIRED FIELD
                        'variables_values' => $otp
                    ]
                ]
            ]);

            $result = $response->json();

            \Log::info('SMS FULL RESPONSE: ', $result);

            //  CORRECT SUCCESS CHECK
            if (isset($result['ErrorCode']) && $result['ErrorCode'] == 0) {
                return true;
            }

            return false;

        } catch (\Exception $e) {
            \Log::error('SMS Failed: ' . $e->getMessage());
            return false;
        }
    }
}