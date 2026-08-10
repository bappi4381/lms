@props([
    'name',
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option...',
    'searchPlaceholder' => 'Search...',
    'required' => false,
    'id' => null,
    'onChange' => null,
])

@php
    $formattedOptions = [];
    foreach ($options as $k => $v) {
        $formattedOptions[] = [
            'value' => (string) $k,
            'label' => (string) $v,
        ];
    }
    $initialValue = old($name, $value) !== null ? (string) old($name, $value) : '';
@endphp

<div x-data="{
        open: false,
        search: '',
        selected: '{{ $initialValue }}',
        options: {{ json_encode($formattedOptions) }},
        get selectedLabel() {
            let item = this.options.find(o => String(o.value) === String(this.selected));
            return item ? item.label : '{{ $placeholder }}';
        },
        get filteredOptions() {
            if (!this.search.trim()) return this.options;
            return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        select(val) {
            this.selected = val;
            this.open = false;
            this.search = '';
            $nextTick(() => {
                let el = $refs.hiddenInput;
                if (el) {
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    @if($onChange)
                        {{ $onChange }};
                    @endif
                }
            });
        }
     }"
     @click.away="open = false"
     class="relative w-full">

    <!-- Hidden Input for Form Submission -->
    <input type="hidden"
           name="{{ $name }}"
           id="{{ $id ?? $name }}"
           x-ref="hiddenInput"
           :value="selected"
           @if($required) required @endif>

    <!-- Trigger Button -->
    <button type="button"
            @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
            :class="open ? 'border-sky-500 ring-2 ring-sky-100' : 'border-slate-300 hover:border-slate-400'"
            class="w-full px-4 py-2.5 rounded-xl border bg-white text-left text-sm flex items-center justify-between shadow-2xs transition-all cursor-pointer">
        <span class="truncate" :class="selected ? 'text-slate-800 font-semibold' : 'text-slate-400'" x-text="selectedLabel"></span>
        <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180 text-sky-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute left-0 right-0 top-full mt-1.5 z-50 bg-white rounded-2xl border border-sky-200 shadow-xl p-3 space-y-2 max-h-72 flex flex-col">

        <!-- Search Input -->
        <div class="relative shrink-0">
            <input type="text"
                   x-ref="searchInput"
                   x-model="search"
                   placeholder="{{ $searchPlaceholder }}"
                   class="w-full px-3.5 py-2 rounded-xl border-2 border-sky-400 text-sm text-slate-800 focus:outline-none focus:border-sky-500 placeholder-slate-400 shadow-2xs">
        </div>

        <!-- Options List -->
        <div class="overflow-y-auto flex-1 space-y-0.5 max-h-48 pr-1">
            <template x-if="filteredOptions.length === 0">
                <div class="px-3 py-3 text-xs text-center text-slate-400 font-medium">No options found</div>
            </template>

            <template x-for="item in filteredOptions" :key="item.value">
                <div @click="select(item.value)"
                     :class="String(selected) === String(item.value) ? 'bg-sky-50 text-sky-600 font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900 font-medium'"
                     class="px-3 py-2 rounded-xl text-sm cursor-pointer transition-all flex items-center justify-between">
                    <span x-text="item.label" class="truncate"></span>
                    <template x-if="String(selected) === String(item.value)">
                        <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
