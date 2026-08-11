@extends('layouts.admin')

@section('title', 'Subscription Management')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Subscriptions')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by user name or email..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.subscriptions.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.subscriptions.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Subscription
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Plan</th>
                        <th class="text-center">Status</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        @php
                            $statusBadge = match($subscription->status) {
                                'active'    => 'admin-badge-accent',
                                'expired'   => 'admin-badge-neutral',
                                'cancelled' => 'admin-badge-brick',
                                default     => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $subscription->user?->name ?? '—' }}</td>
                            <td class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">{{ $subscription->plan?->name ?? '—' }}</td>
                            <td class="text-center"><span class="admin-badge {{ $statusBadge }} capitalize">{{ $subscription->status }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $subscription->starts_at?->format('d M Y') }}</td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $subscription->ends_at?->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}" onsubmit="return confirm('Delete this subscription?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">No subscriptions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscriptions->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $subscriptions->links() }}</div>
        @endif
    </div>

</div>
@endsection
