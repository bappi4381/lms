@extends('layouts.admin')

@section('title', 'Edit Device')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Edit Device')

@section('content')
<div class="mx-auto max-w-xl space-y-4">
    <a href="{{ route('admin.devices.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Devices
    </a>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.devices.update', $device) }}">
            @csrf @method('PUT')

            <div>
                <label class="admin-label">User</label>
                <input type="text" value="{{ $device->user?->name }}" disabled class="admin-input opacity-70">
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Device Type</label>
                    <input type="text" value="{{ $device->device_type }}" disabled class="admin-input capitalize opacity-70">
                </div>
                <div>
                    <label class="admin-label">IP Address</label>
                    <input type="text" value="{{ $device->ip_address }}" disabled class="admin-input opacity-70">
                </div>
            </div>

            <div class="mt-4">
                <label class="admin-label">Device Name</label>
                <input type="text" value="{{ $device->device_name }}" disabled class="admin-input opacity-70">
            </div>

            <div class="mt-4 flex items-center gap-3">
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $device->is_active) ? 'checked' : '' }} class="peer sr-only">
                    <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
                    <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Active (turn off to free up device slot)</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.devices.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
