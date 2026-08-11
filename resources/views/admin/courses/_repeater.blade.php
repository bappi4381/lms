{{-- Simple string repeater (key_features, tools, course_includes) --}}
{{-- Variables: $fieldId, $label, $itemKey, $placeholder, $addLabel  --}}
<div class="space-y-3" x-data="repeater('{{ $fieldId }}', '{{ $itemKey }}')" x-init="init()">
    <div class="flex items-center justify-between">
        <label class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $label }}</label>
        <span class="text-[11px]" style="color:var(--a-ink-faint)" x-text="items.length + ' items'"></span>
    </div>

    <div class="space-y-2" x-ref="list">
        <template x-for="(item, index) in items" :key="index">
            <div class="flex items-center gap-2 rounded-ledger border px-3 py-2" style="border-color:var(--a-line); background:var(--a-page)">
                <svg class="h-4 w-4 shrink-0 cursor-grab" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                </svg>
                <input type="text"
                       :value="item['{{ $itemKey }}']"
                       @input="items[index]['{{ $itemKey }}'] = $event.target.value; save()"
                       placeholder="{{ $placeholder }}"
                       class="flex-1 bg-transparent text-[13px] focus:outline-none"
                       style="color:var(--a-ink)">
                <button type="button" @click="remove(index)" style="color:var(--a-brick)">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <button type="button" @click="add()" class="admin-btn admin-btn-secondary w-full justify-center border-dashed">
        {{ $addLabel }}
    </button>
</div>
