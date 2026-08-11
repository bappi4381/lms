@extends('layouts.admin')

@section('title', 'Create Course')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Create New Course')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Courses
    </a>

    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Hidden array fields (managed via JS) -->
        <input type="hidden" name="key_features_en" id="key_features_en_json" value="{{ old('key_features_en', '[]') }}">
        <input type="hidden" name="key_features_bn" id="key_features_bn_json" value="{{ old('key_features_bn', '[]') }}">
        <input type="hidden" name="tools_en" id="tools_en_json" value="{{ old('tools_en', '[]') }}">
        <input type="hidden" name="tools_bn" id="tools_bn_json" value="{{ old('tools_bn', '[]') }}">
        <input type="hidden" name="course_includes" id="course_includes_json" value="{{ old('course_includes', '[]') }}">
        <input type="hidden" name="projects_en" id="projects_en_json" value="{{ old('projects_en', '[]') }}">
        <input type="hidden" name="projects_bn" id="projects_bn_json" value="{{ old('projects_bn', '[]') }}">
        <input type="hidden" name="faqs_en" id="faqs_en_json" value="{{ old('faqs_en', '[]') }}">
        <input type="hidden" name="faqs_bn" id="faqs_bn_json" value="{{ old('faqs_bn', '[]') }}">

        @include('admin.courses._form', ['course' => null])

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.courses.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Create Course</button>
        </div>
    </form>
</div>

@include('admin.courses._scripts')
@endsection
