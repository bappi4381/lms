<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AssignDeviceId;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeviceController extends Controller
{
    /**
     * List the authenticated user's registered devices (profile page).
     */
    public function index(): View
    {
        $devices = auth()->user()->devices()->orderByDesc('last_active_at')->get();
        $currentDeviceUuid = request()->cookie(AssignDeviceId::COOKIE_NAME);

        return view('profile.devices', compact('devices', 'currentDeviceUuid'));
    }

    /**
     * Remove/deactivate a device to free up its slot (mobile or desktop).
     */
    public function destroy(Request $request, int $device): RedirectResponse
    {
        $deviceModel = $request->user()->devices()->findOrFail($device);
        $deviceModel->delete();

        return back()->with('status', 'ডিভাইসটি সফলভাবে রিমুভ করা হয়েছে। এখন নতুন ডিভাইস থেকে লগইন করতে পারবেন।');
    }

    /**
     * Shown when a user tries to access from a 3rd device (already has
     * 1 mobile + 1 desktop registered).
     */
    public function limitReached(): View
    {
        $devices = auth()->user()->devices()->where('is_active', true)->orderByDesc('last_active_at')->get();

        return view('devices.limit-reached', compact('devices'));
    }
}
