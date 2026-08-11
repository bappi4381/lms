@extends('layouts.admin')

@section('title', 'Assignment Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Assignments')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.assignments.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search assignment title..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.assignments.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.assignments.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Assignment
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Lesson</th>
                        <th>Course</th>
                        <th class="text-center">Submissions</th>
                        <th class="text-center">Max Points</th>
                        <th>Due Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $assignment->title }}</td>
                            <td class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">{{ $assignment->lesson?->title ?? '—' }}</td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $assignment->lesson?->module?->course?->title_en ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.assignments.submissions.index', $assignment) }}" class="admin-badge admin-badge-accent">
                                    {{ $assignment->submissions_count }}
                                </a>
                            </td>
                            <td class="text-center font-semibold" style="color:var(--a-ink)">{{ $assignment->max_points }}</td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $assignment->due_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.assignments.submissions.index', $assignment) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Submissions</a>
                                    <a href="{{ route('admin.assignments.edit', $assignment) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}" onsubmit="return confirm('Delete this assignment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No assignments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assignments->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $assignments->links() }}</div>
        @endif
    </div>

</div>
@endsection
