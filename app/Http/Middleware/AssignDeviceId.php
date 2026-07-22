<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures every visitor (guest or authenticated) carries a stable,
 * long-lived first-party `device_id` cookie. This is the foundation of
 * device-limited access: it works on any request (no JS required) and
 * survives normal browsing/navigation.
 */
class AssignDeviceId
{
    public const COOKIE_NAME = 'device_id';

    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->cookie(self::COOKIE_NAME);

        if (! $deviceId) {
            $deviceId = (string) Str::uuid();
            Cookie::queue(self::COOKIE_NAME, $deviceId, 60 * 24 * 365 * 2); // 2 years
        }

        // Make it available within the current request even though the
        // cookie itself will only be sent back by the browser on the *next* request.
        $request->attributes->set(self::COOKIE_NAME, $deviceId);

        return $next($request);
    }
}
