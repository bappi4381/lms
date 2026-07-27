<div
    x-data="{
        open: false,
        items: [],
        load() {
            try {
                this.items = JSON.parse(localStorage.getItem('secondshiftbd_cart') || '[]');
            } catch {
                this.items = [];
            }
            this.broadcast();
        },
        save() {
            localStorage.setItem('secondshiftbd_cart', JSON.stringify(this.items));
            this.broadcast();
        },
        broadcast() {
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.totalItems } }));
        },
        get totalItems() {
            return this.items.reduce((sum, item) => sum + item.quantity, 0);
        },
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        slug(title) {
            return title.toLowerCase().replace(/[^a-z0-9\u0980-\u09FF]+/g, '-');
        },
        addItem(product) {
            if (!product?.title) return;
            const id = this.slug(product.title);
            const existing = this.items.find(i => i.id === id);
            if (existing) {
                existing.quantity += 1;
            } else {
                this.items.push({ id, title: product.title, price: product.price || 0, quantity: 1 });
            }
            this.save();
            this.open = true;
        },
        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            this.save();
        },
        clearCart() {
            this.items = [];
            this.save();
        },
        close() {
            this.open = false;
        }
    }"
    x-init="load()"
    x-on:open-cart-drawer.window="open = true"
    x-on:add-to-cart.window="addItem($event.detail)"
    x-show="open"
    class="relative z-[100]"
    aria-labelledby="cart-drawer-title"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="close()"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 glass-backdrop"
        @click="close()"
    ></div>

    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div
                    x-show="open"
                    x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md"
                >
                    <div class="flex h-full flex-col glass-drawer">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 sm:px-6 py-5 border-b glass-divider">
                            <h2 id="cart-drawer-title" class="text-lg font-extrabold text-brand-navy">
                                কার্ট (<span x-text="totalItems">0</span>)
                            </h2>
                            <button
                                type="button"
                                @click="close()"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full glass-icon-btn text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                                aria-label="বন্ধ করুন"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="flex-1 overflow-y-auto px-5 sm:px-6 py-6">
                            {{-- Empty state --}}
                            <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-16 h-16 rounded-full glass-icon-box flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <p class="text-base font-semibold text-gray-600">কার্ট খালি আছে</p>
                                <a
                                    href="{{ route('courses.list', ['store' => 1]) }}"
                                    @click="close()"
                                    class="mt-6 md-ripple inline-flex items-center min-h-[44px] px-6 py-2.5 rounded-xl bg-brand-navy text-white text-sm font-bold hover:bg-brand-navy-light transition-colors"
                                >
                                    স্টোর দেখুন
                                </a>
                            </div>

                            {{-- Cart items --}}
                            <ul x-show="items.length > 0" class="space-y-4">
                                <template x-for="item in items" :key="item.id">
                                    <li class="flex gap-4 p-4 glass-cart-item">
                                        <div class="shrink-0 w-14 h-14 rounded-lg bg-brand-blue-light course-thumb-pattern relative overflow-hidden"></div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-brand-navy line-clamp-2 leading-snug" x-text="item.title"></h3>
                                            <p class="text-sm font-extrabold text-brand-blue mt-1" x-text="'৳' + (item.price * item.quantity).toLocaleString('en-US')"></p>
                                            <p class="text-xs text-gray-400 mt-0.5" x-show="item.quantity > 1" x-text="'পরিমাণ: ' + item.quantity"></p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="removeItem(item.id)"
                                            class="shrink-0 p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                                            aria-label="সরান"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        {{-- Footer --}}
                        <div x-show="items.length > 0" class="border-t glass-divider px-5 sm:px-6 py-5 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-gray-600">মোট</span>
                                <span class="text-lg font-extrabold text-brand-navy" x-text="'৳' + subtotal.toLocaleString('en-US')"></span>
                            </div>
                            <button
                                type="button"
                                class="md-ripple w-full min-h-[48px] rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white text-sm font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                            >
                                চেকআউট করুন
                            </button>
                            <button
                                type="button"
                                @click="clearCart()"
                                class="w-full text-sm font-semibold text-gray-400 hover:text-red-500 transition-colors py-2"
                            >
                                কার্ট খালি করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
