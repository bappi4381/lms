@extends('layouts.admin')

@section('title', 'Lesson Management')
@section('page_heading', 'Lesson Management')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.lessons.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search lesson title..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="type" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                <option value="">All Types</option>
                @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="module_id" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white max-w-[250px]">
                <option value="">All Modules</option>
                @foreach($modules as $id => $title)
                    <option value="{{ $id }}" {{ request('module_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 35) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('type') || request('module_id'))
                <a href="{{ route('admin.lessons.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.lessons.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Lesson
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">Order</th>
                        <th class="py-3.5 px-4">Lesson Title</th>
                        <th class="py-3.5 px-4">Module & Course</th>
                        <th class="py-3.5 px-4 text-center">Type</th>
                        <th class="py-3.5 px-4 text-center">Preview</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($lessons as $lesson)
                        @php
                            $typeBadge = match($lesson->type) {
                                'video'      => 'bg-blue-100 text-blue-800 border-blue-200',
                                'pdf'        => 'bg-rose-100 text-rose-800 border-rose-200',
                                'quiz'       => 'bg-amber-100 text-amber-800 border-amber-200',
                                'assignment' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                default      => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-500">{{ $lesson->order }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $lesson->title }}</td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-semibold text-slate-700">{{ $lesson->module?->title ?? '—' }}</div>
                                <div class="text-slate-400">{{ $lesson->module?->course?->title_en }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border capitalize {{ $typeBadge }}">
                                    {{ $lesson->type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($lesson->is_preview)
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800">Free</span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="inline-block" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-sm">No lessons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lessons->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">{{ $lessons->links() }}</div>
        @endif
    </div>

</div>
@endsection
