@extends('layouts.admin')

@section('title', 'User Management')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Users')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name, email, phone..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="role" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>

            <select name="verified" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Verified</option>
                <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
            </select>

            @if(request('search') || request('role') || request('verified') !== null)
                <a href="{{ route('admin.users.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.users.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add User
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Roles</th>
                        <th class="text-center">Verified</th>
                        <th class="text-center">Enrollments</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $avatarBg = $user->hasRole('admin') ? ['background:var(--a-brick-soft)', 'color:var(--a-brick)'] : ($user->hasRole('instructor') ? ['background:var(--a-gold-soft)', 'color:var(--a-gold)'] : ['background:var(--a-accent-soft)', 'color:var(--a-accent)']);
                        @endphp
                        <tr @if($user->id === auth()->id()) style="background:var(--a-accent-soft)" @endif>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[13px] font-bold uppercase" style="{{ implode(';', $avatarBg) }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1 text-[13px] font-semibold leading-tight" style="color:var(--a-ink)">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="admin-badge admin-badge-accent !text-[9px]">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px]" style="color:var(--a-ink-faint)">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        @php
                                            $roleBadge = match($role->name) {
                                                'admin'      => 'admin-badge-brick',
                                                'instructor' => 'admin-badge-gold',
                                                'student'    => 'admin-badge-accent',
                                                default      => 'admin-badge-neutral',
                                            };
                                        @endphp
                                        <span class="admin-badge {{ $roleBadge }} capitalize">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                @if($user->email_verified_at)
                                    <svg class="mx-auto h-5 w-5" style="color:var(--a-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="mx-auto h-5 w-5" style="color:var(--a-line)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral">{{ $user->enrollments_count ?? $user->enrollments()->count() }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete user {{ addslashes($user->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $users->links() }}</div>
        @endif
    </div>

</div>
@endsection
