{{-- Shared user form partial --}}
{{-- Variables: $user (null = create), $roles (Role collection), $userRoles (array of role names) --}}

<div class="space-y-5">
    <!-- Basic Info -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Basic Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required
                       value="{{ old('name', $user?->name) }}"
                       placeholder="e.g. Md. Sajjadul Islam"
                       class="w-full px-4 py-2.5 rounded-xl border @error('name') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                <input type="email" name="email" required
                       value="{{ old('email', $user?->email) }}"
                       placeholder="example@email.com"
                       class="w-full px-4 py-2.5 rounded-xl border @error('email') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $user?->phone) }}"
                       placeholder="01XXXXXXXXX"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
        </div>
    </div>

    <!-- Password -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">
            Password
            @if($user)
                <span class="text-xs font-normal text-slate-400 ml-2">Leave blank to keep current password</span>
            @endif
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    New Password {{ !$user ? '<span class="text-rose-500">*</span>' : '' }}
                </label>
                <input type="password" name="password"
                       {{ !$user ? 'required' : '' }}
                       placeholder="{{ $user ? 'Leave blank to keep unchanged' : 'Min. 8 characters' }}"
                       class="w-full px-4 py-2.5 rounded-xl border @error('password') border-rose-400 bg-rose-50 @else border-slate-300 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       placeholder="Repeat password"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
        </div>
    </div>

    <!-- Roles -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6">
        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 mb-4">Roles & Permissions</h3>
        <p class="text-xs text-slate-500 mb-4">Assign one or more roles. Roles control access to the admin panel and features.</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach($roles as $role)
                @php
                    $roleColor = match($role->name) {
                        'admin'      => 'border-rose-200 bg-rose-50 text-rose-700 peer-checked:border-rose-500 peer-checked:bg-rose-100',
                        'instructor' => 'border-violet-200 bg-violet-50 text-violet-700 peer-checked:border-violet-500 peer-checked:bg-violet-100',
                        'student'    => 'border-emerald-200 bg-emerald-50 text-emerald-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-100',
                        'support'    => 'border-amber-200 bg-amber-50 text-amber-700 peer-checked:border-amber-500 peer-checked:bg-amber-100',
                        default      => 'border-slate-200 bg-slate-50 text-slate-700 peer-checked:border-sky-500 peer-checked:bg-sky-50',
                    };
                    $isChecked = in_array($role->name, old('roles', $userRoles ?? []));
                @endphp
                <label class="relative cursor-pointer">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                           {{ $isChecked ? 'checked' : '' }}
                           class="peer sr-only">
                    <div class="flex items-center gap-2 p-3 rounded-xl border-2 transition-all {{ $roleColor }}">
                        <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0
                             peer-checked:bg-current border-current
                             {{ $isChecked ? 'bg-current' : '' }}">
                            @if($isChecked)
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        <span class="text-sm font-semibold capitalize">{{ $role->name }}</span>
                    </div>
                </label>
            @endforeach
        </div>
    </div>
</div>
