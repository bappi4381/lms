{{-- Shared coupon form partial. Variables: $coupon (null = create), $courses --}}
<div x-data="{ applicableTo: '{{ old('applicable_to', $coupon?->applicable_to ?? 'all') }}' }" class="admin-card">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Code <span style="color:var(--a-brick)">*</span></label>
            <input type="text" name="code" required value="{{ old('code', $coupon?->code) }}" placeholder="e.g. SUMMER25" class="admin-input font-mono uppercase">
            @error('code')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Type <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="type"
                                 :options="['percent' => 'Percent (%)', 'fixed' => 'Fixed Amount (৳)']"
                                 :value="old('type', $coupon?->type ?? 'percent')"
                                 placeholder="Select Type"
                                 searchPlaceholder="Search..."
                                 required="true" />
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Value <span style="color:var(--a-brick)">*</span></label>
            <input type="number" name="value" step="0.01" min="0" required value="{{ old('value', $coupon?->value) }}" class="admin-input">
            @error('value')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Max Uses <span class="text-[11px] font-normal" style="color:var(--a-ink-faint)">(blank = unlimited)</span></label>
            <input type="number" name="max_uses" min="1" value="{{ old('max_uses', $coupon?->max_uses) }}" class="admin-input">
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Minimum Order Amount</label>
        <div class="flex items-center">
            <span class="flex h-[38px] items-center rounded-l-ledger border px-3 text-[13px] font-bold" style="background:var(--a-panel); border-color:var(--a-line); color:var(--a-ink-soft)">৳</span>
            <input type="number" name="min_order_amount" step="0.01" min="0" value="{{ old('min_order_amount', $coupon?->min_order_amount) }}" class="admin-input flex-1 !rounded-l-none">
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Applicable To <span style="color:var(--a-brick)">*</span></label>
        <x-searchable-select name="applicable_to"
                             :options="['all' => 'All Courses', 'specific_courses' => 'Specific Courses']"
                             :value="old('applicable_to', $coupon?->applicable_to ?? 'all')"
                             placeholder="Select"
                             searchPlaceholder="Search..."
                             required="true"
                             onChange="applicableTo = selected" />
    </div>

    <div class="mt-4" x-show="applicableTo === 'specific_courses'" x-cloak>
        <label class="admin-label">Applicable Courses</label>
        <select name="course_ids[]" multiple class="admin-select" style="min-height:140px">
            @foreach($courses as $id => $title)
                <option value="{{ $id }}" {{ in_array($id, old('course_ids', $coupon?->course_ids ?? [])) ? 'selected' : '' }}>{{ $title }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-[11px]" style="color:var(--a-ink-faint)">Ctrl/Cmd + click to select multiple courses.</p>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Starts At</label>
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
        </div>
        <div>
            <label class="admin-label">Expires At</label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\TH:i')) }}" class="admin-input">
        </div>
    </div>

    <div class="mt-4 flex items-center gap-3">
        <label class="relative inline-flex cursor-pointer items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon?->is_active ?? true) ? 'checked' : '' }} class="peer sr-only">
            <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
            <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Active</span>
        </label>
    </div>
</div>
