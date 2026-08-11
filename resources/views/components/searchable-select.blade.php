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
            :class="open ? 'border-[var(--a-accent)] ring-2 ring-[var(--a-accent-soft)]' : 'border-[var(--a-line)] hover:border-[var(--a-ink-faint)]'"
            class="flex w-full cursor-pointer items-center justify-between rounded-ledger border bg-[var(--a-card)] px-3 py-2 text-left text-[13px] transition-all">
        <span class="truncate" :class="selected ? 'font-medium' : ''" :style="selected ? 'color:var(--a-ink)' : 'color:var(--a-ink-faint)'" x-text="selectedLabel"></span>
        <svg class="h-4 w-4 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
         class="absolute left-0 right-0 top-full z-50 mt-1.5 flex max-h-72 flex-col space-y-2 rounded-ledger border bg-[var(--a-card)] p-3 shadow-lg"
         style="border-color:var(--a-line)">

        <!-- Search Input -->
        <div class="relative shrink-0">
            <input type="text"
                   x-ref="searchInput"
                   x-model="search"
                   placeholder="{{ $searchPlaceholder }}"
                   class="admin-input">
        </div>

        <!-- Options List -->
        <div class="flex-1 space-y-0.5 overflow-y-auto pr-1" style="max-height:12rem">
            <template x-if="filteredOptions.length === 0">
                <div class="px-3 py-3 text-center text-[12px] font-medium" style="color:var(--a-ink-faint)">No options found</div>
            </template>

            <template x-for="item in filteredOptions" :key="item.value">
                <div @click="select(item.value)"
                     :class="String(selected) === String(item.value) ? 'font-semibold' : 'font-medium'"
                     :style="String(selected) === String(item.value) ? 'background:var(--a-accent-soft); color:var(--a-accent)' : 'color:var(--a-ink-soft)'"
                     class="flex cursor-pointer items-center justify-between rounded-ledger px-3 py-2 text-[13px] transition-all hover:brightness-95">
                    <span x-text="item.label" class="truncate"></span>
                    <template x-if="String(selected) === String(item.value)">
                        <svg class="h-4 w-4 shrink-0" style="color:var(--a-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
