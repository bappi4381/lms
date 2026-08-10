{{-- FAQ repeater (question + answer pairs) --}}
{{-- Variables: $fieldId, $label, $qPlaceholder, $aPlaceholder, $addLabel --}}
<div class="space-y-3" x-data="faqRepeater('{{ $fieldId }}')" x-init="init()">
    <div class="flex items-center justify-between">
        <label class="text-sm font-bold text-slate-700">{{ $label }}</label>
        <span class="text-xs text-slate-400" x-text="items.length + ' FAQs'"></span>
    </div>

    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="index">
            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xs font-bold text-slate-400 mt-1" x-text="'#' + (index + 1)"></span>
                    <button type="button" @click="remove(index)"
                            class="text-rose-400 hover:text-rose-600 transition-colors ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <input type="text"
                       :value="item.question"
                       @input="items[index].question = $event.target.value; save()"
                       placeholder="{{ $qPlaceholder }}"
                       class="w-full bg-white px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold">
                <textarea
                       :value="item.answer"
                       @input="items[index].answer = $event.target.value; save()"
                       placeholder="{{ $aPlaceholder }}"
                       rows="2"
                       class="w-full bg-white px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none"></textarea>
            </div>
        </template>
    </div>

    <button type="button" @click="add()"
            class="w-full py-2 rounded-xl border-2 border-dashed border-slate-300 text-slate-500 hover:border-sky-400 hover:text-sky-600 text-sm font-semibold transition-all">
        {{ $addLabel }}
    </button>
</div>
