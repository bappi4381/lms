@extends('layouts.admin')

@section('title', 'Course Management')
@section('page_heading', 'Course Management')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Filters -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">

        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[240px] flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search course title or slug..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="category_id" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_en }}
                    </option>
                @endforeach
            </select>

            <select name="is_published" onchange="this.form.submit()" class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
            </select>

            @if(request('search') || request('category_id') || request('is_published') !== null)
                <a href="{{ route('admin.courses.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear Filters</a>
            @endif
        </form>

        <!-- Create Button -->
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span>Add Course</span>
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Thumbnail</th>
                        <th class="py-3.5 px-4">Course Title</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Instructor</th>
                        <th class="py-3.5 px-4 text-right">Price</th>
                        <th class="py-3.5 px-4 text-center">Enrollments</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title_en }}"
                                         class="w-14 h-10 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="w-14 h-10 bg-gradient-to-br from-sky-100 to-indigo-100 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 max-w-[240px] truncate">{{ $course->title_en }}</div>
                                <div class="text-xs text-slate-500 max-w-[240px] truncate">{{ $course->title_bn }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 font-mono">/courses/{{ $course->slug }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($course->category)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $course->category->name_en }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-slate-600">
                                {{ $course->instructor?->name ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="font-bold text-slate-900">৳{{ number_format($course->price, 0) }}</div>
                                @if($course->discount_price)
                                    <div class="text-xs text-emerald-600 font-semibold">৳{{ number_format($course->discount_price, 0) }} offer</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200">
                                    {{ $course->enrollments_count }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($course->is_published)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800">Published</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800">Draft</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.courses.modules', $course) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-bold transition-all"
                                       title="Manage Modules">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline-block"
                                          onsubmit="return confirm('Delete this course? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-sm">
                                No courses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
