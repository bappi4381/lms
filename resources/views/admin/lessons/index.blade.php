@extends('layouts.admin')

@section('title', 'Lesson Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Lessons')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.lessons.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search lesson title..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="type" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Types</option>
                @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="module_id" onchange="this.form.submit()" class="admin-select w-auto max-w-[250px]">
                <option value="">All Modules</option>
                @foreach($modules as $id => $title)
                    <option value="{{ $id }}" {{ request('module_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 35) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('type') || request('module_id'))
                <a href="{{ route('admin.lessons.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.lessons.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Lesson
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">Order</th>
                        <th>Lesson Title</th>
                        <th>Module &amp; Course</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Preview</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                        @php
                            $typeBadge = match($lesson->type) {
                                'video'      => 'admin-badge-accent',
                                'pdf'        => 'admin-badge-brick',
                                'quiz'       => 'admin-badge-gold',
                                'assignment' => 'admin-badge-accent',
                                default      => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td class="text-center font-semibold" style="color:var(--a-ink-soft)">{{ $lesson->order }}</td>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $lesson->title }}</td>
                            <td class="text-[12px]">
                                <div class="font-semibold" style="color:var(--a-ink)">{{ $lesson->module?->title ?? '—' }}</div>
                                <div style="color:var(--a-ink-faint)">{{ $lesson->module?->course?->title_en }}</div>
                            </td>
                            <td class="text-center"><span class="admin-badge {{ $typeBadge }} capitalize">{{ $lesson->type }}</span></td>
                            <td class="text-center font-mono text-[12px]" style="color:var(--a-ink-soft)">
                                @if($lesson->duration_seconds)
                                    {{ sprintf('%02d:%02d', intdiv($lesson->duration_seconds, 60), $lesson->duration_seconds % 60) }}
                                @else
                                    <span style="color:var(--a-ink-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($lesson->is_preview)
                                    <span class="admin-badge admin-badge-accent">Free</span>
                                @else
                                    <span class="text-[12px]" style="color:var(--a-ink-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('Delete this lesson?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No lessons found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lessons->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $lessons->links() }}</div>
        @endif
    </div>

</div>
@endsection
