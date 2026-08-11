@extends('layouts.admin')

@section('title', 'Role Management')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Roles')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search role name..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.roles.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.roles.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Role
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th class="text-center">Permissions</th>
                        <th class="text-center">Users</th>
                        <th class="text-center">Guard</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        @php
                            $roleBadge = match($role->name) {
                                'admin'      => 'admin-badge-brick',
                                'instructor' => 'admin-badge-gold',
                                'student'    => 'admin-badge-accent',
                                default      => 'admin-badge-neutral',
                            };
                            $isCore = in_array($role->name, ['admin', 'instructor', 'student']);
                        @endphp
                        <tr>
                            <td><span class="admin-badge {{ $roleBadge }} capitalize">{{ $role->name }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral">{{ $role->permissions_count }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral">{{ $role->users_count }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral">{{ $role->guard_name }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $role->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    @if(!$isCore)
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete role {{ addslashes($role->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-empty">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($roles->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $roles->links() }}</div>
        @endif
    </div>

</div>
@endsection
