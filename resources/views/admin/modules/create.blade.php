@extends('layouts.admin')

@section('title', 'Add Module')
@section('page_heading', 'Add New Module')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <a href="{{ route('admin.modules.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Modules
    </a>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
        <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course <span class="text-rose-500">*</span></label>
                <select name="course_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">— Select Course —</option>
                    @foreach($courses as $id => $title)
                        <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Module Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Module 1: Introduction" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Order</label>
                    <input type="number" name="order" min="0" value="{{ old('order', 0) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Provider</label>
                    <select name="live_class_provider" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                        <option value="">— None —</option>
                        <option value="zoom" {{ old('live_class_provider') === 'zoom' ? 'selected' : '' }}>Zoom</option>
                        <option value="google_meet" {{ old('live_class_provider') === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                        <option value="other" {{ old('live_class_provider') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Link</label>
                    <input type="url" name="live_class_link" value="{{ old('live_class_link') }}" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Date/Time</label>
                    <input type="datetime-local" name="live_class_at" value="{{ old('live_class_at') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.modules.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Cancel</a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md">Create Module</button>
            </div>
        </form>
    </div>
</div>
@endsection
