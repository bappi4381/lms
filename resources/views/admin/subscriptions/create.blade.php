@extends('layouts.admin')

@section('title', 'Add Subscription')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Add New Subscription')

@section('content')
<div class="mx-auto max-w-2xl space-y-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Subscriptions
    </a>

    <form method="POST" action="{{ route('admin.subscriptions.store') }}">
        @csrf
        @include('admin.subscriptions._form', ['subscription' => null])
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.subscriptions.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
            <button type="submit" class="admin-btn admin-btn-primary">Create Subscription</button>
        </div>
    </form>
</div>
@endsection
