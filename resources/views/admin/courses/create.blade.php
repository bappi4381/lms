@extends('layouts.admin')

@section('title', 'Create Course')
@section('page_heading', 'Create New Course')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.courses.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Courses
        </a>
    </div>

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
            <a href="{{ route('admin.courses.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">
                Create Course
            </button>
        </div>
    </form>
</div>

@include('admin.courses._scripts')
@endsection
