@extends('layouts.admin')

@section('title', 'User Management')
@section('page_heading', 'User Management')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name, email, phone..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="role" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>

            <select name="verified" onchange="this.form.submit()"
                    class="py-2 px-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                <option value="">All Verified</option>
                <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
            </select>

            @if(request('search') || request('role') || request('verified') !== null)
                <a href="{{ route('admin.users.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add User
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Users',   'count' => \App\Models\User::count(),                             'color' => 'sky'],
            ['label' => 'Students',      'count' => \App\Models\User::role('student')->count(),             'color' => 'emerald'],
            ['label' => 'Instructors',   'count' => \App\Models\User::role('instructor')->count(),          'color' => 'violet'],
            ['label' => 'Admins',        'count' => \App\Models\User::role('admin')->count(),               'color' => 'rose'],
        ] as $stat)
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center">
                <div class="text-2xl font-extrabold text-{{ $stat['color'] }}-600">{{ $stat['count'] }}</div>
                <div class="text-xs text-slate-500 font-medium mt-1">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Phone</th>
                        <th class="py-3.5 px-4">Roles</th>
                        <th class="py-3.5 px-4 text-center">Verified</th>
                        <th class="py-3.5 px-4 text-center">Enrollments</th>
                        <th class="py-3.5 px-4">Joined</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $user->id === auth()->id() ? 'bg-sky-50/30' : '' }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 uppercase
                                        {{ $user->hasRole('admin') ? 'bg-rose-100 text-rose-700' : ($user->hasRole('instructor') ? 'bg-violet-100 text-violet-700' : 'bg-sky-100 text-sky-700') }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-sm leading-tight flex items-center gap-1">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] bg-sky-100 text-sky-700 px-1.5 py-0.5 rounded font-bold">You</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-500">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        @php
                                            $roleColor = match($role->name) {
                                                'admin'      => 'bg-rose-100 text-rose-700 border-rose-200',
                                                'instructor' => 'bg-violet-100 text-violet-700 border-violet-200',
                                                'student'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'support'    => 'bg-amber-100 text-amber-700 border-amber-200',
                                                default      => 'bg-slate-100 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border capitalize {{ $roleColor }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($user->email_verified_at)
                                    <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-5 h-5 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                    {{ $user->enrollments_count ?? $user->enrollments()->count() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">{{ $users->links() }}</div>
        @endif
    </div>

</div>
@endsection
