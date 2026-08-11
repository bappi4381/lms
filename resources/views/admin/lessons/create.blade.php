@extends('layouts.admin')

@section('title', 'Add Lesson')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Add New Lesson')

@section('content')
<div class="mx-auto max-w-2xl space-y-4">
    <a href="{{ route('admin.lessons.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Lessons
    </a>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.lessons.store') }}">
            @csrf

            @include('admin.lessons._form', ['lesson' => null])

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.lessons.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Create Lesson</button>
            </div>
        </form>
    </div>
</div>
@endsection
