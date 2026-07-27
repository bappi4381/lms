@php
    $sidebarClass = ($mobile ?? false)
        ? 'w-full'
        : 'hidden lg:block w-64 xl:w-72 shrink-0';
@endphp

<aside class="{{ $sidebarClass }}">
    <div class="{{ ($mobile ?? false) ? '' : 'glass-sidebar p-5 ' }}{{ ($mobile ?? false) ? '' : 'sticky top-24' }}">
        @unless($mobile ?? false)
            <h2 class="text-lg font-extrabold text-brand-navy mb-6">ফিল্টার</h2>
        @endunless

        {{-- Category --}}
        <div class="mb-8">
            <h3 class="text-sm font-bold text-brand-navy mb-3">ক্যাটাগরি</h3>
            <ul class="space-y-2.5">
                @forelse($categories as $category)
                    <li>
                        <label class="flex items-center gap-3 cursor-pointer group min-h-[36px]">
                            <input
                                type="checkbox"
                                class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue"
                                wire:click="filterByCategory({{ $selectedCategory === $category->id ? 'null' : $category->id }})"
                                @checked($selectedCategory === $category->id)
                            >
                            <span class="text-sm text-gray-700 group-hover:text-brand-navy transition-colors">{{ $category->name }}</span>
                        </label>
                    </li>
                @empty
                    @foreach(['একাডেমিক', 'স্কিলস', 'টেস্ট প্রস্তুতি', 'CA/মেরিটাইম'] as $label)
                        <li>
                            <label class="flex items-center gap-3 min-h-[36px]">
                                <input type="checkbox" class="rounded border-gray-300 text-brand-blue" @checked($label === 'একাডেমিক' && ($isAcademicContext ?? false)) disabled>
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        </li>
                    @endforeach
                @endforelse
            </ul>
        </div>

        {{-- Price --}}
        <div class="mb-8">
            <h3 class="text-sm font-bold text-brand-navy mb-3">মূল্য</h3>
            <ul class="space-y-2.5">
                @foreach([
                    ['value' => 'all', 'label' => 'সকল মূল্য'],
                    ['value' => 'free', 'label' => 'ফ্রি'],
                    ['value' => 'paid', 'label' => 'পেইড'],
                    ['value' => 'under1500', 'label' => '৳১৫০০ এর নিচে'],
                ] as $option)
                    <li>
                        <label class="flex items-center gap-3 cursor-pointer group min-h-[36px]">
                            <input
                                type="radio"
                                name="priceFilter"
                                value="{{ $option['value'] }}"
                                wire:model.live="priceFilter"
                                class="border-gray-300 text-brand-blue focus:ring-brand-blue"
                            >
                            <span class="text-sm text-gray-700 group-hover:text-brand-navy transition-colors">{{ $option['label'] }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Level (display — no backend level field yet) --}}
        <div class="mb-8">
            <h3 class="text-sm font-bold text-brand-navy mb-3">লেভেল</h3>
            <ul class="space-y-2.5">
                @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                    <li>
                        <label class="flex items-center gap-3 min-h-[36px] opacity-60 cursor-not-allowed" title="শীঘ্রই উপলব্ধ">
                            <input type="checkbox" disabled class="rounded border-gray-300 text-brand-blue">
                            <span class="text-sm text-gray-500">{{ $level }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        <button
            type="button"
            wire:click="clearFilters"
            class="text-sm font-bold text-brand-blue hover:text-brand-navy transition-colors focus-visible:underline"
        >
            সব ফিল্টার মুছুন
        </button>
    </div>
</aside>
