{{-- Project repeater (title + required preview image upload) --}}
{{-- Variables: $fieldId, $label, $placeholder, $addLabel --}}
<div class="space-y-3" x-data="projectRepeater('{{ $fieldId }}')" x-init="init()">
    <div class="flex items-center justify-between">
        <label class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $label }}</label>
        <span class="text-[11px]" style="color:var(--a-ink-faint)" x-text="items.length + ' projects'"></span>
    </div>

    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="item._uid">
            <div class="flex items-center gap-3 rounded-ledger border p-3" style="border-color:var(--a-line); background:var(--a-page)">
                <img :src="item.image ? '/storage/' + item.image : (item._previewUrl || '')"
                     x-show="item.image || item._previewUrl"
                     class="h-14 w-20 shrink-0 rounded-ledger border object-cover"
                     style="border-color:var(--a-line)">
                <div :class="(item.image || item._previewUrl) ? '' : 'flex h-14 w-20 shrink-0 items-center justify-center rounded-ledger border'"
                     x-show="!item.image && !item._previewUrl"
                     style="border-color:var(--a-line); border-style:dashed; color:var(--a-ink-faint)">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                </div>

                <div class="flex-1 space-y-1.5">
                    <input type="text"
                           :value="item.title"
                           @input="items[index].title = $event.target.value; save()"
                           placeholder="{{ $placeholder }}"
                           class="admin-input !py-1.5">
                    <label class="inline-flex cursor-pointer items-center gap-1.5 text-[11.5px] font-semibold" style="color:var(--a-accent)">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                        <span x-text="item.image || item._previewUrl ? 'Replace preview image' : 'Upload preview image (required)'"></span>
                        <input type="file" name="{{ $fieldId }}_images[]" accept="image/*" class="hidden" @change="previewImage($event, index)">
                    </label>
                </div>

                <button type="button" @click="remove(index)" class="shrink-0 self-start" style="color:var(--a-brick)">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    <button type="button" @click="add()" class="admin-btn admin-btn-secondary w-full justify-center border-dashed">
        {{ $addLabel }}
    </button>
</div>
