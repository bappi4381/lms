{{-- Shared subscription-plan form partial. Variables: $plan (null = create) --}}
<div class="admin-card">

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Name <span style="color:var(--a-brick)">*</span></label>
            <input type="text" id="name" name="name" required value="{{ old('name', $plan?->name) }}" oninput="autoSlug(this.value)" class="admin-input">
            @error('name')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Slug <span style="color:var(--a-brick)">*</span></label>
            <input type="text" id="slug" name="slug" required value="{{ old('slug', $plan?->slug) }}" class="admin-input font-mono">
            @error('slug')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Price <span style="color:var(--a-brick)">*</span></label>
            <div class="flex items-center">
                <span class="flex h-[38px] items-center rounded-l-ledger border px-3 text-[13px] font-bold" style="background:var(--a-panel); border-color:var(--a-line); color:var(--a-ink-soft)">৳</span>
                <input type="number" name="price" step="0.01" min="0" required value="{{ old('price', $plan?->price) }}" class="admin-input flex-1 !rounded-l-none">
            </div>
            @error('price')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Interval <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="interval"
                                 :options="['monthly' => 'Monthly', 'yearly' => 'Yearly']"
                                 :value="old('interval', $plan?->interval ?? 'monthly')"
                                 placeholder="Select Interval"
                                 searchPlaceholder="Search..."
                                 required="true" />
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Description</label>
        <textarea name="description" rows="3" class="admin-textarea resize-y">{{ old('description', $plan?->description) }}</textarea>
    </div>

    <div class="mt-4 flex items-center gap-3">
        <label class="relative inline-flex cursor-pointer items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan?->is_active ?? true) ? 'checked' : '' }} class="peer sr-only">
            <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
            <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Active</span>
        </label>
    </div>
</div>

<script>
    function autoSlug(value) {
        const slugField = document.getElementById('slug');
        if (slugField && !slugField.dataset.manual) {
            slugField.value = value.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        const slugField = document.getElementById('slug');
        if (slugField) {
            slugField.addEventListener('input', function () { this.dataset.manual = 'true'; });
        }
    });
</script>
