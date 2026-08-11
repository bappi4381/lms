{{-- Shared user form partial --}}
{{-- Variables: $user (null = create), $roles (Role collection), $userRoles (array of role names) --}}

<div class="space-y-6">
    <!-- Basic Info -->
    <div class="admin-card">
        <h3 class="admin-card-title">Basic Information</h3>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="admin-label">Full Name <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="name" required
                       value="{{ old('name', $user?->name) }}"
                       placeholder="e.g. Md. Sajjadul Islam"
                       class="admin-input">
                @error('name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Email Address <span style="color:var(--a-brick)">*</span></label>
                <input type="email" name="email" required
                       value="{{ old('email', $user?->email) }}"
                       placeholder="example@email.com"
                       class="admin-input">
                @error('email')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Phone Number</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $user?->phone) }}"
                       placeholder="01XXXXXXXXX"
                       class="admin-input">
            </div>
        </div>
    </div>

    <!-- Password -->
    <div class="admin-card">
        <h3 class="admin-card-title">
            Password
            @if($user)
                <span class="ml-2 text-[11px] font-normal" style="color:var(--a-ink-faint)">Leave blank to keep current password</span>
            @endif
        </h3>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="admin-label">
                    New Password @if(!$user) <span style="color:var(--a-brick)">*</span> @endif
                </label>
                <input type="password" name="password"
                       {{ !$user ? 'required' : '' }}
                       placeholder="{{ $user ? 'Leave blank to keep unchanged' : 'Min. 8 characters' }}"
                       class="admin-input">
                @error('password')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       placeholder="Repeat password"
                       class="admin-input">
            </div>
        </div>
    </div>

    <!-- Roles -->
    <div class="admin-card">
        <h3 class="admin-card-title">Roles &amp; Permissions</h3>
        <p class="-mt-2 mb-4 text-[12px]" style="color:var(--a-ink-faint)">Assign one or more roles. Roles control access to the admin panel and features.</p>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
            @foreach($roles as $role)
                @php
                    $isChecked = in_array($role->name, old('roles', $userRoles ?? []));
                    [$bg, $fg] = match($role->name) {
                        'admin'      => ['var(--a-brick-soft)', 'var(--a-brick)'],
                        'instructor' => ['var(--a-gold-soft)', 'var(--a-gold)'],
                        'student'    => ['var(--a-accent-soft)', 'var(--a-accent)'],
                        default      => ['var(--a-panel)', 'var(--a-ink-soft)'],
                    };
                @endphp
                <label class="relative cursor-pointer">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                           {{ $isChecked ? 'checked' : '' }}
                           class="peer sr-only">
                    <div class="flex items-center gap-2 rounded-ledger border-2 p-3 transition-all"
                         style="border-color:{{ $isChecked ? $fg : 'var(--a-line)' }}; background:{{ $isChecked ? $bg : 'var(--a-card)' }}; color:{{ $isChecked ? $fg : 'var(--a-ink-soft)' }}">
                        <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded border-2" style="border-color:currentColor; background:{{ $isChecked ? 'currentColor' : 'transparent' }}">
                            @if($isChecked)
                                <svg class="h-2.5 w-2.5" style="color:{{ $bg }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <span class="text-[13px] font-semibold capitalize">{{ $role->name }}</span>
                    </div>
                </label>
            @endforeach
        </div>
    </div>
</div>
