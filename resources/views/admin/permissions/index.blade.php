@extends('layouts.admin')

@section('title', 'Permission Management')
@section('eyebrow', 'Access Control')
@section('page_heading', 'Permissions')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search permission name..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="guard_name" onchange="this.form.submit()" class="admin-select w-auto">
                <option value="">All Guards</option>
                <option value="web" {{ request('guard_name') === 'web' ? 'selected' : '' }}>Web</option>
                <option value="api" {{ request('guard_name') === 'api' ? 'selected' : '' }}>API</option>
            </select>

            @if(request('search') || request('guard_name'))
                <a href="{{ route('admin.permissions.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.permissions.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Permission
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Permission</th>
                        <th class="text-center">Roles</th>
                        <th class="text-center">Guard</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>
                                <span class="cursor-pointer font-mono text-[13px] font-semibold" style="color:var(--a-ink)" title="Click to copy" onclick="navigator.clipboard.writeText('{{ $permission->name }}')">{{ $permission->name }}</span>
                            </td>
                            <td class="text-center"><span class="admin-badge admin-badge-gold">{{ $permission->roles_count }}</span></td>
                            <td class="text-center"><span class="admin-badge admin-badge-neutral">{{ $permission->guard_name }}</span></td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $permission->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Delete permission {{ addslashes($permission->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="admin-empty">No permissions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($permissions->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $permissions->links() }}</div>
        @endif
    </div>

</div>
@endsection
