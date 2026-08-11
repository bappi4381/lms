@extends('layouts.admin')

@section('title', 'Review Management')
@section('eyebrow', 'Support & Engagement')
@section('page_heading', 'Reviews')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by user or course..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="is_approved" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Approved</option>
                <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>Pending</option>
            </select>

            @if(request('search') || request('is_approved') !== null)
                <a href="{{ route('admin.reviews.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
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
                        <th>Course</th>
                        <th class="text-center">Rating</th>
                        <th>Comment</th>
                        <th class="text-center">Approved</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $review->user?->name ?? '—' }}</td>
                            <td class="max-w-[180px] truncate text-[12px]" style="color:var(--a-ink-soft)">{{ $review->course?->title ?? '—' }}</td>
                            <td class="text-center font-mono text-[13px]" style="color:var(--a-gold)">{{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}</td>
                            <td class="max-w-[260px] text-[12px]" style="color:var(--a-ink-soft)">{{ Str::limit($review->comment, 60) }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="rating" value="{{ $review->rating }}">
                                    <input type="hidden" name="comment" value="{{ $review->comment }}">
                                    <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                    <button type="submit" class="admin-badge {{ $review->is_approved ? 'admin-badge-accent' : 'admin-badge-gold' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $review->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.reviews.edit', $review) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $reviews->links() }}</div>
        @endif
    </div>

</div>
@endsection
