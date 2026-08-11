@extends('layouts.admin')

@section('title', 'Edit Role')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Edit Role')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <a href="{{ route('admin.roles.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Roles
    </a>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf @method('PUT')
        @include('admin.roles._form', ['role' => $role])
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Update Role</button>
        </div>
    </form>
</div>
@endsection
