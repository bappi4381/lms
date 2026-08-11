@extends('layouts.admin')

@section('title', 'Assignment Submissions')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Submissions')

@section('content')
<div class="space-y-5">

    <a href="{{ route('admin.assignments.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Assignments
    </a>

    <div class="admin-card !p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-ledger text-[17px] font-semibold" style="color:var(--a-ink)">{{ $assignment->title }}</h2>
                <p class="mt-0.5 text-[12px]" style="color:var(--a-ink-faint)">
                    {{ $assignment->lesson?->module?->course?->title_en ?? '—' }} &middot; Max points: {{ $assignment->max_points }}
                    @if($assignment->due_at) &middot; Due {{ $assignment->due_at->format('d M Y, h:i A') }} @endif
                </p>
            </div>
            <a href="{{ route('admin.assignments.edit', $assignment) }}" class="admin-btn admin-btn-secondary">Edit Assignment</a>
        </div>
    </div>

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.assignments.submissions.index', $assignment) }}" class="flex flex-1 flex-wrap items-center gap-3">
            <select name="status" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="graded" {{ request('status') === 'graded' ? 'selected' : '' }}>Graded</option>
                <option value="resubmit" {{ request('status') === 'resubmit' ? 'selected' : '' }}>Resubmit</option>
            </select>
            @if(request('status'))
                <a href="{{ route('admin.assignments.submissions.index', $assignment) }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Grade</th>
                        <th>Submitted At</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        @php
                            $statusBadge = match($submission->status) {
                                'submitted' => 'admin-badge-gold',
                                'graded'    => 'admin-badge-accent',
                                'resubmit'  => 'admin-badge-brick',
                                default     => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="font-semibold" style="color:var(--a-ink)">{{ $submission->user?->name ?? '—' }}</div>
                                <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $submission->user?->email }}</div>
                            </td>
                            <td class="text-center"><span class="admin-badge {{ $statusBadge }} capitalize">{{ $submission->status }}</span></td>
                            <td class="text-center font-semibold" style="color:var(--a-ink)">
                                {{ $submission->grade !== null ? rtrim(rtrim($submission->grade, '0'), '.') . ' / ' . $assignment->max_points : '—' }}
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $submission->submitted_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.assignments.submissions.edit', [$assignment, $submission]) }}" class="admin-btn admin-btn-primary !min-h-[30px] !px-3 text-[12px]">Grade</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="admin-empty">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $submissions->links() }}</div>
        @endif
    </div>

</div>
@endsection
