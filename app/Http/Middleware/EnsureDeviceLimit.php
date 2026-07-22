<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Services\DeviceDetector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforces "1 mobile + 1 desktop" access per account. Paired with
 * AssignDeviceId (which guarantees a stable device_id cookie exists).
 *
 * If the current device is new AND the user already has an active
 * device of the same type, we block access and send them to the
 * "device limit reached" page where they can free up a slot.
 */
class EnsureDeviceLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $deviceUuid = $request->cookie(AssignDeviceId::COOKIE_NAME)
            ?? $request->attributes->get(AssignDeviceId::COOKIE_NAME);

        if (! $deviceUuid) {
            return $next($request);
        }

        $deviceType = DeviceDetector::detectType($request->userAgent());

        $device = Device::where('user_id', $user->id)
            ->where('device_uuid', $deviceUuid)
            ->first();

        if ($device) {
            $device->update([
                'last_active_at' => now(),
                'ip_address' => $request->ip(),
                'is_active' => true,
            ]);

            return $next($request);
        }

        $activeOfSameType = Device::where('user_id', $user->id)
            ->where('device_type', $deviceType)
            ->where('is_active', true)
            ->count();

        if ($activeOfSameType >= 1) {
            return redirect()->route('devices.limit-reached');
        }

        Device::create([
            'user_id' => $user->id,
            'device_uuid' => $deviceUuid,
            'device_type' => $deviceType,
            'device_name' => DeviceDetector::label($request->userAgent()),
            'ip_address' => $request->ip(),
            'is_active' => true,
            'last_active_at' => now(),
        ]);

        return $next($request);
    }
}
