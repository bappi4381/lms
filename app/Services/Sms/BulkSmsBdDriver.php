<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generic driver for BulkSMSBD-style HTTP APIs (common among BD SMS
 * gateways). Adjust the request parameters to match your specific
 * provider's documentation if it differs.
 */
class BulkSmsBdDriver implements SmsDriverInterface
{
    public function send(string $phone, string $message): bool
    {
        $config = config('sms.drivers.bulksmsbd');

        try {
            $response = Http::get($config['base_url'], [
                'api_key' => $config['api_key'],
                'senderid' => $config['sender_id'],
                'number' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('BulkSmsBd send failed: '.$response->body());
        } catch (\Exception $e) {
            Log::error('BulkSmsBd Exception: '.$e->getMessage());
        }

        return false;
    }
}
