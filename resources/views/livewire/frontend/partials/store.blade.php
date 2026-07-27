@php
    $allProducts = [
        [
            'type' => 'digital',
            'title' => 'Canva Pro (১ বছরের লাইসেন্স)',
            'badge' => 'সফটওয়্যার',
            'rating' => 4.6,
            'price' => 590,
            'original' => 1200,
            'icon' => 'palette',
        ],
        [
            'type' => 'digital',
            'title' => 'Microsoft Office 365 (১ বছর)',
            'badge' => 'সফটওয়্যার',
            'rating' => 4.8,
            'price' => 890,
            'original' => 1800,
            'icon' => 'document',
        ],
        [
            'type' => 'digital',
            'title' => 'ChatGPT Plus Shared Access',
            'badge' => 'টুলস',
            'rating' => 4.5,
            'price' => 450,
            'original' => 900,
            'icon' => 'robot',
        ],
        [
            'type' => 'digital',
            'title' => 'Grammarly Premium (৬ মাস)',
            'badge' => 'টুলস',
            'rating' => 4.7,
            'price' => 350,
            'original' => 700,
            'icon' => 'edit',
        ],
        [
            'type' => 'digital',
            'title' => 'Adobe Creative Cloud Student',
            'badge' => 'সফটওয়্যার',
            'rating' => 4.9,
            'price' => 1200,
            'original' => 2400,
            'icon' => 'palette',
        ],
        [
            'type' => 'physical',
            'title' => 'SSC Academic Book Bundle',
            'badge' => 'বই',
            'rating' => 4.4,
            'price' => 1500,
            'original' => 2200,
            'icon' => 'book',
        ],
        [
            'type' => 'physical',
            'title' => 'HSC Physics Lab Kit',
            'badge' => 'সরঞ্জাম',
            'rating' => 4.3,
            'price' => 980,
            'original' => 1400,
            'icon' => 'box',
        ],
        [
            'type' => 'physical',
            'title' => 'SecondShiftBD Branded Notebook Set',
            'badge' => 'স্টেশনারি',
            'rating' => 4.6,
            'price' => 299,
            'original' => 450,
            'icon' => 'book',
        ],
    ];

    $initialFilter = in_array(request()->query('type'), ['all', 'digital', 'physical'], true)
        ? request()->query('type')
        : 'all';

    $filters = [
        ['id' => 'all', 'label' => 'সকল প্রোডাক্ট'],
        ['id' => 'digital', 'label' => 'ডিজিটাল'],
        ['id' => 'physical', 'label' => 'ফিজিক্যাল'],
    ];
@endphp

<div
    x-data="{
        filter: '{{ $initialFilter }}',
        products: @js($allProducts),
        get filtered() {
            if (this.filter === 'all') return this.products;
            return this.products.filter(p => p.type === this.filter);
        },
        addToCart(product) {
            $dispatch('add-to-cart', { title: product.title, price: product.price });
        }
    }"
    class="min-h-screen"
>
    {{-- Header --}}
    <section class="glass-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-page-header">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">স্টোর</h1>
            <p class="mt-3 text-sm sm:text-base text-gray-500 max-w-2xl leading-relaxed">
                ডিজিটাল লাইসেন্স, টুলস ও ফিজিক্যাল প্রোডাক্ট — সব এক জায়গায় কিনুন।
            </p>

            {{-- Category filters --}}
            <div class="flex flex-wrap gap-2.5 mt-8">
                @foreach($filters as $item)
                    <button
                        type="button"
                        @click="filter = '{{ $item['id'] }}'"
                        :class="filter === '{{ $item['id'] }}'
                            ? 'glass-pill-active'
                            : 'glass-pill'"
                        class="md-ripple inline-flex items-center min-h-[44px] px-5 sm:px-6 py-2.5 rounded-full text-sm font-bold border transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                    >
                        {{ $item['label'] }}
                    </button>
                @endforeach
            </div>
            </div>
        </div>
    </section>

    {{-- Product grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
        <div class="glass-content-area">
        <p class="text-sm font-semibold text-gray-600 mb-6">
            <span class="text-brand-navy font-bold" x-text="filtered.length"></span>টি প্রোডাক্ট পাওয়া গেছে
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
            <template x-for="(product, index) in filtered" :key="filter + '-' + index">
                <article class="group flex flex-col glass-card-hover overflow-hidden h-full">
                    {{-- Thumbnail --}}
                    <div class="relative aspect-[4/3] glass-thumb overflow-hidden">
                        <div class="course-thumb-pattern absolute inset-0"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            {{-- Icon variants --}}
                            <svg x-show="product.icon === 'palette'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            <svg x-show="product.icon === 'document'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <svg x-show="product.icon === 'robot'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/><circle cx="9" cy="11" r="1" fill="currentColor"/><circle cx="15" cy="11" r="1" fill="currentColor"/></svg>
                            <svg x-show="product.icon === 'edit'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <svg x-show="product.icon === 'book'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <svg x-show="product.icon === 'box'" class="w-12 h-12 text-brand-navy/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="absolute top-3 right-3 glass-pill text-brand-navy text-[11px] font-bold px-2.5 py-1 rounded-md shadow-glass-sm" x-text="product.badge"></span>
                    </div>

                    {{-- Details --}}
                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                        <h2 class="text-sm sm:text-base font-bold text-brand-navy leading-snug line-clamp-2 min-h-[2.75rem] mb-2" x-text="product.title"></h2>

                        <div class="flex items-center gap-1 text-xs mb-3">
                            <svg class="w-3.5 h-3.5 text-brand-gold shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold text-brand-navy" x-text="product.rating"></span>
                        </div>

                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-lg sm:text-xl font-extrabold text-brand-blue" x-text="'৳' + product.price.toLocaleString('en-US')"></span>
                            <span class="text-sm text-gray-400 line-through" x-text="'৳' + product.original.toLocaleString('en-US')"></span>
                        </div>

                        <button
                            type="button"
                            @click="addToCart(product)"
                            class="md-ripple mt-auto w-full min-h-[44px] inline-flex items-center justify-center gap-2 rounded-xl glass-btn-ghost text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            কার্টে যোগ করুন
                        </button>
                    </div>
                </article>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="filtered.length === 0" class="flex flex-col items-center justify-center py-20 glass-empty" style="display: none;">
            <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <p class="text-lg font-bold text-brand-navy">কোনো প্রোডাক্ট পাওয়া যায়নি</p>
            <p class="text-gray-500 mt-1 text-sm">অন্য ফিল্টার দেখুন।</p>
        </div>
        </div>
    </section>
</div>
