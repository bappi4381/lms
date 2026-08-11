@extends('layouts.admin')

@section('title', 'Module Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Modules')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.modules.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search module or course..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="course_id" onchange="this.form.submit()" class="admin-select w-auto max-w-[250px]">
                <option value="">All Courses</option>
                @foreach($courses as $id => $title)
                    <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 35) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('course_id'))
                <a href="{{ route('admin.modules.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.modules.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Module
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">Order</th>
                        <th>Module Title</th>
                        <th>Course</th>
                        <th class="text-center">Lessons</th>
                        <th>Live Class</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $module)
                        <tr>
                            <td class="text-center font-semibold" style="color:var(--a-ink-soft)">{{ $module->order }}</td>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $module->title }}</td>
                            <td><span class="admin-badge admin-badge-accent">{{ $module->course?->title_en ?? '—' }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-gold">{{ $module->lessons->count() }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-soft)">
                                @if($module->live_class_provider)
                                    <span class="font-semibold capitalize" style="color:var(--a-ink)">{{ $module->live_class_provider }}</span>
                                    @if($module->live_class_at)
                                        <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $module->live_class_at->format('d M, h:i A') }}</div>
                                    @endif
                                @else
                                    <span style="color:var(--a-ink-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.modules.edit', $module) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" onsubmit="return confirm('Delete this module?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">No modules found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($modules->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $modules->links() }}</div>
        @endif
    </div>

</div>
@endsection
