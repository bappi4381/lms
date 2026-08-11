@extends('layouts.admin')

@section('title', 'Edit Review')
@section('eyebrow', 'Support & Engagement')
@section('page_heading', 'Edit Review')

@section('content')
<div class="mx-auto max-w-xl space-y-4">
    <a href="{{ route('admin.reviews.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Reviews
    </a>

    <div class="admin-card !flex items-center gap-4 !p-4" style="background:var(--a-accent-soft); border-color:var(--a-accent-soft)">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[13px] font-bold uppercase" style="background:var(--a-card); color:var(--a-accent)">
            {{ strtoupper(substr($review->user?->name ?? '?', 0, 1)) }}
        </div>
        <div>
            <div class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $review->user?->name ?? '—' }}</div>
            <div class="text-[12px]" style="color:var(--a-ink-soft)">{{ $review->course?->title ?? '—' }}</div>
        </div>
    </div>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
            @csrf @method('PUT')

            <div>
                <label class="admin-label">Rating (1–5) <span style="color:var(--a-brick)">*</span></label>
                <input type="number" name="rating" min="1" max="5" required value="{{ old('rating', $review->rating) }}" class="admin-input">
                @error('rating')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>

            <div class="mt-4">
                <label class="admin-label">Comment</label>
                <textarea name="comment" rows="3" class="admin-textarea resize-y">{{ old('comment', $review->comment) }}</textarea>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="is_approved" value="0">
                    <input type="checkbox" name="is_approved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }} class="peer sr-only">
                    <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
                    <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Approved (visible on course page)</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.reviews.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Update Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
