@extends('layouts.admin')

@section('title', 'Coupon Management')
@section('eyebrow', 'Payments & Subscriptions')
@section('page_heading', 'Coupons')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search coupon code..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="is_active" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Statuses</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            @if(request('search') || request('is_active') !== null)
                <a href="{{ route('admin.coupons.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.coupons.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Coupon
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th class="text-center">Type</th>
                        <th class="text-right">Value</th>
                        <th class="text-center">Used</th>
                        <th>Expires</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <span class="cursor-pointer font-mono text-[13px] font-semibold" style="color:var(--a-ink)" title="Click to copy" onclick="navigator.clipboard.writeText('{{ $coupon->code }}')">{{ $coupon->code }}</span>
                            </td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral capitalize">{{ $coupon->type }}</span></td>
                            <td class="text-right font-semibold" style="color:var(--a-ink)">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : '৳' . number_format($coupon->value, 0) }}
                            </td>
                            <td class="text-center text-[12px]" style="color:var(--a-ink-soft)">
                                {{ $coupon->used_count }}{{ $coupon->max_uses ? '/' . $coupon->max_uses : '' }}
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $coupon->expires_at?->format('d M Y') ?? 'No Expiry' }}</td>
                            <td class="text-center">
                                <span class="admin-badge {{ $coupon->is_active ? 'admin-badge-accent' : 'admin-badge-brick' }}">{{ $coupon->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No coupons found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $coupons->links() }}</div>
        @endif
    </div>

</div>
@endsection
