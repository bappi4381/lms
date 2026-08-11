@extends('layouts.admin')

@section('title', 'Subscription Plan Management')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Subscription Plans')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.subscription-plans.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search plan name..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.subscription-plans.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.subscription-plans.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Plan
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Interval</th>
                        <th class="text-center">Subscribers</th>
                        <th class="text-center">Active</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $plan->name }}</td>
                            <td class="text-right font-semibold" style="color:var(--a-ink)">৳{{ number_format($plan->price, 0) }}</td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral capitalize">{{ $plan->interval }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-accent">{{ $plan->subscriptions_count }}</span></td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.subscription-plans.update', $plan) }}" class="inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="name" value="{{ $plan->name }}">
                                    <input type="hidden" name="slug" value="{{ $plan->slug }}">
                                    <input type="hidden" name="price" value="{{ $plan->price }}">
                                    <input type="hidden" name="interval" value="{{ $plan->interval }}">
                                    <input type="hidden" name="description" value="{{ $plan->description }}">
                                    <input type="hidden" name="is_active" value="{{ $plan->is_active ? 0 : 1 }}">
                                    <button type="submit" class="admin-badge {{ $plan->is_active ? 'admin-badge-accent' : 'admin-badge-brick' }}">{{ $plan->is_active ? 'Active' : 'Inactive' }}</button>
                                </form>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.subscription-plans.edit', $plan) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.subscription-plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">No subscription plans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($plans->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $plans->links() }}</div>
        @endif
    </div>

</div>
@endsection
