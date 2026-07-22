<?php

namespace App\Services\Sms;

interface SmsDriverInterface
{
    /**
     * Send a plain-text SMS message to the given phone number.
     * Returns true on success (or accepted-for-delivery), false otherwise.
     */
    public function send(string $phone, string $message): bool;
}
