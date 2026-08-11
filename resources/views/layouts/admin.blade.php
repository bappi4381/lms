<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') — {{ config('app.name', 'LMS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="admin-shell h-full antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen flex-col md:flex-row">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/30 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="admin-sidebar fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 -translate-x-full flex-col transition-transform duration-200 ease-in-out md:sticky md:top-0 md:h-screen md:translate-x-0">

            <div class="flex items-center justify-between border-b px-6 py-6" style="border-color:var(--a-line)">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col">
                    <span class="admin-brand-mark text-[20px]">{{ config('app.name', 'LMS') }}</span>
                    <span class="admin-brand-sub mt-1">Admin Panel</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="md:hidden" style="color:var(--a-ink-soft)">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-6 overflow-y-auto px-3.5 py-5">

                {{-- ── Dashboard ── --}}
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                {{-- ── Course Management ── --}}
                @php
                    $isCourseGroupActive = request()->routeIs(['admin.categories.*', 'admin.courses.*', 'admin.modules.*', 'admin.lessons.*', 'admin.assignments.*', 'admin.quizzes.*']);
                @endphp
                <div x-data="{ open: {{ $isCourseGroupActive ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-ledger px-2.5 py-1.5">
                        <span class="admin-nav-label">Course Management</span>
                        <svg class="h-3 w-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 space-y-0.5">
                        <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 7h.01M7 11h.01M7 15h.01M11 7h7M11 11h7M11 15h7M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="admin-nav-item {{ request()->routeIs('admin.courses.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Courses</span>
                        </a>
                        <a href="{{ route('admin.modules.index') }}" class="admin-nav-item {{ request()->routeIs('admin.modules.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Modules</span>
                        </a>
                        <a href="{{ route('admin.lessons.index') }}" class="admin-nav-item {{ request()->routeIs('admin.lessons.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Lessons</span>
                        </a>
                        <a href="{{ route('admin.assignments.index') }}" class="admin-nav-item {{ request()->routeIs('admin.assignments.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Assignments</span>
                        </a>
                        <a href="{{ route('admin.quizzes.index') }}" class="admin-nav-item {{ request()->routeIs('admin.quizzes.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Quizzes</span>
                        </a>
                    </div>
                </div>

                {{-- ── Users & Enrollments ── --}}
                <div>
                    <div class="admin-nav-label px-2.5 pb-2">Users &amp; Enrollments</div>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Users</span>
                        </a>
                        <a href="{{ route('admin.enrollments.index') }}" class="admin-nav-item {{ request()->routeIs('admin.enrollments.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Enrollments</span>
                        </a>
                    </div>
                </div>

                {{-- ── Access Control ── --}}
                @if(auth()->user()?->hasRole('admin'))
                    <div>
                        <div class="admin-nav-label px-2.5 pb-2">Access Control</div>
                        <div class="space-y-0.5">
                            @include('layouts.partials.admin-nav-item', ['route' => 'admin.roles.index', 'pattern' => 'admin.roles.*', 'label' => 'Roles', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'])
                            @include('layouts.partials.admin-nav-item', ['route' => 'admin.permissions.index', 'pattern' => 'admin.permissions.*', 'label' => 'Permissions', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 11-12 0 6 6 0 0112 0zM9 21l-4-4m0 0l4-4m-4 4h11'])
                            @include('layouts.partials.admin-nav-item', ['route' => 'admin.devices.index', 'pattern' => 'admin.devices.*', 'label' => 'Devices', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'])
                        </div>
                    </div>
                @endif

                {{-- ── Payments & Subscriptions ── --}}
                <div>
                    <div class="admin-nav-label px-2.5 pb-2">Payments &amp; Subscriptions</div>
                    <div class="space-y-0.5">
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.orders.index', 'pattern' => 'admin.orders.*', 'label' => 'Orders', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'])
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.coupons.index', 'pattern' => 'admin.coupons.*', 'label' => 'Coupons', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'])
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.subscription-plans.index', 'pattern' => 'admin.subscription-plans.*', 'label' => 'Subscription Plans', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'])
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.subscriptions.index', 'pattern' => 'admin.subscriptions.*', 'label' => 'Subscriptions', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'])
                    </div>
                </div>

                {{-- ── Support & Engagement ── --}}
                <div>
                    <div class="admin-nav-label px-2.5 pb-2">Support &amp; Engagement</div>
                    <div class="space-y-0.5">
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.support-tickets.index', 'pattern' => 'admin.support-tickets.*', 'label' => 'Support Tickets', 'icon' => 'M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9'])
                        @include('layouts.partials.admin-nav-item', ['route' => 'admin.reviews.index', 'pattern' => 'admin.reviews.*', 'label' => 'Reviews', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'])
                    </div>
                </div>

                {{-- ── Site Settings ── --}}
                @php
                    $isSettingsGroupActive = request()->routeIs('admin.settings.*');
                @endphp
                <div x-data="{ open: {{ $isSettingsGroupActive ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-ledger px-2.5 py-1.5">
                        <span class="admin-nav-label">Site Settings</span>
                        <svg class="h-3 w-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-1 space-y-0.5">
                        <a href="{{ route('admin.settings.hero.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.hero.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                            <span>Hero Section</span>
                        </a>
                        <a href="{{ route('admin.settings.about.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.about.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>About Section</span>
                        </a>
                        <a href="{{ route('admin.settings.why-us.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.why-us.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span>Why Choose Us</span>
                        </a>
                        <a href="{{ route('admin.settings.pricing.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.pricing.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v-2m0-8c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pricing Section</span>
                        </a>
                        <a href="{{ route('admin.settings.testimonials.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.testimonials.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
                            <span>Testimonials</span>
                        </a>
                        <a href="{{ route('admin.settings.header-footer.edit') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.header-footer.*') ? 'is-active' : '' }}">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h1a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Header &amp; Footer</span>
                        </a>
                    </div>
                </div>
            </nav>

            <div class="border-t px-4 py-4" style="border-color:var(--a-line)">
                <a href="{{ route('courses.index') }}" target="_blank" class="admin-btn admin-btn-secondary w-full">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Frontend Site</span>
                </a>
            </div>
        </aside>

        <!-- Main column -->
        <div class="flex min-w-0 flex-1 flex-col">

            <!-- Top bar -->
            <header class="admin-topbar sticky top-0 z-30 flex h-16 items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="rounded-ledger p-2 md:hidden" style="color:var(--a-ink-soft)">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <div class="admin-eyebrow">@yield('eyebrow', 'Admin')</div>
                        <h1 class="admin-heading text-[19px]">@yield('page_heading', 'Dashboard')</h1>
                    </div>
                </div>

                @auth
                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <div class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ Auth::user()->name }}</div>
                            <div class="text-[11px]" style="color:var(--a-ink-soft)">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                    </div>
                @endauth
            </header>

            <!-- Content -->
            <main class="mx-auto w-full max-w-7xl flex-1 p-4 sm:p-6 lg:p-8">

                @if(session('success'))
                    <div class="admin-flash admin-flash-success mb-6" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="text-xs font-bold">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="admin-flash admin-flash-error mb-6" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-2.5">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="text-xs font-bold">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="admin-flash admin-flash-error mb-6 !items-start">
                        <div>
                            <div class="mb-1 font-bold">Please fix the following:</div>
                            <ul class="list-inside list-disc space-y-0.5 text-[12.5px] font-normal">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
