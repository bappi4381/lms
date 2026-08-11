@extends('layouts.admin')

@section('title', 'Add Assignment')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Add New Assignment')

@section('content')
<div class="mx-auto max-w-2xl space-y-4">
    <a href="{{ route('admin.assignments.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Assignments
    </a>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.assignments.store') }}">
            @csrf

            <div>
                <label class="admin-label">Lesson (type = assignment) <span style="color:var(--a-brick)">*</span></label>
                <x-searchable-select name="lesson_id"
                                     :options="$lessons"
                                     :value="old('lesson_id')"
                                     placeholder="— Select Assignment Lesson —"
                                     searchPlaceholder="Search assignment lesson..."
                                     required="true" />
                <p class="mt-1 text-[11px]" style="color:var(--a-ink-faint)">Select a lesson with type 'assignment'.</p>
            </div>

            <div class="mt-4">
                <label class="admin-label">Assignment Title <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Build a REST API Project" class="admin-input">
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Max Points <span style="color:var(--a-brick)">*</span></label>
                    <input type="number" name="max_points" min="1" value="{{ old('max_points', 100) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Due Date &amp; Time</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at') }}" class="admin-input">
                </div>
            </div>

            <div class="mt-4">
                <label class="admin-label">Instructions</label>
                <textarea name="instructions" rows="5" placeholder="Detailed assignment instructions..." class="admin-textarea resize-y">{{ old('instructions') }}</textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.assignments.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Create Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection
