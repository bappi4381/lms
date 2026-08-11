@extends('layouts.admin')

@section('title', 'Order Management')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Orders')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search transaction ID or user..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="payment_status" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ request('payment_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Types</option>
                <option value="course" {{ request('type') === 'course' ? 'selected' : '' }}>Course</option>
                <option value="subscription" {{ request('type') === 'subscription' ? 'selected' : '' }}>Subscription</option>
            </select>

            @if(request('search') || request('payment_status') || request('type'))
                <a href="{{ route('admin.orders.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Txn ID</th>
                        <th>User</th>
                        <th class="text-center">Type</th>
                        <th>Item</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Discount</th>
                        <th class="text-center">Status</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $statusBadge = match($order->payment_status) {
                                'paid'             => 'admin-badge-accent',
                                'pending'          => 'admin-badge-gold',
                                'failed', 'canceled' => 'admin-badge-brick',
                                'refund_requested' => 'admin-badge-gold',
                                'refunded'         => 'admin-badge-neutral',
                                default            => 'admin-badge-neutral',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="cursor-pointer font-mono text-[12px] font-semibold" style="color:var(--a-ink)" title="Click to copy" onclick="navigator.clipboard.writeText('{{ $order->transaction_id }}')">{{ $order->transaction_id }}</span>
                            </td>
                            <td class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">{{ $order->user?->name ?? '—' }}</td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral capitalize">{{ $order->type }}</span></td>
                            <td class="max-w-[180px] truncate text-[12px]" style="color:var(--a-ink-faint)">
                                {{ $order->type === 'subscription' ? $order->subscriptionPlan?->name : $order->course?->title }}
                            </td>
                            <td class="text-right font-semibold" style="color:var(--a-ink)">৳{{ number_format($order->amount, 0) }}</td>
                            <td class="text-right text-[12px]" style="color:var(--a-ink-faint)">{{ $order->discount_amount > 0 ? '৳' . number_format($order->discount_amount, 0) : '—' }}</td>
                            <td class="text-center"><span class="admin-badge {{ $statusBadge }} capitalize">{{ str_replace('_', ' ', $order->payment_status) }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $order->payment_method ?? '—' }}</td>
                            <td class="whitespace-nowrap text-[12px]" style="color:var(--a-ink-faint)">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($order->payment_status === 'refund_requested')
                                        <form method="POST" action="{{ route('admin.orders.mark-refunded', $order) }}" onsubmit="return confirm('Mark this order as refunded?')">
                                            @csrf
                                            <button type="submit" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Mark Refunded</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="admin-empty">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $orders->links() }}</div>
        @endif
    </div>

</div>
@endsection
