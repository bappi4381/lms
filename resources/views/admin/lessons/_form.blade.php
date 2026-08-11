{{-- Shared form partial for Create & Edit lesson pages. --}}
{{-- Variables: $lesson (null for create), $modules, $types, $selectedModuleId (create only) --}}
<div x-data="{ lessonType: '{{ old('type', $lesson?->type ?? 'video') }}' }" @lesson-type-changed.stop="lessonType = $event.detail">

    <div>
        <label class="admin-label">Module <span style="color:var(--a-brick)">*</span></label>
        <x-searchable-select name="module_id"
                             :options="$modules"
                             :value="old('module_id', $lesson->module_id ?? ($selectedModuleId ?? null))"
                             placeholder="— Select Module —"
                             searchPlaceholder="Search module..."
                             required="true" />
    </div>

    <div class="mt-4">
        <label class="admin-label">Lesson Title <span style="color:var(--a-brick)">*</span></label>
        <input type="text" name="title" required value="{{ old('title', $lesson?->title) }}" placeholder="e.g. Lesson 1: Getting Started" class="admin-input">
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Type <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="type"
                                 :options="$types"
                                 :value="old('type', $lesson?->type ?? 'video')"
                                 placeholder="Select Type"
                                 searchPlaceholder="Search type..."
                                 required="true"
                                 onChange="$dispatch('lesson-type-changed', selected)" />
        </div>
        <div>
            <label class="admin-label">Order</label>
            <input type="number" name="order" min="0" value="{{ old('order', $lesson?->order ?? 0) }}" class="admin-input">
        </div>
    </div>

    <div class="mt-4" x-show="lessonType === 'video'" x-cloak>
        <label class="admin-label">Bunny Video ID</label>
        <input type="text" name="video_id" value="{{ old('video_id', $lesson?->video_id) }}" placeholder="e.g. 3a4f8b91-xxxx-xxxx-xxxx-xxxxxxxxxxxx" class="admin-input">
        <p class="mt-1 text-[11px]" style="color:var(--a-ink-faint)">Bunny.net ড্যাশবোর্ড থেকে ভিডিওর নির্দিষ্ট Video ID (GUID) দিন। (Library ID দিবেন না)</p>
    </div>

    <div class="mt-4" x-show="lessonType === 'pdf'" x-cloak>
        <label class="admin-label">PDF URL</label>
        <input type="url" name="pdf_url" value="{{ old('pdf_url', $lesson?->pdf_url) }}" placeholder="https://..." class="admin-input">
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Duration (seconds)</label>
            <input type="number" name="duration_seconds" min="0" value="{{ old('duration_seconds', $lesson?->duration_seconds) }}" placeholder="e.g. 720" class="admin-input">
        </div>
        <div class="flex items-center pt-6">
            <label class="relative inline-flex cursor-pointer items-center">
                <input type="hidden" name="is_preview" value="0">
                <input type="checkbox" name="is_preview" value="1" {{ old('is_preview', $lesson?->is_preview) ? 'checked' : '' }} class="peer sr-only">
                <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
                <span class="ml-3 text-[13px] font-semibold" style="color:var(--a-ink)">Free Preview Lesson</span>
            </label>
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Lesson Notes / Content</label>
        <textarea name="content" rows="4" class="admin-textarea resize-y">{{ old('content', $lesson?->content) }}</textarea>
    </div>
</div>
