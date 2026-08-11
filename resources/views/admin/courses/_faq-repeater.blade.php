{{-- FAQ repeater (question + answer pairs) --}}
{{-- Variables: $fieldId, $label, $qPlaceholder, $aPlaceholder, $addLabel --}}
<div class="space-y-3" x-data="faqRepeater('{{ $fieldId }}')" x-init="init()">
    <div class="flex items-center justify-between">
        <label class="text-[13px] font-semibold" style="color:var(--a-ink)">{{ $label }}</label>
        <span class="text-[11px]" style="color:var(--a-ink-faint)" x-text="items.length + ' FAQs'"></span>
    </div>

    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="index">
            <div class="space-y-3 rounded-ledger border p-4" style="border-color:var(--a-line); background:var(--a-page)">
                <div class="flex items-start justify-between gap-2">
                    <span class="mt-1 text-[11px] font-semibold" style="color:var(--a-ink-faint)" x-text="'#' + (index + 1)"></span>
                    <button type="button" @click="remove(index)" class="ml-auto" style="color:var(--a-brick)">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="text"
                       :value="item.question"
                       @input="items[index].question = $event.target.value; save()"
                       placeholder="{{ $qPlaceholder }}"
                       class="admin-input font-semibold">
                <textarea
                       :value="item.answer"
                       @input="items[index].answer = $event.target.value; save()"
                       placeholder="{{ $aPlaceholder }}"
                       rows="2"
                       class="admin-textarea resize-none"></textarea>
            </div>
        </template>
    </div>

    <button type="button" @click="add()" class="admin-btn admin-btn-secondary w-full justify-center border-dashed">
        {{ $addLabel }}
    </button>
</div>
