@extends('layouts.admin')

@section('title', 'Edit Course: ' . $course->title_en)
@section('eyebrow', 'Course Management')
@section('page_heading', 'Edit Course')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <div class="mb-2 flex items-center justify-between">
        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Courses
        </a>
        <a href="{{ route('admin.courses.modules', $course) }}" class="admin-btn admin-btn-secondary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Manage Modules ({{ $modules->count() }})
        </a>
    </div>

    <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden array fields populated with existing data -->
        <input type="hidden" name="key_features_en" id="key_features_en_json"
               value="{{ old('key_features_en', json_encode($course->key_features_en ?? [])) }}">
        <input type="hidden" name="key_features_bn" id="key_features_bn_json"
               value="{{ old('key_features_bn', json_encode($course->key_features_bn ?? [])) }}">
        <input type="hidden" name="tools_en" id="tools_en_json"
               value="{{ old('tools_en', json_encode($course->tools_en ?? [])) }}">
        <input type="hidden" name="tools_bn" id="tools_bn_json"
               value="{{ old('tools_bn', json_encode($course->tools_bn ?? [])) }}">
        <input type="hidden" name="course_includes" id="course_includes_json"
               value="{{ old('course_includes', json_encode($course->course_includes ?? [])) }}">
        <input type="hidden" name="projects_en" id="projects_en_json"
               value="{{ old('projects_en', json_encode($course->projects_en ?? [])) }}">
        <input type="hidden" name="projects_bn" id="projects_bn_json"
               value="{{ old('projects_bn', json_encode($course->projects_bn ?? [])) }}">
        <input type="hidden" name="faqs_en" id="faqs_en_json"
               value="{{ old('faqs_en', json_encode($course->faqs_en ?? [])) }}">
        <input type="hidden" name="faqs_bn" id="faqs_bn_json"
               value="{{ old('faqs_bn', json_encode($course->faqs_bn ?? [])) }}">

        @include('admin.courses._form', ['course' => $course])

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Update Course</button>
        </div>
    </form>
</div>

@include('admin.courses._scripts')
@endsection
