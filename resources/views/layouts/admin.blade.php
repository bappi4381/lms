<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') — {{ config('app.name', 'SecondShiftBD') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }

        :root {
            --primary: #FF7A2E;
            --primary-hover: #e0651b;
            --bg-warm: #F4F0EE;
            --sage-sidebar: #537A61;
            --sage-sidebar-hover: #43644f;
            --sage-sidebar-active: #385342;
        }

        body {
            background-color: var(--bg-warm) !important;
        }
        
        aside {
            background-color: var(--sage-sidebar) !important;
        }
        
        aside .bg-slate-950\/60 {
            background-color: rgba(56, 83, 66, 0.4) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        aside .border-slate-800 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        aside .bg-slate-950\/40 {
            background-color: rgba(56, 83, 66, 0.3) !important;
        }
        
        aside .border-t {
            border-top-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        aside .bg-slate-800 {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        aside .bg-slate-800:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }
        
        aside a, aside button {
            color: #e2e8f0 !important;
        }
        
        aside a:hover, aside button:hover {
            background-color: var(--sage-sidebar-hover) !important;
            color: #ffffff !important;
        }
        
        aside .bg-sky-600 {
            background-color: var(--primary) !important;
            color: #ffffff !important;
        }
        
        aside .text-slate-500 {
            color: #cbd5e1 !important;
        }

        .bg-sky-600 {
            background-color: var(--primary) !important;
        }
        
        .bg-sky-600:hover {
            background-color: var(--primary-hover) !important;
        }
        
        .text-sky-600 {
            color: var(--primary) !important;
        }
        
        .text-sky-600:hover {
            color: var(--primary-hover) !important;
        }
        
        .hover\:text-sky-600:hover {
            color: var(--primary) !important;
        }
        
        .hover\:bg-sky-100:hover {
            background-color: #fff2eb !important;
            color: var(--primary) !important;
        }
        
        .bg-sky-50 {
            background-color: #fff5f0 !important;
        }
        
        .bg-sky-100 {
            background-color: #ffeae0 !important;
            color: var(--primary) !important;
        }
        
        .border-sky-200 {
            border-color: #ffd4c2 !important;
        }
        
        .border-sky-500 {
            border-color: var(--primary) !important;
        }
        
        .focus\:ring-sky-500:focus {
            --tw-ring-color: var(--primary) !important;
        }
        
        .text-sky-700 {
            color: #d15611 !important;
        }
        
        .bg-sky-200 {
            background-color: #ffd4c2 !important;
            color: #d15611 !important;
        }
        
        .text-sky-800 {
            color: #a63f05 !important;
        }
        
        .text-sky-900 {
            color: #732a00 !important;
        }
        
        .from-sky-500 {
            --tw-gradient-from: var(--primary) !important;
            --tw-gradient-to: #ffd4c2 !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }
        
        .to-indigo-600 {
            --tw-gradient-to: #d15611 !important;
        }
        
        .shadow-sky-900\/30 {
            --tw-shadow-color: rgba(255, 122, 46, 0.15) !important;
        }
    </style>
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-cloak 
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs md:hidden transition-opacity"></div>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform md:translate-x-0 md:static md:inset-0 transition-transform duration-200 ease-in-out flex flex-col shadow-xl">
            
            <!-- Brand Logo -->
            <div class="h-16 flex items-center justify-between px-6 bg-slate-950/60 border-b border-slate-800">
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 font-black text-lg text-white tracking-tight">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white text-sm shadow-md font-bold">L</span>
                    <span>Admin Panel</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-5 overflow-y-auto space-y-5">

                {{-- ── Course Management Group Dropdown ── --}}
                @php
                    $isCourseGroupActive = request()->routeIs(['admin.categories.*', 'admin.courses.*', 'admin.modules.*', 'admin.lessons.*', 'admin.assignments.*', 'admin.quizzes.*']);
                @endphp
                <div x-data="{ open: {{ $isCourseGroupActive ? 'true' : 'false' }} }}" class="space-y-1">
                    <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-[10px] font-bold text-slate-400 hover:text-white uppercase tracking-widest transition-colors">
                        <span>Course Management</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="pl-2 space-y-0.5">
                        <a href="{{ route('admin.categories.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 11h.01M7 15h.01M11 7h7M11 11h7M11 15h7M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                            <span>Categories</span>
                        </a>

                        <a href="{{ route('admin.courses.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.courses.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Courses</span>
                        </a>

                        <a href="{{ route('admin.modules.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.modules.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Modules</span>
                        </a>

                        <a href="{{ route('admin.lessons.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.lessons.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Lessons</span>
                        </a>

                        <a href="{{ route('admin.assignments.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.assignments.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Assignments</span>
                        </a>

                        <a href="{{ route('admin.quizzes.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 rounded-xl font-semibold text-xs transition-all duration-150 {{ request()->routeIs('admin.quizzes.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Quizzes</span>
                        </a>
                    </div>
                </div>

                {{-- ── Users & Enrollments Group (future phases) ── --}}
                <div>
                    <div class="px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Users & Enrollments</div>
                    <div class="space-y-0.5">

                        <a href="{{ route('admin.users.index') }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Users</span>
                        </a>

                        <a href="{{ route('admin.enrollments.index') }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 {{ request()->routeIs('admin.enrollments.*') ? 'bg-sky-600 text-white shadow-md shadow-sky-900/30' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-100' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Enrollments</span>
                        </a>

                    </div>
                </div>

                {{-- ── Payments Group ── --}}
                <div>
                    <div class="px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Finance</div>
                    <div class="space-y-0.5">

                        <a href="#"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm text-slate-600 opacity-40 cursor-not-allowed select-none">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Payments</span>
                            <span class="ml-auto text-[9px] font-bold bg-slate-700 text-slate-400 px-1.5 py-0.5 rounded-md">Soon</span>
                        </a>

                    </div>
                </div>

                {{-- ── Site Settings Group ── --}}
                <div>
                    <div class="px-3 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Site Settings</div>
                    <div class="space-y-0.5">

                        <a href="{{ route('filament.admin.pages.manage-hero-section') }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold text-sm transition-all duration-150 text-slate-400 hover:bg-slate-800/80 hover:text-slate-100"
                           title="Filament Admin (Hero, About, Pricing, etc.)">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Filament Settings</span>
                            <svg class="w-3 h-3 ml-auto opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>

                    </div>
                </div>

            </nav>

            <!-- Back to Site Link -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                <a href="{{ route('courses.index') }}" target="_blank" 
                   class="flex items-center justify-center gap-2 w-full py-2 px-3 text-xs font-bold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>View Frontend Site</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Column -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- Top Navbar Header -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shadow-xs sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg font-bold text-slate-800 truncate">@yield('page_heading', 'Dashboard')</h1>
                </div>

                <!-- User Dropdown & Notifications -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-sm shadow-xs uppercase">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-500 font-semibold">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                
                <!-- Flash Alert Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-900 text-xs font-bold px-2 py-1">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="text-rose-600 hover:text-rose-900 text-xs font-bold px-2 py-1">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-sm font-semibold shadow-xs">
                        <div class="font-bold text-amber-800 mb-1">Please fix the following validation errors:</div>
                        <ul class="list-disc list-inside space-y-1 text-xs text-amber-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
