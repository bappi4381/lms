@extends('layouts.admin')

@section('title', 'Category Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Categories')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search category name…"
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="main_type" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Navbar Sections</option>
                @foreach($mainTypeOptions as $key => $label)
                    <option value="{{ $key }}" {{ request('main_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="is_active" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request('search') || request('main_type') || request('is_active') !== null)
                <a href="{{ route('admin.categories.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">Order</th>
                        <th>Depth</th>
                        <th>Name (English / বাংলা)</th>
                        <th>Parent</th>
                        <th>Navbar Section</th>
                        <th class="text-center">Courses</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="text-center font-semibold" style="color:var(--a-ink-soft)">{{ $category->order }}</td>
                            <td><span class="admin-badge admin-badge-neutral">L{{ $category->depth() }}</span></td>
                            <td>
                                <div class="font-semibold" style="color:var(--a-ink)">{{ $category->name_en }}</div>
                                <div class="text-[12px]" style="color:var(--a-ink-soft)">{{ $category->name_bn }}</div>
                            </td>
                            <td class="text-[12.5px] font-medium" style="color:var(--a-ink-soft)">
                                {{ $category->parent?->name_en ?: '— Top-level' }}
                            </td>
                            <td>
                                @php
                                    $mainType = $category->resolvedMainType();
                                    $badgeTones = [
                                        'academic' => 'admin-badge-accent',
                                        'skills' => 'admin-badge-gold',
                                        'test_prep' => 'admin-badge-brick',
                                        'professional' => 'admin-badge-neutral',
                                    ];
                                @endphp
                                @if($mainType)
                                    <span class="admin-badge {{ $badgeTones[$mainType] ?? 'admin-badge-neutral' }} capitalize">
                                        {{ str_replace('_', ' ', $mainType) }}
                                    </span>
                                @else
                                    <span class="text-[12px]" style="color:var(--a-ink-faint)">—</span>
                                @endif
                            </td>
                            <td class="text-center"><span class="admin-badge admin-badge-accent">{{ $category->courses_count }}</span></td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="admin-badge admin-badge-accent">Active</span>
                                @else
                                    <span class="admin-badge admin-badge-brick">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="admin-empty">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
