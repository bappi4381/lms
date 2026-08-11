@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)
@section('eyebrow', 'Access Control')
@section('page_heading', 'Edit User')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <a href="{{ route('admin.users.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>

    <!-- User summary card -->
    <div class="admin-card !flex items-center gap-4 !p-5" style="background:var(--a-accent-soft); border-color:var(--a-accent-soft)">
        @php
            [$bg, $fg] = $user->hasRole('admin') ? ['var(--a-brick-soft)', 'var(--a-brick)'] : ($user->hasRole('instructor') ? ['var(--a-gold-soft)', 'var(--a-gold)'] : ['var(--a-card)', 'var(--a-accent)']);
        @endphp
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-[17px] font-bold uppercase" style="background:{{ $bg }}; color:{{ $fg }}">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
            <div class="font-ledger text-[16px] font-semibold" style="color:var(--a-ink)">{{ $user->name }}</div>
            <div class="text-[13px]" style="color:var(--a-ink-soft)">{{ $user->email }}</div>
            <div class="mt-1.5 flex gap-1">
                @foreach($user->roles as $role)
                    <span class="admin-badge {{ match($role->name) { 'admin' => 'admin-badge-brick', 'instructor' => 'admin-badge-gold', 'student' => 'admin-badge-accent', default => 'admin-badge-neutral' } }} capitalize">
                        {{ $role->name }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="shrink-0 text-right">
            <div class="text-[11px]" style="color:var(--a-ink-faint)">Joined</div>
            <div class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $user->created_at->format('d M Y') }}</div>
            <div class="mt-1 text-[11px]" style="color:var(--a-ink-faint)">{{ $enrollments->count() }} enrollment(s)</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf @method('PUT')
        @include('admin.users._form', ['user' => $user, 'userRoles' => $userRoles])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Update User</button>
        </div>
    </form>

    <!-- Recent Enrollments mini table -->
    @if($enrollments->count() > 0)
        <div class="admin-card !p-5">
            <h3 class="font-ledger text-[14px] font-semibold" style="color:var(--a-ink)">Recent Enrollments</h3>
            <div class="mt-3 space-y-2">
                @foreach($enrollments as $enrollment)
                    <div class="flex items-center justify-between rounded-ledger border p-3" style="border-color:var(--a-line-soft); background:var(--a-page)">
                        <div class="max-w-xs truncate text-[13px] font-semibold" style="color:var(--a-ink)">
                            {{ $enrollment->course?->title_en ?? '—' }}
                        </div>
                        <div class="flex shrink-0 items-center gap-3">
                            <span class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">৳{{ number_format($enrollment->amount_paid, 0) }}</span>
                            <span class="admin-badge {{ $enrollment->payment_status === 'paid' ? 'admin-badge-accent' : ($enrollment->payment_status === 'pending' ? 'admin-badge-gold' : 'admin-badge-neutral') }} capitalize">
                                {{ $enrollment->payment_status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
