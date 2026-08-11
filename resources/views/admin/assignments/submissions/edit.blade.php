@extends('layouts.admin')

@section('title', 'Grade Submission')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Grade Submission')

@section('content')
<div class="mx-auto max-w-2xl space-y-4">
    <a href="{{ route('admin.assignments.submissions.index', $assignment) }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Submissions
    </a>

    <div class="admin-card space-y-4">
        <div class="flex items-center justify-between border-b pb-4" style="border-color:var(--a-line-soft)">
            <div>
                <h2 class="font-ledger text-[16px] font-semibold" style="color:var(--a-ink)">{{ $submission->user?->name ?? '—' }}</h2>
                <p class="text-[12px]" style="color:var(--a-ink-faint)">{{ $submission->user?->email }} &middot; {{ $assignment->title }}</p>
            </div>
            <span class="text-[12px]" style="color:var(--a-ink-faint)">Submitted {{ $submission->submitted_at?->format('d M Y, h:i A') ?? '—' }}</span>
        </div>

        <div>
            <label class="admin-label">Submission Text</label>
            <textarea rows="4" disabled class="admin-textarea resize-y opacity-70">{{ $submission->submission_text }}</textarea>
        </div>

        <div>
            <label class="admin-label">Uploaded File</label>
            @if($submission->file_path)
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank"
                   class="admin-input flex items-center gap-2 !py-2 no-underline" style="color:var(--a-accent)">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 4h16v16H4V4z"/></svg>
                    <span class="truncate">{{ basename($submission->file_path) }}</span>
                </a>
            @else
                <div class="admin-input flex items-center opacity-60">—</div>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.assignments.submissions.update', [$assignment, $submission]) }}" class="space-y-4 border-t pt-4" style="border-color:var(--a-line-soft)">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Grade <span style="color:var(--a-ink-faint)">(out of {{ $assignment->max_points }})</span></label>
                    <input type="number" name="grade" step="0.01" min="0" max="{{ $assignment->max_points }}" value="{{ old('grade', $submission->grade) }}" class="admin-input">
                </div>
                <div class="flex flex-col justify-end pb-2.5">
                    <span class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">Current status:
                        <span class="admin-badge {{ match($submission->status) { 'submitted' => 'admin-badge-gold', 'graded' => 'admin-badge-accent', 'resubmit' => 'admin-badge-brick', default => 'admin-badge-neutral' } }} capitalize">{{ $submission->status }}</span>
                    </span>
                </div>
            </div>

            <div>
                <label class="admin-label">Feedback</label>
                <textarea name="feedback" rows="4" class="admin-textarea resize-y">{{ old('feedback', $submission->feedback) }}</textarea>
            </div>

            @if($submission->graded_at)
                <p class="text-[11px]" style="color:var(--a-ink-faint)">Last graded {{ $submission->graded_at->format('d M Y, h:i A') }} by {{ $submission->grader?->name ?? '—' }}</p>
            @endif

            <div class="flex justify-end gap-3 border-t pt-4" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.assignments.submissions.index', $assignment) }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Save Grade</button>
            </div>
        </form>
    </div>
</div>
@endsection
