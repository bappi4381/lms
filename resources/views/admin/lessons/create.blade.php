@extends('layouts.admin')

@section('title', 'Add Lesson')
@section('page_heading', 'Add New Lesson')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <a href="{{ route('admin.lessons.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Lessons
    </a>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
        <form method="POST" action="{{ route('admin.lessons.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Module <span class="text-rose-500">*</span></label>
                <x-searchable-select name="module_id"
                                     :options="$modules"
                                     :value="old('module_id', $selectedModuleId)"
                                     placeholder="— Select Module —"
                                     searchPlaceholder="Search module..."
                                     required="true" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lesson Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Lesson 1: Getting Started" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Type <span class="text-rose-500">*</span></label>
                    <x-searchable-select name="type"
                                         :options="$types"
                                         :value="old('type', 'video')"
                                         placeholder="Select Type"
                                         searchPlaceholder="Search type..."
                                         required="true" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Order</label>
                    <input type="number" name="order" min="0" value="{{ old('order', 0) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Video ID / Embedded URL (Video type)</label>
                <input type="text" name="video_id" value="{{ old('video_id') }}" placeholder="YouTube / Vimeo video ID or URL" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">PDF File URL (PDF type)</label>
                <input type="url" name="pdf_url" value="{{ old('pdf_url') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lesson Notes / Content</label>
                <textarea name="content" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-y">{{ old('content') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_preview" value="0">
                    <input type="checkbox" name="is_preview" value="1" {{ old('is_preview') ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-sky-300 rounded-full peer peer-checked:bg-sky-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </label>
                <span class="text-sm font-bold text-slate-700">Free Preview Lesson</span>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.lessons.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Cancel</a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md">Create Lesson</button>
            </div>
        </form>
    </div>
</div>
@endsection
