@extends('layouts.admin')

@section('title', 'Edit Permission')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Edit Permission')

@section('content')
<div class="mx-auto max-w-xl space-y-4">
    <a href="{{ route('admin.permissions.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Permissions
    </a>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
            @csrf @method('PUT')

            <div>
                <label class="admin-label">Permission Name <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="name" required value="{{ old('name', $permission->name) }}" class="admin-input font-mono">
                @error('name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>

            <div class="mt-4">
                <label class="admin-label">Guard <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="guard_name" required value="{{ old('guard_name', $permission->guard_name) }}" class="admin-input">
                @error('guard_name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.permissions.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Update Permission</button>
            </div>
        </form>
    </div>
</div>
@endsection
