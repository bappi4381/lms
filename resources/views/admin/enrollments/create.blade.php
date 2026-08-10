@extends('layouts.admin')

@section('title', 'Add Enrollment')
@section('page_heading', 'Add Enrollment')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Enrollments
    </a>

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.enrollments.store') }}" class="space-y-5">
        @csrf
        @include('admin.enrollments._form', ['enrollment' => null])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.enrollments.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">Cancel</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">Create Enrollment</button>
        </div>
    </form>
</div>
@endsection
