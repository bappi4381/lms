@extends('layouts.admin')

@section('title', 'Edit Enrollment')
@section('eyebrow', 'Operations')
@section('page_heading', 'Edit Enrollment')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <a href="{{ route('admin.enrollments.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Enrollments
    </a>

    <!-- Enrollment Info Card -->
    <div class="admin-card !flex items-center gap-4 !p-4" style="background:var(--a-accent-soft); border-color:var(--a-accent-soft)">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[13px] font-bold uppercase" style="background:var(--a-card); color:var(--a-accent)">
            {{ strtoupper(substr($enrollment->user?->name ?? '?', 0, 1)) }}
        </div>
        <div>
            <div class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $enrollment->user?->name }}</div>
            <div class="text-[12px]" style="color:var(--a-ink-soft)">{{ $enrollment->course?->title_en }}</div>
        </div>
        <div class="ml-auto">
            <span class="admin-badge {{ $enrollment->payment_status === 'paid' ? 'admin-badge-accent' : ($enrollment->payment_status === 'pending' ? 'admin-badge-gold' : 'admin-badge-brick') }}">
                {{ ucfirst($enrollment->payment_status) }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="space-y-4">
        @csrf @method('PUT')
        @include('admin.enrollments._form', ['enrollment' => $enrollment])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.enrollments.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Update Enrollment</button>
        </div>
    </form>
</div>
@endsection
