@extends('layouts.admin')

@section('title', 'Edit Course: ' . $course->title_en)
@section('page_heading', 'Edit Course')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('admin.courses.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Courses
        </a>
        <a href="{{ route('admin.courses.modules', $course) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Manage Modules ({{ $modules->count() }})
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm space-y-1">
            <p class="font-bold">Please fix the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <a href="{{ route('admin.courses.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">
                Update Course
            </button>
        </div>
    </form>
</div>

@include('admin.courses._scripts')
@endsection
