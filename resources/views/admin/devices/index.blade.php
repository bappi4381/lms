@extends('layouts.admin')

@section('title', 'Device Management')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Devices')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.devices.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by user name or email..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="device_type" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Types</option>
                <option value="mobile" {{ request('device_type') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="desktop" {{ request('device_type') === 'desktop' ? 'selected' : '' }}>Desktop</option>
            </select>

            @if(request('search') || request('device_type'))
                <a href="{{ route('admin.devices.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th class="text-center">Type</th>
                        <th>Device</th>
                        <th>IP</th>
                        <th class="text-center">Active</th>
                        <th>Last Active</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $device->user?->name ?? '—' }}</td>
                            <td class="text-center"><span class="admin-badge {{ $device->device_type === 'mobile' ? 'admin-badge-accent' : 'admin-badge-neutral' }} capitalize">{{ $device->device_type }}</span></td>
                            <td class="max-w-[220px] truncate text-[12px]" style="color:var(--a-ink-soft)">{{ $device->device_name }}</td>
                            <td class="font-mono text-[11px]" style="color:var(--a-ink-faint)">{{ $device->ip_address }}</td>
                            <td class="text-center">
                                @if($device->is_active)
                                    <svg class="mx-auto h-5 w-5" style="color:var(--a-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="mx-auto h-5 w-5" style="color:var(--a-line)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $device->last_active_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.devices.edit', $device) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.devices.destroy', $device) }}" onsubmit="return confirm('Remove this device?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Remove</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No devices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($devices->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $devices->links() }}</div>
        @endif
    </div>

</div>
@endsection
