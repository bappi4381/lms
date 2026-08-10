@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total Users --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(255,122,46,0.12)">
                <svg class="w-6 h-6" style="color:#FF7A2E" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($stats['total_users']) }}</div>
                <div class="text-xs font-semibold text-slate-500 mt-0.5">Total Users</div>
            </div>
        </div>

        {{-- Total Courses --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(83,122,97,0.12)">
                <svg class="w-6 h-6" style="color:#537A61" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($stats['total_courses']) }}</div>
                <div class="text-xs font-semibold text-slate-500 mt-0.5">Total Courses</div>
                <div class="text-[11px] text-emerald-600 font-semibold">{{ $stats['published_courses'] }} Published</div>
            </div>
        </div>

        {{-- Total Enrollments --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(99,102,241,0.10)">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($stats['total_enrollments']) }}</div>
                <div class="text-xs font-semibold text-slate-500 mt-0.5">Enrollments</div>
                <div class="text-[11px] text-emerald-600 font-semibold">{{ $stats['paid_enrollments'] }} Paid</div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(16,185,129,0.12)">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-800">৳{{ number_format($stats['total_revenue'], 0) }}</div>
                <div class="text-xs font-semibold text-slate-500 mt-0.5">Total Revenue</div>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Top Courses + Recent Users ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Top Courses by Enrollments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">🏆 Top Courses</h2>
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold" style="color:#FF7A2E">View All →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topCourses as $i => $course)
                <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-slate-50/60 transition-colors">
                    <div class="w-7 h-7 rounded-xl font-black text-xs flex items-center justify-center shrink-0 text-white"
                         style="background: {{ $i === 0 ? '#FF7A2E' : ($i === 1 ? '#537A61' : '#94a3b8') }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-800 truncate">{{ $course->title }}</div>
                        <div class="text-xs text-slate-400">{{ $course->category?->name ?? '—' }}</div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold" style="color:#537A61">{{ $course->enrollments_count }}</span>
                        <span class="text-xs text-slate-400">enrollments</span>
                    </div>
                    <div>
                        @if($course->is_published)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Live</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Draft</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">No courses yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">👥 Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold" style="color:#FF7A2E">View All →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentUsers as $user)
                <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50/60 transition-colors">
                    <div class="w-9 h-9 rounded-full font-bold text-sm flex items-center justify-center text-white uppercase shrink-0"
                         style="background: #537A61">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-800 truncate">{{ $user->name }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ $user->email }}</div>
                    </div>
                    <div class="text-xs text-slate-400 whitespace-nowrap">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">No users yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Row 3: Recent Enrollments ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">📋 Recent Enrollments</h2>
            <a href="{{ route('admin.enrollments.index') }}" class="text-xs font-semibold" style="color:#FF7A2E">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3 text-left">User</th>
                        <th class="px-5 py-3 text-left">Course</th>
                        <th class="px-5 py-3 text-left">Amount</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentEnrollments as $enrollment)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-800">{{ $enrollment->user?->name ?? '—' }}</div>
                            <div class="text-xs text-slate-400">{{ $enrollment->user?->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-slate-700 font-medium truncate max-w-[180px]">{{ $enrollment->course?->title ?? '—' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-800">৳{{ number_format($enrollment->amount_paid, 0) }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($enrollment->payment_status === 'paid')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                            @elseif($enrollment->payment_status === 'pending')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Pending</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">{{ ucfirst($enrollment->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400 whitespace-nowrap">
                            {{ $enrollment->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">No enrollments yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Row 4: Quick Actions ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="text-sm font-bold text-slate-800 mb-4">⚡ Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            <a href="{{ route('admin.courses.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/40 transition-all group text-center">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-xs font-semibold text-slate-500 group-hover:text-orange-600">New Course</span>
            </a>
            <a href="{{ route('admin.categories.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/40 transition-all group text-center">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h7M11 11h7M11 15h7M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-500 group-hover:text-orange-600">New Category</span>
            </a>
            <a href="{{ route('admin.enrollments.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/40 transition-all group text-center">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-500 group-hover:text-orange-600">Add Enrollment</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/40 transition-all group text-center">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-500 group-hover:text-orange-600">Manage Users</span>
            </a>
            <a href="{{ route('admin.quizzes.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/40 transition-all group text-center">
                <svg class="w-7 h-7 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-500 group-hover:text-orange-600">New Quiz</span>
            </a>
        </div>
    </div>

</div>
@endsection
