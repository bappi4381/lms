@extends('layouts.admin')

@section('title', 'Support Ticket: ' . $ticket->subject)
@section('eyebrow', 'Support & Engagement')
@section('page_heading', 'Support Ticket')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <a href="{{ route('admin.support-tickets.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Support Tickets
    </a>

    <!-- Ticket summary -->
    <div class="admin-card">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="font-ledger text-[17px] font-semibold" style="color:var(--a-ink)">{{ $ticket->subject }}</h2>
                <p class="mt-1 text-[12px]" style="color:var(--a-ink-faint)">
                    {{ $ticket->user?->name ?? '—' }} &middot; {{ $ticket->user?->email }} &middot; {{ $ticket->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <span class="admin-badge admin-badge-neutral capitalize">{{ $ticket->category }}</span>
        </div>

        <form method="POST" action="{{ route('admin.support-tickets.update', $ticket) }}" class="mt-5 grid grid-cols-1 gap-4 border-t pt-5 sm:grid-cols-2" style="border-color:var(--a-line-soft)">
            @csrf @method('PUT')
            <div>
                <label class="admin-label">Status <span style="color:var(--a-brick)">*</span></label>
                <x-searchable-select name="status"
                                     :options="$statuses"
                                     :value="old('status', $ticket->status)"
                                     placeholder="Select Status"
                                     searchPlaceholder="Search..."
                                     required="true" />
            </div>
            <div>
                <label class="admin-label">Priority <span style="color:var(--a-brick)">*</span></label>
                <x-searchable-select name="priority"
                                     :options="$priorities"
                                     :value="old('priority', $ticket->priority)"
                                     placeholder="Select Priority"
                                     searchPlaceholder="Search..."
                                     required="true" />
            </div>
            <div class="sm:col-span-2 flex justify-end">
                <button type="submit" class="admin-btn admin-btn-primary">Save Status</button>
            </div>
        </form>
    </div>

    <!-- Replies thread -->
    <div class="admin-card !p-0">
        <div class="admin-card-head">
            <h3 class="admin-card-title">Replies</h3>
            <span class="text-[12px]" style="color:var(--a-ink-faint)">{{ $ticket->replies->count() }} reply(ies)</span>
        </div>
        <div class="divide-y" style="border-color:var(--a-line-soft)">
            @forelse($ticket->replies as $reply)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $reply->user?->name ?? '—' }}</span>
                            @if($reply->is_staff_reply)
                                <span class="admin-badge admin-badge-accent">Staff</span>
                            @endif
                        </div>
                        <span class="text-[11px]" style="color:var(--a-ink-faint)">{{ $reply->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <p class="mt-2 whitespace-pre-line text-[13px]" style="color:var(--a-ink-soft)">{{ $reply->message }}</p>
                </div>
            @empty
                <div class="admin-empty">No replies yet.</div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.support-tickets.reply', $ticket) }}" class="border-t p-5" style="border-color:var(--a-line-soft)">
            @csrf
            <label class="admin-label">Reply as Staff</label>
            <textarea name="message" rows="4" required placeholder="Type your reply..." class="admin-textarea resize-y">{{ old('message') }}</textarea>
            @error('message')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            <div class="mt-3 flex justify-end">
                <button type="submit" class="admin-btn admin-btn-primary">Send Reply</button>
            </div>
        </form>
    </div>
</div>
@endsection
