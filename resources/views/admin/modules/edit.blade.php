@extends('layouts.admin')

@section('title', 'Edit Module')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Edit Module')

@section('content')
<div class="mx-auto max-w-2xl space-y-5">
    <a href="{{ route('admin.modules.index') }}" class="inline-flex w-fit items-center gap-2 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Modules
    </a>

    <div class="admin-card space-y-4 p-6">
        <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="admin-label">Course <span style="color:var(--a-brick)">*</span></label>
                <x-searchable-select name="course_id"
                                     :options="$courses"
                                     :value="old('course_id', $module->course_id)"
                                     placeholder="— Select Course —"
                                     searchPlaceholder="Search course..."
                                     required="true" />
            </div>

            <div>
                <label class="admin-label">Module Title <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="title" required value="{{ old('title', $module->title) }}" class="admin-input">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Order</label>
                    <input type="number" name="order" min="0" value="{{ old('order', $module->order) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Live Provider</label>
                    @php
                        $liveProviders = ['' => '— None —', 'zoom' => 'Zoom', 'google_meet' => 'Google Meet', 'other' => 'Other'];
                    @endphp
                    <x-searchable-select name="live_class_provider"
                                         :options="$liveProviders"
                                         :value="old('live_class_provider', $module->live_class_provider)"
                                         placeholder="— None —"
                                         searchPlaceholder="Search live provider..." />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Live Link</label>
                    <input type="url" name="live_class_link" value="{{ old('live_class_link', $module->live_class_link) }}" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Live Date/Time</label>
                    <input type="datetime-local" name="live_class_at" value="{{ old('live_class_at', $module->live_class_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.modules.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Update Module</button>
            </div>
        </form>
    </div>
</div>
@endsection
