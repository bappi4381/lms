@extends('layouts.admin')

@section('title', 'Category Management')
@section('page_heading', 'Category Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Filters -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[240px] flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search category name..." 
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="main_type" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">All Main Navbar Sections</option>
                @foreach($mainTypeOptions as $key => $label)
                    <option value="{{ $key }}" {{ request('main_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="is_active" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request('search') || request('main_type') || request('is_active') !== null)
                <a href="{{ route('admin.categories.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear Filters</a>
            @endif
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m75-7.5h-15"/></svg>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">Order</th>
                        <th class="py-3.5 px-4">Depth</th>
                        <th class="py-3.5 px-4">Icon</th>
                        <th class="py-3.5 px-4">Name (English / বাংলা)</th>
                        <th class="py-3.5 px-4">Parent Category</th>
                        <th class="py-3.5 px-4">Navbar Section</th>
                        <th class="py-3.5 px-4 text-center">Courses</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-500">{{ $category->order }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-600">L{{ $category->depth() }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-base">{{ $category->icon ?: '📁' }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <div>{{ $category->name_en }}</div>
                                <div class="text-xs font-normal text-slate-500">{{ $category->name_bn }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-500">
                                {{ $category->parent?->name_en ?: '— Top-level' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $mainType = $category->resolvedMainType();
                                    $badgeColors = [
                                        'academic' => 'bg-sky-100 text-sky-800 border-sky-200',
                                        'skills' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'test_prep' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'professional' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    ];
                                @endphp
                                @if($mainType)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border capitalize {{ $badgeColors[$mainType] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ str_replace('_', ' ', $mainType) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    {{ $category->courses_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($category->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-rose-100 text-rose-800">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 text-sm">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
