<?php

namespace App\Services\Sms;

class SmsManager
{
    public function driver(?string $name = null): SmsDriverInterface
    {
        $name = $name ?? config('sms.default', 'log');
        $class = config("sms.drivers.{$name}.driver");

        if (! $class || ! class_exists($class)) {
            $class = LogSmsDriver::class;
        }

        return app($class);
    }

    public function send(string $phone, string $message): bool
    {
        return $this->driver()->send($phone, $message);
    }
}
