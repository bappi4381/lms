@extends('layouts.admin')

@section('title', 'Enrollment Management')
@section('page_heading', 'Enrollment Management')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search user, course, transaction..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="payment_status" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                <option value="">All Statuses</option>
                @foreach($paymentStatuses as $val => $label)
                    <option value="{{ $val }}" {{ request('payment_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="course_id" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white max-w-[200px]">
                <option value="">All Courses</option>
                @foreach($courses as $id => $title)
                    <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>{{ Str::limit($title, 35) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('payment_status') || request('course_id'))
                <a href="{{ route('admin.enrollments.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.enrollments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Enrollment
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Student</th>
                        <th class="py-3.5 px-4">Course</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Amount Paid</th>
                        <th class="py-3.5 px-4">Transaction ID</th>
                        <th class="py-3.5 px-4">Enrolled At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($enrollments as $enrollment)
                        @php
                            $statusBadge = match($enrollment->payment_status) {
                                'paid'     => 'bg-emerald-100 text-emerald-800',
                                'pending'  => 'bg-amber-100 text-amber-800',
                                'failed'   => 'bg-rose-100 text-rose-800',
                                'refunded' => 'bg-slate-100 text-slate-600',
                                default    => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 font-bold text-xs flex items-center justify-center shrink-0 uppercase">
                                        {{ strtoupper(substr($enrollment->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-xs leading-tight">{{ $enrollment->user?->name ?? '—' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $enrollment->user?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 max-w-[180px]">
                                <div class="font-semibold text-xs text-slate-700 truncate" title="{{ $enrollment->course?->title_en }}">
                                    {{ $enrollment->course?->title_en ?? '—' }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold {{ $statusBadge }} capitalize">
                                    {{ $enrollment->payment_status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-800">
                                ৳{{ number_format($enrollment->amount_paid, 0) }}
                            </td>
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">
                                {{ $enrollment->transaction_id ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-500">
                                {{ $enrollment->enrolled_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.enrollments.edit', $enrollment) }}"
                                       class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}"
                                          onsubmit="return confirm('Delete this enrollment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">No enrollments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
