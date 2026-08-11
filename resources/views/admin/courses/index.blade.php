@extends('layouts.admin')

@section('title', 'Course Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Courses')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search course title or slug..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="category_id" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_en }}
                    </option>
                @endforeach
            </select>

            <select name="is_published" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
            </select>

            @if(request('search') || request('category_id') || request('is_published') !== null)
                <a href="{{ route('admin.courses.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.courses.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span>Add Course</span>
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Course Title</th>
                        <th>Category</th>
                        <th>Instructor</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Enrollments</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title_en }}"
                                         class="h-10 w-14 rounded-ledger border object-cover" style="border-color:var(--a-line)">
                                @else
                                    <div class="flex h-10 w-14 items-center justify-center rounded-ledger border" style="background:var(--a-page); border-color:var(--a-line); color:var(--a-ink-faint)">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="max-w-[240px] truncate font-semibold" style="color:var(--a-ink)">{{ $course->title_en }}</div>
                                <div class="max-w-[240px] truncate text-[12px]" style="color:var(--a-ink-soft)">{{ $course->title_bn }}</div>
                                <div class="mt-0.5 font-mono text-[11px]" style="color:var(--a-ink-faint)">/courses/{{ $course->slug }}</div>
                            </td>
                            <td>
                                @if($course->category)
                                    <span class="admin-badge admin-badge-accent">{{ $course->category->name_en }}</span>
                                @else
                                    <span class="text-[12px]" style="color:var(--a-ink-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-[12.5px]" style="color:var(--a-ink-soft)">
                                {{ $course->instructor?->name ?? '—' }}
                            </td>
                            <td class="text-right">
                                <div class="font-semibold" style="color:var(--a-ink)">৳{{ number_format($course->price, 0) }}</div>
                                @if($course->discount_price)
                                    <div class="text-[11px] font-semibold" style="color:var(--a-accent)">৳{{ number_format($course->discount_price, 0) }} offer</div>
                                @endif
                            </td>
                            <td class="text-center"><span class="admin-badge admin-badge-gold">{{ $course->enrollments_count }}</span></td>
                            <td class="text-center">
                                @if($course->is_published)
                                    <span class="admin-badge admin-badge-accent">Published</span>
                                @else
                                    <span class="admin-badge admin-badge-brick">Draft</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.courses.modules', $course) }}"
                                       class="admin-btn admin-btn-secondary !min-h-[30px] !px-2.5"
                                       title="Manage Modules">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                          onsubmit="return confirm('Delete this course? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="admin-empty">No courses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
