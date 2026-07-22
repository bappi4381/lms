<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioSmsDriver implements SmsDriverInterface
{
    public function send(string $phone, string $message): bool
    {
        $config = config('sms.drivers.twilio');

        try {
            $response = Http::asForm()
                ->withBasicAuth($config['sid'], $config['token'])
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$config['sid']}/Messages.json", [
                    'From' => $config['from'],
                    'To' => $phone,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Twilio send failed: '.$response->body());
        } catch (\Exception $e) {
            Log::error('Twilio Exception: '.$e->getMessage());
        }

        return false;
    }
}
