@extends('layouts.admin')

@section('title', 'Dashboard')
@section('eyebrow', 'Overview')
@section('page_heading', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="admin-stat-card">
            <p class="admin-eyebrow">Total Users</p>
            <p class="admin-stat-value mt-2">{{ number_format($stats['total_users']) }}</p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-eyebrow">Total Courses</p>
            <p class="admin-stat-value mt-2">{{ number_format($stats['total_courses']) }}</p>
            <p class="mt-1 text-[11px] font-semibold" style="color:var(--a-accent)">{{ $stats['published_courses'] }} published</p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-eyebrow">Enrollments</p>
            <p class="admin-stat-value mt-2">{{ number_format($stats['total_enrollments']) }}</p>
            <p class="mt-1 text-[11px] font-semibold" style="color:var(--a-accent)">{{ $stats['paid_enrollments'] }} paid</p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-eyebrow">Total Revenue</p>
            <p class="admin-stat-value mt-2">৳{{ number_format($stats['total_revenue'], 0) }}</p>
        </div>
    </div>

    {{-- ── Row 2: Top Courses + Recent Users ── --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

        <!-- Top Courses by Enrollments -->
        <div class="admin-card !p-0">
            <div class="admin-card-head">
                <h2 class="admin-card-title">Top Courses</h2>
                <a href="{{ route('admin.courses.index') }}" class="text-[12px] font-semibold" style="color:var(--a-accent)">View All &rarr;</a>
            </div>
            <div class="divide-y" style="border-color:var(--a-line-soft)">
                @forelse($topCourses as $i => $course)
                <div class="flex items-center gap-4 px-5 py-3.5" style="border-color:var(--a-line-soft)">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[11px] font-bold"
                         style="background: {{ $i === 0 ? 'var(--a-gold)' : ($i === 1 ? 'var(--a-accent)' : 'var(--a-line)') }}; color:{{ $i <= 1 ? '#fff' : 'var(--a-ink-soft)' }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-[13px] font-semibold" style="color:var(--a-ink)">{{ $course->title }}</div>
                        <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $course->category?->name ?? '—' }}</div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[12px] font-bold" style="color:var(--a-accent)">{{ $course->enrollments_count }}</span>
                        <span class="text-[11px]" style="color:var(--a-ink-faint)">enrollments</span>
                    </div>
                    <div>
                        @if($course->is_published)
                            <span class="admin-badge admin-badge-accent">Live</span>
                        @else
                            <span class="admin-badge admin-badge-gold">Draft</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="admin-empty">No courses yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="admin-card !p-0">
            <div class="admin-card-head">
                <h2 class="admin-card-title">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-[12px] font-semibold" style="color:var(--a-accent)">View All &rarr;</a>
            </div>
            <div class="divide-y" style="border-color:var(--a-line-soft)">
                @forelse($recentUsers as $user)
                <div class="flex items-center gap-3 px-5 py-3.5" style="border-color:var(--a-line-soft)">
                    <div class="admin-avatar uppercase">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-[13px] font-semibold" style="color:var(--a-ink)">{{ $user->name }}</div>
                        <div class="truncate text-[11px]" style="color:var(--a-ink-faint)">{{ $user->email }}</div>
                    </div>
                    <div class="whitespace-nowrap text-[11px]" style="color:var(--a-ink-faint)">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="admin-empty">No users yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Row 3: Recent Enrollments ── --}}
    <div class="admin-table-wrap">
        <div class="admin-card-head" style="border-color:var(--a-line)">
            <h2 class="admin-card-title">Recent Enrollments</h2>
            <a href="{{ route('admin.enrollments.index') }}" class="text-[12px] font-semibold" style="color:var(--a-accent)">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEnrollments as $enrollment)
                    <tr>
                        <td>
                            <div class="font-semibold" style="color:var(--a-ink)">{{ $enrollment->user?->name ?? '—' }}</div>
                            <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $enrollment->user?->email }}</div>
                        </td>
                        <td>
                            <div class="max-w-[180px] truncate font-medium" style="color:var(--a-ink-soft)">{{ $enrollment->course?->title ?? '—' }}</div>
                        </td>
                        <td class="font-semibold" style="color:var(--a-ink)">৳{{ number_format($enrollment->amount_paid, 0) }}</td>
                        <td>
                            @if($enrollment->payment_status === 'paid')
                                <span class="admin-badge admin-badge-accent">Paid</span>
                            @elseif($enrollment->payment_status === 'pending')
                                <span class="admin-badge admin-badge-gold">Pending</span>
                            @else
                                <span class="admin-badge admin-badge-neutral">{{ ucfirst($enrollment->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap text-[12px]" style="color:var(--a-ink-faint)">
                            {{ $enrollment->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="admin-empty">No enrollments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Row 4: Quick Actions ── --}}
    <div class="admin-card">
        <h2 class="admin-card-title mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-5">
            <a href="{{ route('admin.courses.create') }}"
               class="group flex flex-col items-center gap-2 rounded-ledger border-2 border-dashed border-[var(--a-line)] p-4 text-center transition-colors hover:border-[var(--a-accent)] hover:bg-[var(--a-accent-soft)]">
                <svg class="h-6 w-6 text-[var(--a-ink-faint)] transition-colors group-hover:text-[var(--a-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-[12px] font-semibold text-[var(--a-ink-soft)] group-hover:text-[var(--a-accent)]">New Course</span>
            </a>
            <a href="{{ route('admin.categories.create') }}"
               class="group flex flex-col items-center gap-2 rounded-ledger border-2 border-dashed border-[var(--a-line)] p-4 text-center transition-colors hover:border-[var(--a-accent)] hover:bg-[var(--a-accent-soft)]">
                <svg class="h-6 w-6 text-[var(--a-ink-faint)] transition-colors group-hover:text-[var(--a-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h7M11 11h7M11 15h7M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                </svg>
                <span class="text-[12px] font-semibold text-[var(--a-ink-soft)] group-hover:text-[var(--a-accent)]">New Category</span>
            </a>
            <a href="{{ route('admin.enrollments.create') }}"
               class="group flex flex-col items-center gap-2 rounded-ledger border-2 border-dashed border-[var(--a-line)] p-4 text-center transition-colors hover:border-[var(--a-accent)] hover:bg-[var(--a-accent-soft)]">
                <svg class="h-6 w-6 text-[var(--a-ink-faint)] transition-colors group-hover:text-[var(--a-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="text-[12px] font-semibold text-[var(--a-ink-soft)] group-hover:text-[var(--a-accent)]">Add Enrollment</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="group flex flex-col items-center gap-2 rounded-ledger border-2 border-dashed border-[var(--a-line)] p-4 text-center transition-colors hover:border-[var(--a-accent)] hover:bg-[var(--a-accent-soft)]">
                <svg class="h-6 w-6 text-[var(--a-ink-faint)] transition-colors group-hover:text-[var(--a-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-[12px] font-semibold text-[var(--a-ink-soft)] group-hover:text-[var(--a-accent)]">Manage Users</span>
            </a>
            <a href="{{ route('admin.quizzes.create') }}"
               class="group flex flex-col items-center gap-2 rounded-ledger border-2 border-dashed border-[var(--a-line)] p-4 text-center transition-colors hover:border-[var(--a-accent)] hover:bg-[var(--a-accent-soft)]">
                <svg class="h-6 w-6 text-[var(--a-ink-faint)] transition-colors group-hover:text-[var(--a-accent)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-[12px] font-semibold text-[var(--a-ink-soft)] group-hover:text-[var(--a-accent)]">New Quiz</span>
            </a>
        </div>
    </div>

</div>
@endsection
