{{-- Shared subscription form partial. Variables: $subscription (null = create), $users, $plans --}}
<div class="admin-card">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">User <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="user_id"
                                 :options="$users"
                                 :value="old('user_id', $subscription?->user_id)"
                                 placeholder="— Select User —"
                                 searchPlaceholder="Search user..."
                                 required="true" />
            @error('user_id')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Plan <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="subscription_plan_id"
                                 :options="$plans"
                                 :value="old('subscription_plan_id', $subscription?->subscription_plan_id)"
                                 placeholder="— Select Plan —"
                                 searchPlaceholder="Search plan..."
                                 required="true" />
            @error('subscription_plan_id')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Status <span style="color:var(--a-brick)">*</span></label>
        <x-searchable-select name="status"
                             :options="['active' => 'Active', 'expired' => 'Expired', 'cancelled' => 'Cancelled']"
                             :value="old('status', $subscription?->status ?? 'active')"
                             placeholder="Select Status"
                             searchPlaceholder="Search..."
                             required="true" />
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Starts At <span style="color:var(--a-brick)">*</span></label>
            <input type="datetime-local" name="starts_at" required value="{{ old('starts_at', $subscription?->starts_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" class="admin-input">
            @error('starts_at')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Ends At <span style="color:var(--a-brick)">*</span></label>
            <input type="datetime-local" name="ends_at" required value="{{ old('ends_at', $subscription?->ends_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
            @error('ends_at')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-4 flex items-center gap-3">
        <label class="relative inline-flex cursor-pointer items-center">
            <input type="hidden" name="auto_renew" value="0">
            <input type="checkbox" name="auto_renew" value="1" {{ old('auto_renew', $subscription?->auto_renew ?? true) ? 'checked' : '' }} class="peer sr-only">
            <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
            <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Auto Renew</span>
        </label>
    </div>
</div>
