@extends('layouts.admin')

@section('title', 'Edit Enrollment')
@section('page_heading', 'Edit Enrollment')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Enrollments
    </a>

    <!-- Enrollment Info Card -->
    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-sky-200 text-sky-800 font-bold flex items-center justify-center uppercase text-sm shrink-0">
            {{ strtoupper(substr($enrollment->user?->name ?? '?', 0, 1)) }}
        </div>
        <div>
            <div class="font-bold text-sky-900 text-sm">{{ $enrollment->user?->name }}</div>
            <div class="text-xs text-sky-600">{{ $enrollment->course?->title_en }}</div>
        </div>
        <div class="ml-auto">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold
                {{ $enrollment->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($enrollment->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                {{ ucfirst($enrollment->payment_status) }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.enrollments.update', $enrollment) }}" class="space-y-5">
        @csrf @method('PUT')
        @include('admin.enrollments._form', ['enrollment' => $enrollment])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.enrollments.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">Cancel</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">Update Enrollment</button>
        </div>
    </form>
</div>
@endsection
