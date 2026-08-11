<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Device::query()->with('user');

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($type = $request->input('device_type')) {
            $query->where('device_type', $type);
        }

        $devices = $query->orderByDesc('last_active_at')->paginate(20)->withQueryString();

        return view('admin.devices.index', compact('devices'));
    }

    public function edit(Device $device): View
    {
        return view('admin.devices.edit', ['device' => $device->load('user')]);
    }

    public function update(Request $request, Device $device): RedirectResponse
    {
        $data = $request->validate([
            'is_active' => 'boolean',
        ]);

        $device->update(['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.devices.index')
            ->with('success', 'Device updated successfully!');
    }

    public function destroy(Device $device): RedirectResponse
    {
        $device->delete();

        return redirect()->route('admin.devices.index')
            ->with('success', 'Device removed successfully!');
    }
}
