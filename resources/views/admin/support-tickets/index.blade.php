@extends('layouts.admin')

@section('title', 'Support Ticket Management')
@section('eyebrow', 'Support & Engagement')
@section('page_heading', 'Support Tickets')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search subject..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.support-tickets.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>User</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Priority</th>
                        <th class="text-center">Status</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        @php
                            $priorityBadge = match($ticket->priority) {
                                'high'   => 'admin-badge-brick',
                                'medium' => 'admin-badge-gold',
                                'low'    => 'admin-badge-neutral',
                                default  => 'admin-badge-neutral',
                            };
                            $statusBadge = match($ticket->status) {
                                'open'     => 'admin-badge-brick',
                                'pending'  => 'admin-badge-gold',
                                'resolved' => 'admin-badge-accent',
                                'closed'   => 'admin-badge-neutral',
                                default    => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td class="max-w-[240px] truncate font-semibold" style="color:var(--a-ink)">{{ $ticket->subject }}</td>
                            <td class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">{{ $ticket->user?->name ?? '—' }}</td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral capitalize">{{ $ticket->category }}</span></td>
                            <td class="text-center"><span class="admin-badge {{ $priorityBadge }} capitalize">{{ $ticket->priority }}</span></td>
                            <td class="text-center"><span class="admin-badge {{ $statusBadge }} capitalize">{{ $ticket->status }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.support-tickets.edit', $ticket) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Open</a>
                                    <form method="POST" action="{{ route('admin.support-tickets.destroy', $ticket) }}" onsubmit="return confirm('Delete this ticket?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No support tickets found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $tickets->links() }}</div>
        @endif
    </div>

</div>
@endsection
