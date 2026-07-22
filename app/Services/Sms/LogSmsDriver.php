<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

/**
 * Development-friendly driver that just logs the SMS instead of sending it.
 * Useful before real SMS gateway credentials are available.
 */
class LogSmsDriver implements SmsDriverInterface
{
    public function send(string $phone, string $message): bool
    {
        Log::info("[SMS:log-driver] To: {$phone} | Message: {$message}");

        return true;
    }
}
