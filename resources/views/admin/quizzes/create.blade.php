@extends('layouts.admin')

@section('title', 'Add Quiz')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Add New Quiz')

@section('content')
<div class="mx-auto max-w-4xl space-y-4" x-data="quizBuilder()">
    <a href="{{ route('admin.quizzes.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Quizzes
    </a>

    <form method="POST" action="{{ route('admin.quizzes.store') }}" @submit="syncJSON()">
        @csrf
        <input type="hidden" name="questions_json" id="questions_json" :value="JSON.stringify(questions)">

        @include('admin.quizzes._form', ['quiz' => null])

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.quizzes.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Create Quiz</button>
        </div>
    </form>
</div>

@include('admin.quizzes._scripts', ['initialQuestions' => []])
@endsection
