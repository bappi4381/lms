{{-- Project repeater (title only — image upload via filesystem requires special handling) --}}
{{-- Variables: $fieldId, $label, $placeholder, $addLabel --}}
<div class="space-y-3" x-data="projectRepeater('{{ $fieldId }}')" x-init="init()">
    <div class="flex items-center justify-between">
        <label class="text-sm font-bold text-slate-700">{{ $label }}</label>
        <span class="text-xs text-slate-400" x-text="items.length + ' projects'"></span>
    </div>

    <div class="space-y-2">
        <template x-for="(item, index) in items" :key="index">
            <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2 border border-slate-200">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <input type="text"
                       :value="item.title"
                       @input="items[index].title = $event.target.value; save()"
                       placeholder="{{ $placeholder }}"
                       class="flex-1 bg-transparent text-sm focus:outline-none text-slate-700 placeholder-slate-400">
                <button type="button" @click="remove(index)"
                        class="text-rose-400 hover:text-rose-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <button type="button" @click="add()"
            class="w-full py-2 rounded-xl border-2 border-dashed border-slate-300 text-slate-500 hover:border-sky-400 hover:text-sky-600 text-sm font-semibold transition-all">
        {{ $addLabel }}
    </button>
</div>
