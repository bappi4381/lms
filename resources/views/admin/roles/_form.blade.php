{{-- Shared role form partial. Variables: $role (null = create), $permissions (id => name), $rolePermissions (array of selected ids) --}}

<div class="admin-card">
    <h3 class="admin-card-title">Role Details</h3>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Role Name <span style="color:var(--a-brick)">*</span></label>
            <input type="text" name="name" required value="{{ old('name', $role?->name) }}" placeholder="e.g. editor, moderator" class="admin-input">
            @error('name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Guard <span style="color:var(--a-brick)">*</span></label>
            <input type="text" name="guard_name" required value="{{ old('guard_name', $role?->guard_name ?? 'web') }}" class="admin-input">
            @error('guard_name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="admin-card mt-6">
    <h3 class="admin-card-title">Permissions</h3>
    <p class="-mt-2 mb-4 text-[12px]" style="color:var(--a-ink-faint)">এই role কে কোন কোন permission দেওয়া হবে select করুন।</p>

    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
        @forelse($permissions as $id => $name)
            @php $checked = in_array($id, old('permissions', $rolePermissions ?? [])); @endphp
            <label class="flex items-center gap-2 rounded-ledger border px-3 py-2 text-[13px] transition-colors" style="border-color:{{ $checked ? 'var(--a-accent)' : 'var(--a-line)' }}; background:{{ $checked ? 'var(--a-accent-soft)' : 'var(--a-card)' }}; color:{{ $checked ? 'var(--a-accent)' : 'var(--a-ink-soft)' }}">
                <input type="checkbox" name="permissions[]" value="{{ $id }}" {{ $checked ? 'checked' : '' }} class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                <span class="truncate font-mono text-[12px]">{{ $name }}</span>
            </label>
        @empty
            <p class="text-[13px]" style="color:var(--a-ink-faint)">No permissions defined yet.</p>
        @endforelse
    </div>
</div>
