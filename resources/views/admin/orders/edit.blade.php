@extends('layouts.admin')

@section('title', 'Edit Order')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Edit Order')

@section('content')
<div class="mx-auto max-w-xl space-y-4">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex w-fit items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Orders
    </a>

    <div class="admin-card !flex items-center gap-4 !p-4" style="background:var(--a-accent-soft); border-color:var(--a-accent-soft)">
        <div>
            <div class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $order->user?->name ?? '—' }}</div>
            <div class="text-[12px]" style="color:var(--a-ink-soft)">
                {{ $order->type === 'subscription' ? $order->subscriptionPlan?->name : $order->course?->title }}
            </div>
        </div>
        <div class="ml-auto text-right">
            <div class="text-[15px] font-semibold" style="color:var(--a-ink)">৳{{ number_format($order->amount, 0) }}</div>
            @if($order->discount_amount > 0)
                <div class="text-[11px]" style="color:var(--a-ink-faint)">− ৳{{ number_format($order->discount_amount, 0) }} discount</div>
            @endif
        </div>
    </div>

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf @method('PUT')

            <div>
                <label class="admin-label">Payment Status <span style="color:var(--a-brick)">*</span></label>
                <x-searchable-select name="payment_status"
                                     :options="$statuses"
                                     :value="old('payment_status', $order->payment_status)"
                                     placeholder="Select Status"
                                     searchPlaceholder="Search..."
                                     required="true" />
                @error('payment_status')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>

            <div class="mt-4">
                <label class="admin-label">Transaction ID</label>
                <input type="text" value="{{ $order->transaction_id }}" disabled class="admin-input font-mono opacity-70">
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-5" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Update Order</button>
            </div>
        </form>
    </div>
</div>
@endsection
