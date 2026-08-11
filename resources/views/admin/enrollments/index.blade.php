@extends('layouts.admin')

@section('title', 'Enrollment Management')
@section('eyebrow', 'Operations')
@section('page_heading', 'Enrollments')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search user, course, transaction..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="payment_status" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                @foreach($paymentStatuses as $val => $label)
                    <option value="{{ $val }}" {{ request('payment_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="course_id" onchange="this.form.submit()" class="admin-select w-auto max-w-[200px]">
                <option value="">All Courses</option>
                @foreach($courses as $id => $title)
                    <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 35) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('payment_status') || request('course_id'))
                <a href="{{ route('admin.enrollments.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.enrollments.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Enrollment
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Amount Paid</th>
                        <th>Transaction ID</th>
                        <th>Enrolled At</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        @php
                            $statusBadge = match($enrollment->payment_status) {
                                'paid'     => 'admin-badge-accent',
                                'pending'  => 'admin-badge-gold',
                                'failed'   => 'admin-badge-brick',
                                'refunded' => 'admin-badge-neutral',
                                default    => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[11px] font-bold uppercase" style="background:var(--a-accent-soft); color:var(--a-accent)">
                                        {{ strtoupper(substr($enrollment->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-[12px] font-semibold leading-tight" style="color:var(--a-ink)">{{ $enrollment->user?->name ?? '—' }}</div>
                                        <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $enrollment->user?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-[180px]">
                                <div class="truncate text-[12px] font-semibold" style="color:var(--a-ink)" title="{{ $enrollment->course?->title_en }}">
                                    {{ $enrollment->course?->title_en ?? '—' }}
                                </div>
                            </td>
                            <td class="text-center"><span class="admin-badge {{ $statusBadge }} capitalize">{{ $enrollment->payment_status }}</span></td>
                            <td class="text-right font-semibold" style="color:var(--a-ink)">
                                ৳{{ number_format($enrollment->amount_paid, 0) }}
                            </td>
                            <td class="font-mono text-[11px]" style="color:var(--a-ink-faint)">
                                {{ $enrollment->transaction_id ?? '—' }}
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">
                                {{ $enrollment->enrolled_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Delete this enrollment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No enrollments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $enrollments->links() }}</div>
        @endif
    </div>

</div>
@endsection
