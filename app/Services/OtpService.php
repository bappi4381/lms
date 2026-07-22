<?php

namespace App\Services;

use App\Models\PhoneOtp;
use App\Services\Sms\SmsManager;
use Illuminate\Support\Carbon;

class OtpService
{
    public function __construct(protected SmsManager $sms)
    {
    }

    /**
     * Generate a new OTP code for the phone number, persist it, and send it via SMS.
     */
    public function generateAndSend(string $phone, ?int $userId = null): PhoneOtp
    {
        $length = (int) config('sms.otp.length', 6);
        $code = (string) random_int(
            (int) str_pad('1', $length, '0'),
            (int) str_pad('', $length, '9')
        );

        $otp = PhoneOtp::create([
            'user_id' => $userId,
            'phone' => $phone,
            'code' => $code,
            'attempts' => 0,
            'expires_at' => Carbon::now()->addMinutes((int) config('sms.otp.expires_in_minutes', 5)),
        ]);

        $this->sms->send($phone, "আপনার লগইন/ভেরিফিকেশন কোড: {$code}. এই কোডটি ".config('sms.otp.expires_in_minutes', 5)." মিনিটের জন্য বৈধ থাকবে।");

        return $otp;
    }

    /**
     * Verify a submitted OTP code for the given phone number.
     */
    public function verify(string $phone, string $code): bool
    {
        $otp = PhoneOtp::where('phone', $phone)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otp) {
            return false;
        }

        $maxAttempts = (int) config('sms.otp.max_attempts', 5);

        if ($otp->attempts >= $maxAttempts || $otp->isExpired()) {
            return false;
        }

        $otp->increment('attempts');

        if ($otp->code !== $code) {
            return false;
        }

        $otp->update(['verified_at' => now()]);

        return true;
    }
}
