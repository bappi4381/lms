<?php

namespace App\Services;

class DeviceDetector
{
    /**
     * Lightweight User-Agent sniffing — no external dependency needed.
     * Returns "mobile" or "desktop".
     */
    public static function detectType(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'desktop';
        }

        $mobilePattern = '/(android|iphone|ipod|ipad|windows phone|blackberry|opera mini|iemobile|mobile safari|mobile)/i';

        return preg_match($mobilePattern, $userAgent) ? 'mobile' : 'desktop';
    }

    public static function label(?string $userAgent): string
    {
        if (! $userAgent) {
            return 'Unknown Device';
        }

        $ua = strtolower($userAgent);

        $browser = match (true) {
            str_contains($ua, 'edg/') => 'Edge',
            str_contains($ua, 'chrome') => 'Chrome',
            str_contains($ua, 'firefox') => 'Firefox',
            str_contains($ua, 'safari') => 'Safari',
            default => 'Browser',
        };

        $os = match (true) {
            str_contains($ua, 'android') => 'Android',
            str_contains($ua, 'iphone') || str_contains($ua, 'ipad') => 'iOS',
            str_contains($ua, 'windows') => 'Windows',
            str_contains($ua, 'mac os') => 'macOS',
            str_contains($ua, 'linux') => 'Linux',
            default => 'Unknown OS',
        };

        return "{$browser} on {$os}";
    }
}
