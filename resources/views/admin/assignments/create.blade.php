@extends('layouts.admin')

@section('title', 'Add Assignment')
@section('page_heading', 'Add New Assignment')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">
    <a href="{{ route('admin.assignments.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Assignments
    </a>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
        <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lesson (type = assignment) <span class="text-rose-500">*</span></label>
                <x-searchable-select name="lesson_id"
                                     :options="$lessons"
                                     :value="old('lesson_id')"
                                     placeholder="— Select Assignment Lesson —"
                                     searchPlaceholder="Search assignment lesson..."
                                     required="true" />
                <p class="text-xs text-slate-400 mt-1">Select a lesson with type 'assignment'.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Assignment Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Build a REST API Project" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max Points <span class="text-rose-500">*</span></label>
                    <input type="number" name="max_points" min="1" value="{{ old('max_points', 100) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Due Date & Time</label>
                    <input type="datetime-local" name="due_at" value="{{ old('due_at') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instructions</label>
                <textarea name="instructions" rows="5" placeholder="Detailed assignment instructions..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-y">{{ old('instructions') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.assignments.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Cancel</a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md">Create Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection
